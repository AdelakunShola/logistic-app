<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class DriverLocationHistory extends Model
{
    use HasFactory;
use LogsActivity;
    protected $table = 'driver_location_histories';

    protected $guarded = [];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'speed' => 'decimal:2',
        'accuracy' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the most recent location for a driver
     */
    public static function getLatestLocation($driverId)
    {
        return static::where('driver_id', $driverId)
            ->latest('recorded_at')
            ->first();
    }

    /**
     * Get location history for a timeframe
     */
    public static function getHistory($driverId, $from, $to)
    {
        return static::where('driver_id', $driverId)
            ->whereBetween('recorded_at', [$from, $to])
            ->orderBy('recorded_at', 'asc')
            ->get();
    }
}