<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class RouteShipment extends Model

{
    use HasFactory;
use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'sequence_number' => 'integer',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeOrderedBySequence($query)
    {
        return $query->orderBy('sequence_number');
    }

    // Methods
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return $this;
    }

    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed',
            'completed_at' => now(),
        ]);

        return $this;
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

}
