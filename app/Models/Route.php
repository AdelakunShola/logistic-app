<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class Route extends Model
{
    use HasFactory;
use LogsActivity;
    protected $guarded = [];


    protected $casts = [
        'route_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_distance' => 'decimal:2',
    ];

    // Relationships
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function routeShipments()
    {
        return $this->hasMany(RouteShipment::class)->orderBy('sequence_number');
    }

    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'route_shipments')
                    ->withPivot('sequence_number', 'status', 'completed_at')
                    ->withTimestamps()
                    ->orderBy('route_shipments.sequence_number');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('route_date', today());
    }

    // Methods
    public function startRoute()
    {
        $this->update([
            'status' => 'in_progress',
            'start_time' => now(),
        ]);

        return $this;
    }

    public function completeRoute()
    {
        $this->update([
            'status' => 'completed',
            'end_time' => now(),
        ]);

        return $this;
    }

    public function getTotalShipmentsAttribute()
    {
        return $this->routeShipments()->count();
    }

    public function getCompletedShipmentsAttribute()
    {
        return $this->routeShipments()->where('status', 'completed')->count();
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_shipments == 0) {
            return 0;
        }

        return round(($this->completed_shipments / $this->total_shipments) * 100, 2);
    }
}