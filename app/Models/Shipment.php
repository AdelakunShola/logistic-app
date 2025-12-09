<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class Shipment extends Model
{
    use HasFactory, SoftDeletes;
use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'pickup_latitude' => 'decimal:8',
        'pickup_longitude' => 'decimal:8',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'total_weight' => 'decimal:2',
        'total_value' => 'decimal:2',
        'cod_amount' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
        'insurance_required' => 'boolean',
        'signature_required' => 'boolean',
        'temperature_controlled' => 'boolean',
        'fragile_handling' => 'boolean',
        'base_price' => 'decimal:2',
        'weight_charge' => 'decimal:2',
        'distance_charge' => 'decimal:2',
        'priority_charge' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'additional_services_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'pickup_date' => 'datetime',
        'preferred_delivery_date' => 'datetime',
        'expected_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'pickup_scheduled_date' => 'datetime',
        'items' => 'array',
        'delivery_attempts' => 'integer',
        'number_of_items' => 'integer',
        'tracking_history' => 'array',
        'calculated_route' => 'array',
    'route_distance' => 'decimal:2',
    'route_calculated_at' => 'datetime',
    'driver_started_at' => 'datetime',
    'driver_arrived_at' => 'datetime',
    ];



public function driverLocationHistory()
{
    return $this->hasMany(DriverLocationHistory::class);
}

/**
 * Get the latest driver location for this shipment
 */
public function getLatestDriverLocation()
{
    if (!$this->assigned_driver_id) {
        return null;
    }

    return DriverLocationHistory::where('driver_id', $this->assigned_driver_id)
        ->where('shipment_id', $this->id)
        ->latest('recorded_at')
        ->first();
}

/**
 * Check if shipment has active tracking
 */
public function hasActiveTracking()
{
    return $this->assignedDriver && 
           $this->assignedDriver->is_tracking_active &&
           in_array($this->status, ['picked_up', 'in_transit', 'out_for_delivery']);
}

/**
 * Get calculated route data
 */
public function getRouteData()
{
    if (!$this->calculated_route) {
        return null;
    }

    return is_array($this->calculated_route) 
        ? $this->calculated_route 
        : json_decode($this->calculated_route, true);
}

/**
 * Check if route has been calculated
 */
public function hasCalculatedRoute()
{
    return !empty($this->calculated_route);
}

/**
 * Get ETA (Estimated Time of Arrival)
 */
public function getEtaAttribute()
{
    if ($this->expected_delivery_date) {
        return $this->expected_delivery_date;
    }

    if ($this->estimated_duration && $this->driver_started_at) {
        return $this->driver_started_at->addMinutes($this->estimated_duration);
    }

    return null;
}

/**
 * Calculate remaining distance from driver's current location
 */
public function getRemainingDistance()
{
    if (!$this->assignedDriver || 
        !$this->assignedDriver->current_latitude || 
        !$this->delivery_latitude) {
        return null;
    }

    $earthRadius = 6371; // km

    $latFrom = deg2rad($this->assignedDriver->current_latitude);
    $lonFrom = deg2rad($this->assignedDriver->current_longitude);
    $latTo = deg2rad($this->delivery_latitude);
    $lonTo = deg2rad($this->delivery_longitude);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

    return round($angle * $earthRadius, 2);
}

/**
 * Get tracking URL for customers
 */
public function getTrackingUrlAttribute()
{
    return route('tracking.customer', $this->tracking_number);
}

/**
 * Check if driver has started journey
 */
public function hasDriverStarted()
{
    return !is_null($this->driver_started_at);
}

/**
 * Check if driver has arrived at destination
 */
public function hasDriverArrived()
{
    return !is_null($this->driver_arrived_at);
}
    
    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }




    public function return()
{
    return $this->hasOne(ReturnModel::class);
}

/**
 * Check if shipment has a return
 */
public function hasReturn()
{
    return $this->return()->exists();
}

/**
 * Check if shipment can be returned
 */
public function canBeReturned()
{
    return in_array($this->status, ['delivered', 'completed']) && !$this->hasReturn();
}

    public function issues()
{
    return $this->hasMany(ShipmentIssue::class);
}
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function carrier()
    {
        return $this->belongsTo(Carriers::class);
    }

    public function assignedDriver()
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    public function assignedVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'assigned_vehicle_id');
    }

    public function currentBranch()
    {
        return $this->belongsTo(Branch::class, 'current_branch_id');
    }


  

    public function currentHub()
    {
        return $this->belongsTo(Hub::class, 'current_hub_id');
    }

    public function shipmentItems()
    {
        return $this->hasMany(shipment_items::class);
    }

    public function routeShipments()
    {
        return $this->hasMany(RouteShipment::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_shipments')
                    ->withPivot('sequence_number', 'status', 'completed_at')
                    ->withTimestamps();
    }

    public function trackingHistory()
    {
        return $this->hasMany(ShipmentTracking::class)->orderBy('created_at', 'desc');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'model');
    }

    // Scopes
    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['draft', 'cancelled', 'delivered']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByDriver($query, $driverId)
    {
        return $query->where('assigned_driver_id', $driverId);
    }

    public function scopePendingPickup($query)
    {
        return $query->where('status', 'pending')
                    ->whereNotNull('pickup_scheduled_date');
    }

    public function scopeInTransit($query)
    {
        return $query->whereIn('status', ['picked_up', 'in_transit', 'out_for_delivery']);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_delivery_date', '<', now())
                    ->whereNotIn('status', ['delivered', 'cancelled']);
    }

    public function scopeRequiresSignature($query)
    {
        return $query->where('signature_required', true);
    }

    public function scopeHighValue($query, $threshold = 1000)
    {
        return $query->where('total_value', '>=', $threshold);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }



    public function delays(): HasMany
{
    return $this->hasMany(ShipmentDelay::class);
}

public function currentDelay(): HasOne
{
    return $this->hasOne(ShipmentDelay::class)->whereNull('resolved_at')->latest();
}

public function getIsDelayedAttribute(): bool
{
    return $this->delays()->whereNull('resolved_at')->exists();
}

public function recordDelay(array $data): ShipmentDelay
{
    $delay = $this->delays()->create([
        'driver_id' => $data['driver_id'] ?? $this->assigned_driver_id,
        'delay_reason' => $data['delay_reason'],
        'delay_description' => $data['delay_description'] ?? null,
        'delay_duration_minutes' => $data['delay_duration_minutes'] ?? 0,
        'delayed_at' => $data['delayed_at'] ?? now(),
        'original_delivery_date' => $data['original_delivery_date'] ?? $this->expected_delivery_date,
        'new_delivery_date' => $data['new_delivery_date'],
        'delay_hours' => $data['delay_hours'],
        'reported_by' => $data['reported_by'] ?? auth()->id(),
        'customer_notified' => false,
    ]);

    $this->expected_delivery_date = $data['new_delivery_date'];
    $this->save();

    ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'delay_recorded',
        'model_type' => 'Shipment',
        'model_id' => $this->id,
        'description' => "Delay recorded for shipment {$this->tracking_number}: {$data['delay_hours']} hours",
        'new_values' => $delay->toArray(),
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    if ($delay->is_critical) {
        $delay->escalate();
    }

    return $delay;
}

    // Accessors & Mutators
    public function getFormattedTotalAmountAttribute()
    {
        return '$' . number_format($this->total_amount, 2);
    }




    /**
 * Get the progress percentage based on status
 */
public function getProgressAttribute()
{
    $statusProgress = [
        'draft' => 0,
        'pending' => 10,
        'picked_up' => 30,
        'in_transit' => 65,
        'out_for_delivery' => 85,
        'delivered' => 100,
        'failed' => 0,
        'returned' => 0,
        'cancelled' => 0,
    ];
    
    return $statusProgress[$this->status] ?? 0;
}




    public function getFormattedWeightAttribute()
    {
        return number_format($this->total_weight, 2) . ' lbs';
    }

    public function getPickupFullAddressAttribute()
    {
        $parts = array_filter([
            $this->pickup_address,
            $this->pickup_address_line2,
            $this->pickup_city,
            $this->pickup_state,
            $this->pickup_postal_code,
            $this->pickup_country,
        ]);
        return implode(', ', $parts);
    }

    public function getDeliveryFullAddressAttribute()
    {
        $parts = array_filter([
            $this->delivery_address,
            $this->delivery_address_line2,
            $this->delivery_city,
            $this->delivery_state,
            $this->delivery_postal_code,
            $this->delivery_country,
        ]);
        return implode(', ', $parts);
    }

    public function getStatusBadgeColorAttribute()
    {
        $colors = [
            'draft' => 'gray',
            'pending' => 'yellow',
            'picked_up' => 'blue',
            'in_transit' => 'indigo',
            'out_for_delivery' => 'purple',
            'delivered' => 'green',
            'failed' => 'red',
            'returned' => 'orange',
            'cancelled' => 'red',
        ];
        return $colors[$this->status] ?? 'gray';
    }

    public function getEstimatedDeliveryDaysAttribute()
    {
        $days = [
            'standard' => '5-7',
            'express' => '2-3',
            'overnight' => '1',
        ];
        return $days[$this->delivery_priority] ?? '5-7';
    }

    public function getIsOverdueAttribute()
    {
        return $this->expected_delivery_date && 
               $this->expected_delivery_date->isPast() && 
               !in_array($this->status, ['delivered', 'cancelled']);
    }

    public function getCanBeEditedAttribute()
    {
        return in_array($this->status, ['draft', 'pending']);
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, ['draft', 'pending', 'picked_up']);
    }

    // Static Methods
    public static function generateTrackingNumber()
    {
        do {
            $trackingNumber = 'TRK' . strtoupper(uniqid()) . rand(1000, 9999);
        } while (self::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    // Instance Methods
    public function calculateDistance()
    {
        if (!$this->pickup_latitude || !$this->pickup_longitude || 
            !$this->delivery_latitude || !$this->delivery_longitude) {
            return 0;
        }

        $earthRadius = 3959; // miles

        $latFrom = deg2rad($this->pickup_latitude);
        $lonFrom = deg2rad($this->pickup_longitude);
        $latTo = deg2rad($this->delivery_latitude);
        $lonTo = deg2rad($this->delivery_longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius, 2);
    }

    public function calculatePricing()
{
    // 1. Base Price
    $this->base_price = $this->calculateBasePrice();
    
    // 2. Weight Charge - Rate per lb × Total Weight
    $weightRatePerLb = floatval(Setting::get('pricing_weight_rate_per_lb', 0.50));
    $this->weight_charge = $this->total_weight * $weightRatePerLb;
    
    // 3. Distance Charge - Zone-based flat rate
    $this->distance_charge = $this->calculateDistanceChargeFromZone();
    
    // 4. Priority Charge (same as base price in this system)
    $this->priority_charge = $this->base_price;
    
    // 5. Calculate individual service fees
    $signatureFee = 0;
    $temperatureFee = 0;
    $fragileFee = 0;
    
    if ($this->signature_required) {
        $signatureFee = floatval(Setting::get('pricing_signature_fee', 5.00));
    }
    
    if ($this->temperature_controlled) {
        $temperatureFee = floatval(Setting::get('pricing_temperature_controlled_fee', 25.00));
    }
    
    if ($this->fragile_handling) {
        $fragileFee = floatval(Setting::get('pricing_fragile_handling_fee', 10.00));
    }
    
    $this->additional_services_fee = $signatureFee + $temperatureFee + $fragileFee;
    
    // 6. Insurance Fee - percentage of total value
    if ($this->insurance_required && $this->total_value > 0) {
        $insuranceRate = floatval(Setting::get('pricing_insurance_rate', 2));
        $this->insurance_fee = ($this->total_value * $insuranceRate) / 100;
        $this->insurance_amount = $this->total_value;
    } else {
        $this->insurance_fee = 0;
        $this->insurance_amount = 0;
    }
    
    // 7. Calculate Subtotal (before tax)
    $subtotal = $this->base_price + $this->weight_charge + $this->distance_charge + 
                $this->additional_services_fee + $this->insurance_fee;
    
    // 8. Tax - percentage of subtotal
    $taxPercentage = floatval(Setting::get('pricing_tax_percentage', 10));
    $this->tax_amount = ($subtotal * $taxPercentage) / 100;
    
    // 9. Total Amount
    $this->total_amount = $subtotal + $this->tax_amount - $this->discount_amount;
    
    return $this->total_amount;
}

// Add this new helper method to the Shipment model
private function calculateDistanceChargeFromZone()
{
    if (!$this->shipping_zone) {
        return 0;
    }
    
    $zonePricing = [
        'local' => floatval(Setting::get('pricing_zone_local', 5.00)),
        'regional' => floatval(Setting::get('pricing_zone_regional', 15.00)),
        'national' => floatval(Setting::get('pricing_zone_national', 35.00)),
        'international' => floatval(Setting::get('pricing_zone_international', 100.00)),
    ];
    
    return $zonePricing[$this->shipping_zone] ?? 0;
}

    protected function calculateBasePrice()
    {
        $basePrices = [
            'standard' => 10.00,
            'document' => 8.00,
            'freight' => 50.00,
            'bulk' => 100.00,
        ];
        return $basePrices[$this->shipment_type] ?? 10.00;
    }

    protected function calculateWeightCharge()
    {
        $ratePerLb = 0.50;
        return $this->total_weight * $ratePerLb;
    }

    protected function calculateDistanceCharge()
    {
        $distance = $this->calculateDistance();
        $ratePerMile = 0.75;
        return $distance * $ratePerMile;
    }

    protected function calculatePriorityCharge()
    {
        $charges = [
            'standard' => 0.00,
            'express' => 15.00,
            'overnight' => 35.00,
        ];
        return $charges[$this->delivery_priority] ?? 0.00;
    }

    protected function calculateInsuranceFee()
    {
        if (!$this->insurance_required) {
            return 0;
        }
        
        // 2% of insured value, minimum $5
        $fee = $this->insurance_amount * 0.02;
        return max($fee, 5.00);
    }

    protected function calculateTax()
    {
        $taxRate = 0.08; // 8% tax rate
        $subtotal = $this->base_price + $this->weight_charge + $this->distance_charge + 
                   $this->priority_charge + $this->insurance_fee + $this->additional_services_fee;
        return $subtotal * $taxRate;
    }

    public function updateStatus($newStatus, $description = null, $location = null)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->save();

        // Create tracking entry
        $this->trackingHistory()->create([
            'status' => $newStatus,
            'description' => $description ?? "Shipment status updated to {$newStatus}",
            'location' => $location,
            'branch_id' => $this->current_branch_id,
            'warehouse_id' => $this->current_warehouse_id,
            'hub_id' => $this->current_hub_id,
            'updated_by' => auth()->id(),
        ]);

        // Log activity
        $this->logActivity('status_updated', "Status changed from {$oldStatus} to {$newStatus}", [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);

        // Send notification
        $this->notifyStatusChange($newStatus);

        return $this;
    }

    public function assignToDriver($driverId, $vehicleId = null)
    {
        $this->assigned_driver_id = $driverId;
        if ($vehicleId) {
            $this->assigned_vehicle_id = $vehicleId;
        }
        $this->save();

        $this->logActivity('assigned_to_driver', "Shipment assigned to driver ID: {$driverId}");
        
        // Notify driver
        $driver = User::find($driverId);
        if ($driver) {
            Notification::create([
                'user_id' => $driverId,
                'shipment_id' => $this->id,
                'title' => 'New Shipment Assigned',
                'message' => "You have been assigned shipment #{$this->tracking_number}",
                'type' => 'shipment_update',
                'channel' => 'system',
            ]);
        }

        return $this;
    }

    public function markAsPickedUp()
    {
        if ($this->status !== 'pending') {
            throw new \Exception('Only pending shipments can be picked up');
        }

        $this->updateStatus('picked_up', 'Package picked up from sender', $this->pickup_full_address);
        $this->pickup_date = now();
        $this->save();

        return $this;
    }

    public function markAsDelivered($signature = null, $photo = null, $notes = null)
    {
        $this->status = 'delivered';
        $this->actual_delivery_date = now();
        
        if ($signature) {
            $this->delivery_signature = $signature;
        }
        
        if ($photo) {
            $this->delivery_photo = $photo;
        }
        
        if ($notes) {
            $this->delivery_notes = $notes;
        }
        
        $this->save();

        $this->trackingHistory()->create([
            'status' => 'delivered',
            'description' => 'Package successfully delivered',
            'location' => $this->delivery_full_address,
            'updated_by' => auth()->id(),
        ]);

        $this->logActivity('delivered', 'Shipment delivered successfully');
        $this->notifyStatusChange('delivered');

        return $this;
    }

    public function markAsFailed($reason)
    {
        $this->status = 'failed';
        $this->delivery_attempts += 1;
        $this->delivery_notes = $reason;
        $this->save();

        $this->updateStatus('failed', "Delivery attempt failed: {$reason}");
        
        return $this;
    }

    public function cancel($reason)
    {
        if (!$this->can_be_cancelled) {
            throw new \Exception('This shipment cannot be cancelled');
        }

        $this->status = 'cancelled';
        $this->cancellation_reason = $reason;
        $this->save();

        $this->updateStatus('cancelled', "Shipment cancelled: {$reason}");
        
        return $this;
    }

    public function addToRoute($routeId, $sequenceNumber)
    {
        return RouteShipment::create([
            'route_id' => $routeId,
            'shipment_id' => $this->id,
            'sequence_number' => $sequenceNumber,
            'status' => 'pending',
        ]);
    }

    public function logActivity($action, $description = null, $changes = [])
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => self::class,
            'model_id' => $this->id,
            'description' => $description,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected function notifyStatusChange($status)
    {
        $messages = [
            'pending' => 'Your shipment has been created and is pending pickup',
            'picked_up' => 'Your shipment has been picked up',
            'in_transit' => 'Your shipment is in transit',
            'out_for_delivery' => 'Your shipment is out for delivery',
            'delivered' => 'Your shipment has been delivered',
            'failed' => 'Delivery attempt failed',
            'cancelled' => 'Your shipment has been cancelled',
        ];

        Notification::create([
            'user_id' => $this->customer_id,
            'shipment_id' => $this->id,
            'title' => 'Shipment Update',
            'message' => $messages[$status] ?? 'Shipment status updated',
            'type' => 'shipment_update',
            'channel' => 'system',
            'action_url' => route('shipments.show', $this->id),
        ]);
    }

    // Status Check Methods
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPickedUp()
    {
        return $this->status === 'picked_up';
    }

    public function isInTransit()
    {
        return $this->status === 'in_transit';
    }

    public function isOutForDelivery()
    {
        return $this->status === 'out_for_delivery';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    // Event Handlers
    protected static function booted()
    {
        static::creating(function ($shipment) {
            if (!$shipment->tracking_number) {
                $shipment->tracking_number = self::generateTrackingNumber();
            }
        });

        static::created(function ($shipment) {
            $shipment->trackingHistory()->create([
                'status' => $shipment->status,
                'description' => 'Shipment created',
                'updated_by' => auth()->id(),
            ]);

            $shipment->logActivity('created', 'Shipment created');
        });

        static::updated(function ($shipment) {
            if ($shipment->isDirty('status')) {
                $shipment->logActivity('updated', 'Shipment updated', [
                    'old' => $shipment->getOriginal(),
                    'new' => $shipment->getAttributes(),
                ]);
            }
        });

        static::deleted(function ($shipment) {
            $shipment->logActivity('deleted', 'Shipment deleted');
        });
    }



    // Warehouse Inventory relationship
    public function warehouseInventory()
    {
        return $this->hasMany(WarehouseInventory::class);
    }

    // Current warehouse relationship
    public function currentWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'current_warehouse_id');
    }

    // Get shipments not currently in any warehouse
    public function scopeAvailableForCheckIn($query)
    {
        return $query->whereDoesntHave('warehouseInventory', function($q) {
            $q->active();
        })->whereIn('status', ['picked_up', 'in_transit', 'at_warehouse']);
    }

    // Get shipments currently in warehouse
    public function scopeInWarehouse($query)
    {
        return $query->whereHas('warehouseInventory', function($q) {
            $q->active();
        });
    }

    // Filter by status
   

    /**
     * Accessors & Helper Methods
     */

    // Check if shipment is currently in a warehouse
    public function isInWarehouse()
    {
        return $this->warehouseInventory()
            ->whereNull('checked_out_at')
            ->exists();
    }

    // Get current warehouse inventory record
    public function getCurrentInventoryRecord()
    {
        return $this->warehouseInventory()
            ->whereNull('checked_out_at')
            ->first();
    }

    // Get full sender address
    public function getFullSenderAddressAttribute()
    {
        return trim($this->sender_address);
    }

    // Get full recipient address
    public function getFullRecipientAddressAttribute()
    {
        return trim($this->recipient_address);
    }

    public function trackingUpdates()
{
    return $this->hasMany(ShipmentTracking::class)->orderBy('created_at', 'desc');
}

public function updatedBy()
{
    return $this->belongsTo(User::class, 'updated_by');
}
}



















