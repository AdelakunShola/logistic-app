<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class ReturnModel extends Model
{
    use HasFactory, SoftDeletes;
use LogsActivity;
    protected $table = 'returns';

     protected $guarded = [];

    protected $casts = [
        'return_date' => 'date',
        'scheduled_pickup_date' => 'date',
        'actual_pickup_date' => 'date',
        'refund_amount' => 'decimal:2',
        'request_date' => 'date',
        'customer_since' => 'date',
        'return_value' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'items' => 'array',
        'attached_images' => 'array',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];



    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function assignedDriver()
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending_review' => ['color' => 'yellow', 'icon' => 'clock', 'text' => 'Pending Review'],
            'approved' => ['color' => 'green', 'icon' => 'circle-check-big', 'text' => 'Approved'],
            'processing' => ['color' => 'blue', 'icon' => 'refresh-cw', 'text' => 'Processing'],
            'completed' => ['color' => 'green', 'icon' => 'circle-check-big', 'text' => 'Completed'],
            'rejected' => ['color' => 'red', 'icon' => 'circle-x', 'text' => 'Rejected'],
            'cancelled' => ['color' => 'gray', 'icon' => 'x', 'text' => 'Cancelled'],
        ];

        return $badges[$this->status] ?? $badges['pending_review'];
    }

    public function getFormattedReturnReasonAttribute()
    {
        $reasons = [
            'defective_product' => 'Defective Product',
            'wrong_item_sent' => 'Wrong Item Sent',
            'changed_mind' => 'Changed Mind',
            'damaged_in_transit' => 'Damaged in Transit',
            'not_as_described' => 'Not as Described',
            'quality_issue' => 'Quality Issue',
            'size_issue' => 'Size Issue',
            'other' => 'Other',
        ];

        return $reasons[$this->return_reason] ?? ucfirst(str_replace('_', ' ', $this->return_reason));
    }

    public function getItemsListAttribute()
    {
        if (!$this->items || !is_array($this->items)) {
            return 'No items';
        }

        $itemNames = array_map(function($item) {
            return $item['description'] ?? $item['name'] ?? 'Unknown Item';
        }, $this->items);

        return implode(', ', $itemNames);
    }

    public function getImageCountAttribute()
    {
        if (!$this->attached_images || !is_array($this->attached_images)) {
            return 0;
        }

        return count($this->attached_images);
    }

    // Static method to generate return number
    public static function generateReturnNumber()
    {
        $lastReturn = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastReturn ? ((int) substr($lastReturn->return_number, 4)) + 1 : 1;
        
        return 'RET-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // Scopes
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        return $query;
    }

    // Methods
    public function approve($approvedBy, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
            'admin_notes' => $notes,
        ]);

        // Create notification for customer
        Notification::create([
            'user_id' => $this->customer_id,
            'title' => 'Return Approved',
            'message' => "Your return request {$this->return_number} has been approved.",
            'type' => 'success',
            'channel' => 'system',
        ]);
    }

    public function reject($rejectedBy, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $rejectedBy,
            'admin_notes' => $reason,
        ]);

        // Create notification for customer
        Notification::create([
            'user_id' => $this->customer_id,
            'title' => 'Return Rejected',
            'message' => "Your return request {$this->return_number} has been rejected. Reason: {$reason}",
            'type' => 'error',
            'channel' => 'system',
        ]);
    }

    public function complete($completedBy, $notes = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'refund_status' => 'completed',
            'refund_date' => now(),
            'admin_notes' => $notes,
        ]);

        // Create notification for customer
        Notification::create([
            'user_id' => $this->customer_id,
            'title' => 'Return Completed',
            'message' => "Your return {$this->return_number} has been completed. Refund of \${$this->refund_amount} has been processed.",
            'type' => 'success',
            'channel' => 'system',
        ]);
    }



}

