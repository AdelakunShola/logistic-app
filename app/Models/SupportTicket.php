<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class SupportTicket extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    
    protected $guarded = [];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Generate unique ticket number
     */
    public static function generateTicketNumber()
    {
        $lastTicket = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastTicket ? ((int) substr($lastTicket->ticket_number, 5)) + 1 : 1;
        
        return 'TKT-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method to generate ticket number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = self::generateTicketNumber();
            }
        });
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    /**
     * Scopes
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeEscalated($query)
    {
        return $query->where('status', 'escalated');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    /**
     * Methods
     */
    public function markAsResolved($resolution = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolution' => $resolution ?? $this->resolution,
            'resolved_at' => now(),
        ]);

        return $this;
    }

    public function assignTo($userId)
    {
        $this->update([
            'assigned_to' => $userId,
            'status' => $this->status === 'open' ? 'in_progress' : $this->status,
        ]);

        return $this;
    }

    public function escalate()
    {
        $this->update([
            'status' => 'escalated',
            'priority' => 'urgent',
        ]);

        return $this;
    }

    public function close()
    {
        $this->update([
            'status' => 'closed',
        ]);

        return $this;
    }

    /**
     * Accessors
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'gray',
            default => 'gray',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'blue',
            'in_progress' => 'yellow',
            'resolved' => 'green',
            'closed' => 'gray',
            'escalated' => 'red',
            default => 'gray',
        };
    }
}
