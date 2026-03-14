<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class YardAppointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'scheduled_arrival' => 'datetime',
        'scheduled_departure' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            if (empty($appointment->confirmation_code)) {
                $appointment->confirmation_code = strtoupper(Str::random(8));
            }
        });
    }

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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_arrival', '>', now())
            ->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_arrival', today());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}
