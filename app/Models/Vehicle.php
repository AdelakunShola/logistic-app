<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'capacity_weight' => 'decimal:2',
        'capacity_volume' => 'decimal:2',
        'current_fuel_level' => 'decimal:2',
        'utilization_percentage' => 'decimal:2',
        'current_load' => 'decimal:2',
        'mileage' => 'decimal:2',
        'fuel_capacity' => 'decimal:2',
        'registration_date' => 'date',
        'registration_expiry' => 'date',
        'insurance_expiry' => 'date',
        'last_service_date' => 'date',
        'next_service_date' => 'date',
        'alert_count' => 'integer',
        'year' => 'integer',
        
    ];

    // Relationships
    public function assignedDriver()
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }







    public function getLastCompletedMaintenanceAttribute()
{
    return $this->maintenanceLogs()
        ->where('status', 'completed')
        ->orderBy('updated_at', 'desc')
        ->first();
}

public function getNextScheduledMaintenanceAttribute()
{
    return $this->maintenanceLogs()
        ->where('status', '!=', 'completed')
        ->orderBy('maintenance_date', 'asc')
        ->first();
}

public function getPendingMaintenanceCountAttribute()
{
    return $this->maintenanceLogs()
        ->where('status', '!=', 'completed')
        ->count();
}












       public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->make} {$this->model}";
    }

    public function assignedVehicle()
{
    return $this->belongsTo(Vehicle::class, 'assigned_vehicle_id');
}


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

     public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function hub()
    {
        return $this->belongsTo(Hub::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'model');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                     ->whereNull('assigned_driver_id');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('vehicle_type', $type);
    }

    
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
   

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeMaintenanceDue($query, $days = 30)
    {
        return $query->whereNotNull('next_service_date')
                     ->where('next_service_date', '<=', now()->addDays($days));
    }

    public function scopeLowFuel($query, $threshold = 30)
    {
        return $query->where('current_fuel_level', '<=', $threshold);
    }

    // Accessors & Mutators
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'active' => 'success',
            'inactive' => 'secondary',
            'maintenance' => 'warning',
            'repair' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getCapacityUtilizationAttribute()
    {
        if (!$this->capacity_weight || $this->capacity_weight == 0) {
            return 0;
        }

        return ($this->current_load / $this->capacity_weight) * 100;
    }

    public function getFuelStatusAttribute()
    {
        if ($this->current_fuel_level >= 60) {
            return 'good';
        } elseif ($this->current_fuel_level >= 30) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    public function getIsMaintenanceDueAttribute()
    {
        if (!$this->next_service_date) {
            return false;
        }

        return $this->next_service_date <= now()->addDays(30);
    }

    public function getIsInsuranceExpiringAttribute()
    {
        if (!$this->insurance_expiry) {
            return false;
        }

        return $this->insurance_expiry <= now()->addDays(60);
    }

    public function getIsRegistrationExpiringAttribute()
    {
        if (!$this->registration_expiry) {
            return false;
        }

        return $this->registration_expiry <= now()->addDays(60);
    }

    // Methods
    public function updateAlertCount()
    {
        $alerts = 0;

        // Check maintenance due
        if ($this->is_maintenance_due) {
            $alerts++;
        }

        // Check low fuel
        if ($this->current_fuel_level && $this->current_fuel_level < 30) {
            $alerts++;
        }

        // Check insurance expiry
        if ($this->is_insurance_expiring) {
            $alerts++;
        }

        // Check registration expiry
        if ($this->is_registration_expiring) {
            $alerts++;
        }

        $this->update(['alert_count' => $alerts]);

        return $alerts;
    }

    public function assignDriver($driverId)
    {
        $oldDriverId = $this->assigned_driver_id;
        
        $this->update(['assigned_driver_id' => $driverId]);

        // Create notification for new driver
        if ($driverId) {
            Notification::create([
                'user_id' => $driverId,
                'title' => 'Vehicle Assigned',
                'message' => "You have been assigned to vehicle {$this->vehicle_number}",
                'type' => 'info',
                'channel' => 'system',
            ]);
        }

        // Notify old driver
        if ($oldDriverId && $oldDriverId != $driverId) {
            Notification::create([
                'user_id' => $oldDriverId,
                'title' => 'Vehicle Unassigned',
                'message' => "You have been unassigned from vehicle {$this->vehicle_number}",
                'type' => 'warning',
                'channel' => 'system',
            ]);
        }

        return $this;
    }

    public function updateLocation($latitude, $longitude, $address = null)
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'current_location' => $address,
            'last_location_update' => now(),
        ]);

        return $this;
    }

    public function scheduleMaintenance($date, $notes = null)
    {
        $this->update([
            'next_service_date' => $date,
            'status' => 'maintenance',
            'notes' => $notes ?? $this->notes,
        ]);

        // Notify driver
        if ($this->assigned_driver_id) {
            Notification::create([
                'user_id' => $this->assigned_driver_id,
                'title' => 'Maintenance Scheduled',
                'message' => "Maintenance scheduled for vehicle {$this->vehicle_number} on {$date}",
                'type' => 'warning',
                'channel' => 'system',
            ]);
        }

        return $this;
    }

    public function completeMaintenance($notes = null)
    {
        $this->update([
            'last_service_date' => now(),
            'status' => 'active',
            'notes' => $notes ?? $this->notes,
        ]);

        $this->updateAlertCount();

        return $this;
    }

    // Boot method for model events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vehicle) {
            // Auto-calculate utilization
            if ($vehicle->capacity_weight && $vehicle->current_load) {
                $vehicle->utilization_percentage = ($vehicle->current_load / $vehicle->capacity_weight) * 100;
            }
        });

        static::updating(function ($vehicle) {
            // Auto-calculate utilization
            if ($vehicle->capacity_weight && $vehicle->current_load) {
                $vehicle->utilization_percentage = ($vehicle->current_load / $vehicle->capacity_weight) * 100;
            }

            // Update alerts
            if ($vehicle->isDirty(['next_service_date', 'current_fuel_level', 'insurance_expiry', 'registration_expiry'])) {
                $vehicle->updateAlertCount();
            }
        });

        static::created(function ($vehicle) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'model_type' => 'Vehicle',
                'model_id' => $vehicle->id,
                'description' => "Created vehicle: {$vehicle->vehicle_number}",
                'new_values' => json_encode($vehicle->toArray()),
            ]);
        });

        static::updated(function ($vehicle) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'model_type' => 'Vehicle',
                'model_id' => $vehicle->id,
                'description' => "Updated vehicle: {$vehicle->vehicle_number}",
                'old_values' => json_encode($vehicle->getOriginal()),
                'new_values' => json_encode($vehicle->getChanges()),
            ]);
        });

        static::deleted(function ($vehicle) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'model_type' => 'Vehicle',
                'model_id' => $vehicle->id,
                'description' => "Deleted vehicle: {$vehicle->vehicle_number}",
                'old_values' => json_encode($vehicle->toArray()),
            ]);
        });
    }

   
    public function scopeInMaintenance($query)
    {
        return $query->whereIn('status', ['maintenance', 'repair']);
    }

    /**
     * Accessors
     */
    public function getIsAvailableAttribute()
    {
        return $this->assigned_driver_id === null && $this->status === 'active';
    }

    public function getFuelPercentageAttribute()
    {
        return $this->current_fuel_level ?? 0;
    }



    public function routes()
    {
        return $this->hasMany(Route::class);
    }


   
}

