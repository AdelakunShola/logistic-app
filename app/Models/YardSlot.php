<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YardSlot extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'position_data' => 'array',
        'features' => 'array',
    ];

    public function zone()
    {
        return $this->belongsTo(YardZone::class, 'yard_zone_id');
    }

    public function yard()
    {
        return $this->hasOneThrough(Yard::class, YardZone::class, 'id', 'id', 'yard_zone_id', 'yard_id');
    }

    public function currentVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'current_vehicle_id');
    }

    public function currentDriver()
    {
        return $this->belongsTo(User::class, 'current_driver_id');
    }

    public function history()
    {
        return $this->hasMany(YardSlotHistory::class)->orderByDesc('created_at');
    }

    public function activeVisit()
    {
        return $this->hasOne(YardVisit::class, 'yard_slot_id')->whereNull('check_out_time');
    }

    public function visits()
    {
        return $this->hasMany(YardVisit::class, 'yard_slot_id');
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isOccupied()
    {
        return $this->status === 'occupied';
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeBySize($query, $size)
    {
        return $query->where('size', $size);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
