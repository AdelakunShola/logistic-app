<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YardVisit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function yard()
    {
        return $this->belongsTo(Yard::class);
    }

    public function slot()
    {
        return $this->belongsTo(YardSlot::class, 'yard_slot_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function slotHistory()
    {
        return $this->hasMany(YardSlotHistory::class);
    }

    // Accessors
    public function getIsCheckedOutAttribute()
    {
        return $this->check_out_time !== null;
    }

    public function getDurationMinutesAttribute()
    {
        $end = $this->check_out_time ?? now();
        return $this->check_in_time->diffInMinutes($end);
    }

    public function getIsOverstayAttribute()
    {
        if ($this->is_checked_out) return false;
        return $this->duration_minutes > $this->expected_duration_minutes;
    }

    public function getOverstayMinutesAttribute()
    {
        if (!$this->is_overstay) return 0;
        return $this->duration_minutes - $this->expected_duration_minutes;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('check_out_time');
    }

    public function scopeCheckedOut($query)
    {
        return $query->whereNotNull('check_out_time');
    }

    public function scopeOverstaying($query)
    {
        return $query->whereNull('check_out_time')
            ->whereRaw('TIMESTAMPDIFF(MINUTE, check_in_time, NOW()) > expected_duration_minutes');
    }
}
