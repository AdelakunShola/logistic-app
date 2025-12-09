<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;
class CustomerFeedback extends Model
{
    use LogsActivity;
    protected $table = 'customer_feedback';
    
    protected $guarded = [];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeComplaints($query)
    {
        return $query->where('feedback_type', 'complaint');
    }

    public function scopeCompliments($query)
    {
        return $query->where('feedback_type', 'compliment');
    }
}

