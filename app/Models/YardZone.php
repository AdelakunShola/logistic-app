<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YardZone extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'position_data' => 'array',
    ];

    public function yard()
    {
        return $this->belongsTo(Yard::class);
    }

    public function slots()
    {
        return $this->hasMany(YardSlot::class);
    }

    public function activeSlots()
    {
        return $this->hasMany(YardSlot::class)->where('status', '!=', 'blocked');
    }

    public function availableSlots()
    {
        return $this->hasMany(YardSlot::class)->where('status', 'available');
    }

    public function getOccupiedCountAttribute()
    {
        return $this->slots()->where('status', 'occupied')->count();
    }

    public function getUtilizationPercentageAttribute()
    {
        if ($this->capacity === 0) return 0;
        return round(($this->occupied_count / $this->capacity) * 100, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
