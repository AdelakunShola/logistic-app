<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class Warehouse extends Model
{
    use HasFactory, SoftDeletes;
use LogsActivity;
    protected $guarded = [];
    protected $casts = [
        'operating_days' => 'array',
        'is_pickup_point' => 'boolean',
        'is_delivery_point' => 'boolean',
        'accepts_cod' => 'boolean',
        'has_cold_storage' => 'boolean',
        'has_24h_security' => 'boolean',
        'license_expiry' => 'date',
        'last_inspection_date' => 'date',
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
    ];

    // Relationships
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'current_warehouse_id');
    }

    public function originShipments()
    {
        return $this->hasMany(Shipment::class, 'origin_warehouse_id');
    }

    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'destination_warehouse_id');
    }

    public function inventories()
    {
        return $this->hasMany(WarehouseInventory::class);
    }

    public function transfersFrom()
    {
        return $this->hasMany(WarehouseTransfer::class, 'from_warehouse_id');
    }

    public function transfersTo()
    {
        return $this->hasMany(WarehouseTransfer::class, 'to_warehouse_id');
    }

    public function staff()
    {
        return $this->hasMany(User::class, 'warehouse_id');
    }

    // Accessors & Mutators
    public function getUtilizationPercentageAttribute($value)
    {
        if ($this->storage_capacity > 0) {
            return round(($this->current_occupancy / $this->storage_capacity) * 100, 2);
        }
        return 0;
    }

    public function getAvailableCapacityAttribute()
    {
        return $this->storage_capacity - $this->current_occupancy;
    }

    public function getIsFullAttribute()
    {
        return $this->current_occupancy >= $this->storage_capacity;
    }

    public function getIsNearCapacityAttribute()
    {
        return $this->utilization_percentage >= 80;
    }

    // Scopes
   

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function scopePickupPoints($query)
    {
        return $query->where('is_pickup_point', true);
    }

    public function scopeDeliveryPoints($query)
    {
        return $query->where('is_delivery_point', true);
    }

    // Methods
    public function updateOccupancy()
    {
        $this->current_occupancy = $this->inventories()
            ->whereNull('checked_out_at')
            ->count();
        $this->save();
    }

    public function canAcceptShipments()
    {
        return $this->status === 'active' && !$this->is_full;
    }

    public function incrementShipmentsProcessed()
    {
        $this->increment('total_shipments_processed');
    }

    public function incrementDeliveriesCompleted()
    {
        $this->increment('total_deliveries_completed');
    }








    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'assigned_warehouse_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    

    public function trackingHistory()
    {
        return $this->hasMany(ShipmentTracking::class);
    }


    // Scopes
    ///public function scopeActive($query)
    //{
    //    return $query->where('is_active', true);
    //}


     public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }





    public function getCurrentCapacityAttribute()
    {
        return $this->shipments()->whereIn('status', ['picked_up', 'in_transit'])->count();
    }

    

    public function isAtCapacity()
    {
        return $this->storage_capacity - $this->current_occupancy;
    }
}