<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
use LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'is_verified' => 'boolean',
        'is_available' => 'boolean',
        'salary' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'wallet_balance' => 'decimal:2',
        'vehicle_capacity' => 'decimal:2',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'last_location_update' => 'datetime',
        'rating' => 'decimal:2',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'last_login_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'preferred_delivery_areas' => 'array',
        'license_expiry' => 'date',
        'is_tracking_active' => 'boolean',
    'current_speed' => 'decimal:2',
    ];



    public function locationHistory()
{
    return $this->hasMany(DriverLocationHistory::class, 'driver_id');
}

public function getNameAttribute(): string
{
    return trim("{$this->first_name} {$this->last_name}");
}

/**
 * Get latest location
 */
public function getLatestLocation()
{
    return $this->locationHistory()
        ->latest('recorded_at')
        ->first();
}

/**
 * Get active shipments for tracking
 */
public function activeShipmentsForTracking()
{
    return $this->assignedShipments()
        ->whereIn('status', ['picked_up', 'in_transit', 'out_for_delivery'])
        ->get();
}

/**
 * Check if driver is currently on delivery
 */
public function isOnDelivery()
{
    return $this->is_tracking_active && 
           $this->activeShipmentsForTracking()->isNotEmpty();
}

/**
 * Get current coordinates as array [lng, lat] for Mapbox
 */
public function getCurrentCoordinates()
{
    if (!$this->current_latitude || !$this->current_longitude) {
        return null;
    }

    return [
        (float) $this->current_longitude,
        (float) $this->current_latitude
    ];
}

/**
 * Update location from GPS data
 */
public function updateLocation($latitude, $longitude, $speed = null, $heading = null)
{
    $this->update([
        'current_latitude' => $latitude,
        'current_longitude' => $longitude,
        'current_speed' => $speed,
        'current_heading' => $heading,
        'last_location_update' => now(),
    ]);

    return $this;
}

/**
 * Start tracking session
 */
public function startTracking()
{
    $this->update([
        'is_tracking_active' => true,
        'last_location_update' => now(),
    ]);

    return $this;
}

/**
 * Stop tracking session
 */
public function stopTracking()
{
    $this->update([
        'is_tracking_active' => false,
    ]);

    return $this;
}

    // Relationships
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

      public function notificationPreferences()
    {
        return $this->hasOne(UserNotificationPreference::class);
    }

    public function assignedBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'assigned_branch_id');
    }

    public function assignedHub(): BelongsTo
    {
        return $this->belongsTo(Hub::class, 'assigned_hub_id');
    }


    public function assignedWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'assigned_warehouse_id');
    }

    

    public function assignedVehicle(): HasOne
{
    return $this->hasOne(Vehicle::class, 'assigned_driver_id');
}

// Also add this method for convenience
public function vehicle()
{
    return $this->hasOne(Vehicle::class, 'assigned_driver_id');
}

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'assigned_driver_id');
    }

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class, 'assigned_driver_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->role === $role;
        }
        
        return in_array($this->role, $role);
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }




    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

 

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Scopes
     */
    public function scopeDrivers($query)
    {
        return $query->where('role', 'driver');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }


    public function getOnTimeRateAttribute()
    {
        if ($this->total_deliveries > 0) {
            return ($this->successful_deliveries / $this->total_deliveries) * 100;
        }
        return 0;
    }







    public function performanceMetrics()
{
    return $this->hasMany(DriverPerformanceMetric::class, 'driver_id');
}

public function assignments()
{
    return $this->hasMany(DriverAssignment::class, 'driver_id');
}

public function currentAssignment()
{
    return $this->hasOne(DriverAssignment::class, 'driver_id')
        ->where('status', 'active')
        ->latest();
}

public function feedback()
{
    return $this->hasMany(CustomerFeedback::class, 'driver_id');
}

public function complaints()
{
    return $this->feedback()->where('feedback_type', 'complaint');
}

public function compliments()
{
    return $this->feedback()->where('feedback_type', 'compliment');
}

public function delays()
{
    return $this->hasMany(ShipmentDelay::class, 'driver_id');
}




public function unreadNotifications()
{
    return $this->notifications()->where('is_read', false);
}



    public function shipmentsAsSender()
    {
        return $this->hasMany(Shipment::class, 'sender_id');
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

    
public function assignedShipments()
{
    return $this->hasMany(Shipment::class, 'assigned_driver_id');
}




public function branch()
{
    return $this->belongsTo(Branch::class);
}


public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}

public function trackingUpdates()
{
    return $this->hasMany(ShipmentTracking::class, 'updated_by');
}


public function getUnreadNotificationsCountAttribute()
{
    return $this->unreadNotifications()->count();
}


public function isCustomer()
{
    return $this->role === 'customer';
}



public function orders()
{
    return $this->hasMany(Order::class, 'customer_id');
}

public function assignedOrders()
{
    return $this->hasMany(Order::class, 'assigned_driver_id');
}





    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

 
}
































    




