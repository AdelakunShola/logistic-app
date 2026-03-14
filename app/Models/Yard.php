<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Yard extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'yard_layout' => 'array',
        'auto_assign_enabled' => 'boolean',
        'allow_self_registration' => 'boolean',
        'require_appointment' => 'boolean',
        'operating_hours_start' => 'datetime',
        'operating_hours_end' => 'datetime',
    ];

    // Relationships
    public function zones()
    {
        return $this->hasMany(YardZone::class);
    }

    public function slots()
    {
        return $this->hasManyThrough(YardSlot::class, YardZone::class);
    }

    public function visits()
    {
        return $this->hasMany(YardVisit::class);
    }

    public function appointments()
    {
        return $this->hasMany(YardAppointment::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function hub()
    {
        return $this->belongsTo(Hub::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Accessors
    public function getOccupiedSlotsCountAttribute()
    {
        return $this->slots()->where('yard_slots.status', 'occupied')->count();
    }

    public function getAvailableSlotsCountAttribute()
    {
        return $this->slots()->where('yard_slots.status', 'available')->count();
    }

    public function getUtilizationPercentageAttribute()
    {
        $totalSlots = $this->slots()->count();
        if ($totalSlots === 0) return 0;
        $occupied = $this->slots()->whereIn('yard_slots.status', ['occupied', 'reserved'])->count();
        return round(($occupied / $totalSlots) * 100, 2);
    }

    public function getActiveVisitsCountAttribute()
    {
        return $this->visits()->whereNull('check_out_time')->count();
    }

    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->address, $this->city, $this->state, $this->postal_code, $this->country
        ]));
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
