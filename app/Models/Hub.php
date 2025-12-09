<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hub extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'assigned_hub_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Relationships
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'current_hub_id');
    }

    public function trackingHistory()
    {
        return $this->hasMany(ShipmentTracking::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('hub_type', $type);
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

    public function getAvailableCapacityAttribute()
    {
        return $this->capacity - $this->current_capacity;
    }

    public function isAtCapacity()
    {
        return $this->current_capacity >= $this->capacity;
    }
}