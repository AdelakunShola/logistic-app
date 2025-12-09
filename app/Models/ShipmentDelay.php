<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;
class ShipmentDelay extends Model
{
    use HasFactory; 
use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'delayed_at' => 'datetime',
        'resolved_at' => 'datetime',
        'original_delivery_date' => 'datetime',
        'new_delivery_date' => 'datetime',
        'customer_notified' => 'boolean',
    ];

    // Relationships
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    // Accessors
    public function getSeverityAttribute(): string
    {
        if ($this->delay_hours >= 72) {
            return 'critical';
        } elseif ($this->delay_hours >= 48) {
            return 'high';
        } elseif ($this->delay_hours >= 24) {
            return 'medium';
        }
        return 'low';
    }

    public function getIsCriticalAttribute(): bool
    {
        return $this->delay_hours >= 48;
    }

    public function getIsResolvedAttribute(): bool
    {
        return !is_null($this->resolved_at);
    }

    // Scopes
    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }

    public function scopeResolved($query)
    {
        return $query->whereNotNull('resolved_at');
    }

    public function scopeCritical($query)
    {
        return $query->where('delay_hours', '>=', 48);
    }

    public function scopeBySeverity($query, $severity)
    {
        return match($severity) {
            'critical' => $query->where('delay_hours', '>=', 72),
            'high' => $query->whereBetween('delay_hours', [48, 71]),
            'medium' => $query->whereBetween('delay_hours', [24, 47]),
            'low' => $query->where('delay_hours', '<', 24),
            default => $query,
        };
    }

    public function scopeByCarrier($query, $carrierName)
    {
        return $query->whereHas('shipment', function($q) use ($carrierName) {
            $q->whereHas('carrier', function($q2) use ($carrierName) {
                $q2->where('name', $carrierName);
            });
        });
    }

    // Methods
    public function resolve(string $resolutionNotes = null): bool
    {
        $this->resolved_at = now();
        
        if ($resolutionNotes) {
            $this->delay_description = $this->delay_description 
                ? $this->delay_description . "\n\nResolution: " . $resolutionNotes
                : "Resolution: " . $resolutionNotes;
        }

        $saved = $this->save();

        if ($saved) {
            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'resolved',
                'model_type' => 'ShipmentDelay',
                'model_id' => $this->id,
                'description' => "Delay resolved for shipment {$this->shipment->tracking_number}",
                'new_values' => ['resolved_at' => $this->resolved_at],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Update shipment status if needed
            if ($this->shipment->status === 'delayed') {
                $this->shipment->updateStatus(
                    'in_transit',
                    'Delay resolved, shipment back in transit',
                    null
                );
            }
        }

        return $saved;
    }

    public function escalate(): void
    {
        // Send notification to managers/admins
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'shipment_id' => $this->shipment_id,
                'title' => 'Critical Delay - Immediate Action Required',
                'message' => "Shipment {$this->shipment->tracking_number} has been delayed for {$this->delay_hours} hours. Immediate attention required.",
                'type' => 'error',
                'channel' => 'system',
                'data' => json_encode([
                    'delay_id' => $this->id,
                    'severity' => $this->severity,
                    'delay_hours' => $this->delay_hours,
                    'reason' => $this->delay_reason,
                ]),
                'action_url' => route('admin.delayed-shipments.show', $this->id),
            ]);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'escalated',
            'model_type' => 'ShipmentDelay',
            'model_id' => $this->id,
            'description' => "Critical delay escalated for shipment {$this->shipment->tracking_number}",
            'new_values' => ['escalated_at' => now()],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function notifyCustomer(): void
    {
        if ($this->customer_notified) {
            return;
        }

        $customer = $this->shipment->customer;

        Notification::create([
            'user_id' => $customer->id,
            'shipment_id' => $this->shipment_id,
            'title' => 'Shipment Delay Notification',
            'message' => "Your shipment {$this->shipment->tracking_number} has been delayed by {$this->delay_hours} hours due to {$this->getDelayReasonText()}. New expected delivery: {$this->new_delivery_date->format('M d, Y h:i A')}",
            'type' => 'warning',
            'channel' => 'system',
            'data' => json_encode([
                'delay_id' => $this->id,
                'delay_hours' => $this->delay_hours,
                'reason' => $this->delay_reason,
            ]),
            'action_url' => route('admin.shipment.track.show', $this->shipment_id),
        ]);

        $this->customer_notified = true;
        $this->save();
    }

    private function getDelayReasonText(): string
    {
        return match($this->delay_reason) {
            'traffic' => 'traffic congestion',
            'weather' => 'weather conditions',
            'vehicle_breakdown' => 'vehicle breakdown',
            'address_issue' => 'address issue',
            'customer_unavailable' => 'customer unavailability',
            default => $this->delay_reason,
        };
    }

    public static function getReasonLabel($reason)
    {
        $labels = [
            'weather_conditions' => 'Weather Conditions',
            'traffic_congestion' => 'Traffic Congestion',
            'customer_unavailable' => 'Customer Unavailable',
            'vehicle_issues' => 'Vehicle Issues',
            'address_issues' => 'Address Issues',
            'documentation_issues' => 'Documentation Issues',
            'customs_delay' => 'Customs Delay',
            'port_congestion' => 'Port Congestion',
            'mechanical_failure' => 'Mechanical Failure',
            'road_closure' => 'Road Closure',
            'other' => 'Other',
        ];
        
        return $labels[$reason] ?? $reason;
    }
}