<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'assigned_branch_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'current_branch_id');
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
}
