<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class WarehouseTransfer extends Model
{
    use HasFactory, SoftDeletes;
use LogsActivity;
    protected $guarded = [];
    
    protected $casts = [
        'initiated_at' => 'datetime',
        'departed_at' => 'datetime',
        'arrived_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================
    
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // ========================================
    // SCOPES
    // ========================================
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByWarehouse($query, $warehouseId, $direction = 'from')
    {
        $column = $direction === 'from' ? 'from_warehouse_id' : 'to_warehouse_id';
        return $query->where($column, $warehouseId);
    }

    public function scopeByDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    public function scopeByTransferType($query, $type)
    {
        return $query->where('transfer_type', $type);
    }

    // ========================================
    // ACCESSORS & ATTRIBUTES
    // ========================================
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_transit' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getDurationAttribute()
    {
        if (!$this->departed_at || !$this->arrived_at) {
            return null;
        }

        $hours = $this->departed_at->diffInHours($this->arrived_at);
        $minutes = $this->departed_at->diffInMinutes($this->arrived_at) % 60;
        
        return "{$hours}h {$minutes}m";
    }

    public function getTransferCodeAttribute()
    {
        return 'TRF-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getEstimatedArrivalAttribute()
    {
        if (!$this->departed_at) {
            return null;
        }

        // Add estimated duration (you can make this dynamic based on distance)
        return $this->departed_at->addHours(2);
    }

    // ========================================
    // PERMISSION METHODS
    // ========================================
    
    public function canBeEdited()
    {
        return in_array($this->status, ['pending']);
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'in_transit']);
    }

    public function canAssignDriver()
    {
        return $this->status === 'pending' && !$this->driver_id;
    }

    public function canDepart()
    {
        return $this->status === 'pending' && $this->driver_id;
    }

    public function canArrive()
    {
        return $this->status === 'in_transit' && !$this->arrived_at;
    }

    public function canComplete()
    {
        return $this->status === 'in_transit' && $this->arrived_at;
    }

    // ========================================
    // STATUS UPDATE METHODS
    // ========================================
    
    public function markAsDeparted($driverId = null)
    {
        $this->update([
            'status' => 'in_transit',
            'departed_at' => now(),
            'driver_id' => $driverId ?? $this->driver_id,
        ]);

        // Get vehicle from driver
        if ($this->driver && $this->driver->vehicle_number) {
            $this->update(['vehicle_number' => $this->driver->vehicle_number]);
        }

        // Update shipment status
        if ($this->shipment) {
            $this->shipment->update([
                'status' => 'in_transit',
                'departed_origin_warehouse' => now(),
            ]);
        }
    }

    public function markAsArrived()
    {
        $this->update([
            'arrived_at' => now(),
        ]);

        // Update shipment
        if ($this->shipment) {
            $this->shipment->update([
                'arrived_at_destination_warehouse' => now(),
            ]);
        }
    }

    public function markAsCompleted($receivedBy)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'received_by' => $receivedBy,
        ]);

        // Update shipment warehouse location
        if ($this->shipment) {
            $this->shipment->update([
                'current_warehouse_id' => $this->to_warehouse_id,
                'status' => 'at_warehouse',
            ]);
        }

        // Update warehouse occupancy
        $this->fromWarehouse->decrement('current_occupancy');
        $this->toWarehouse->increment('current_occupancy');
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'reason' => $reason,
        ]);

        // Revert shipment status if needed
        if ($this->shipment && $this->status === 'pending') {
            $this->shipment->update([
                'status' => 'at_warehouse',
            ]);
        }
    }

    // ========================================
    // HELPER METHODS
    // ========================================
    
    public function getStatusLabelAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function getTransferTypeLabelAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->transfer_type));
    }
}