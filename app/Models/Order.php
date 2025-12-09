<?php
// ========================================
// Order.php Model
// ========================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'order_type',
        'order_date',
        'scheduled_date',
        'scheduled_time_from',
        'scheduled_time_to',
        'status',
        'priority',
        'pickup_address',
        'delivery_address',
        'street_address',
        'city',
        'state',
        'zip_code',
        'country',
        'items',
        'order_value',
        'service_charge',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'payment_status',
        'payment_method',
        'payment_terms',
        'tracking_number',
        'shipping_method',
        'delivery_progress',
        'customer_phone',
        'customer_company',
        'customer_email',
        'assigned_driver_id',
        'special_instructions',
        'internal_notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'items' => 'array',
        'order_date' => 'date',
        'scheduled_date' => 'date',
        'order_value' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivery_progress' => 'integer',
    ];

    protected $appends = ['formatted_total'];

    /**
     * Get the customer that owns the order
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the assigned driver
     */
    public function assignedDriver()
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    /**
     * Get activity logs for this order
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'model_id')
                    ->where('model_type', 'Order')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get notifications for this order
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'order_id');
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute()
{
    return '₦' . number_format($this->total_amount, 2);
}


    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'processing' => 'blue',
            'confirmed' => 'green',
            'assigned' => 'purple',
            'in_transit' => 'blue',
            'in_progress' => 'indigo',
            'delivered' => 'green',
            'completed' => 'green',
            'delayed' => 'orange',
            'cancelled' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by priority
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for filtering by payment status
     */
    public function scopePaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Scope for recent orders
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('order_date', '>=', now()->subDays($days));
    }
}


