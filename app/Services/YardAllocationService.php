<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Yard;
use App\Models\YardSlot;
use App\Models\YardVisit;
use App\Models\YardSlotHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class YardAllocationService
{
    /**
     * Auto-assign the best available slot for a visit.
     */
    public function allocateSlot(YardVisit $visit): ?YardSlot
    {
        $yard = $visit->yard;

        // Determine preferred slot type based on purpose
        $preferredType = match ($visit->purpose) {
            'pickup', 'delivery' => 'dock',
            'staging' => 'staging',
            'maintenance' => 'waiting',
            default => 'truck_parking',
        };

        // Try preferred type first, then fall back to any available
        $slot = $this->findAvailableSlot($yard, $preferredType);

        if (!$slot) {
            $slot = $this->findAvailableSlot($yard);
        }

        if ($slot) {
            $this->assignSlotToVisit($slot, $visit);
        }

        return $slot;
    }

    /**
     * Find the best available slot in a yard.
     */
    protected function findAvailableSlot(Yard $yard, ?string $preferredType = null): ?YardSlot
    {
        $query = YardSlot::whereHas('zone', function ($q) use ($yard) {
            $q->where('yard_id', $yard->id)->where('yard_zones.status', 'active');
        })->where('yard_slots.status', 'available');

        if ($preferredType) {
            $query->where('yard_slots.type', $preferredType);
        }

        // Sort by zone priority (higher priority first), then slot number
        return $query->join('yard_zones', 'yard_slots.yard_zone_id', '=', 'yard_zones.id')
            ->orderByDesc('yard_zones.priority')
            ->orderBy('yard_slots.slot_number')
            ->select('yard_slots.*')
            ->first();
    }

    /**
     * Assign a slot to a visit.
     */
    public function assignSlotToVisit(YardSlot $slot, YardVisit $visit): void
    {
        DB::transaction(function () use ($slot, $visit) {
            $previousStatus = $slot->status;

            $slot->update([
                'status' => 'occupied',
                'current_vehicle_id' => $visit->vehicle_id,
                'current_driver_id' => $visit->driver_id,
            ]);

            $visit->update(['yard_slot_id' => $slot->id]);

            YardSlotHistory::create([
                'yard_slot_id' => $slot->id,
                'yard_visit_id' => $visit->id,
                'action' => 'assigned',
                'previous_status' => $previousStatus,
                'new_status' => 'occupied',
                'performed_by' => Auth::id(),
            ]);
        });
    }

    /**
     * Release a slot when a vehicle checks out.
     */
    public function releaseSlot(YardSlot $slot, ?YardVisit $visit = null): void
    {
        DB::transaction(function () use ($slot, $visit) {
            $previousStatus = $slot->status;

            $slot->update([
                'status' => 'available',
                'current_vehicle_id' => null,
                'current_driver_id' => null,
            ]);

            YardSlotHistory::create([
                'yard_slot_id' => $slot->id,
                'yard_visit_id' => $visit?->id,
                'action' => 'released',
                'previous_status' => $previousStatus,
                'new_status' => 'available',
                'performed_by' => Auth::id(),
            ]);
        });
    }

    /**
     * Reserve a slot for an upcoming appointment.
     */
    public function reserveSlot(YardSlot $slot): void
    {
        DB::transaction(function () use ($slot) {
            $previousStatus = $slot->status;

            $slot->update(['status' => 'reserved']);

            YardSlotHistory::create([
                'yard_slot_id' => $slot->id,
                'action' => 'reserved',
                'previous_status' => $previousStatus,
                'new_status' => 'reserved',
                'performed_by' => Auth::id(),
            ]);
        });
    }

    /**
     * Calculate yard utilization stats.
     */
    public function getYardUtilization(Yard $yard): array
    {
        $slots = $yard->slots;
        $total = $slots->count();
        $occupied = $slots->where('status', 'occupied')->count();
        $reserved = $slots->where('status', 'reserved')->count();
        $available = $slots->where('status', 'available')->count();
        $maintenance = $slots->where('status', 'maintenance')->count();
        $blocked = $slots->where('status', 'blocked')->count();

        return [
            'total' => $total,
            'occupied' => $occupied,
            'reserved' => $reserved,
            'available' => $available,
            'maintenance' => $maintenance,
            'blocked' => $blocked,
            'utilization_percentage' => $total > 0 ? round((($occupied + $reserved) / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Find visits that have exceeded their expected duration.
     */
    public function checkOverstays(Yard $yard): \Illuminate\Database\Eloquent\Collection
    {
        return $yard->visits()
            ->whereNull('check_out_time')
            ->where('status', '!=', 'overstay')
            ->get()
            ->filter(function ($visit) {
                return $visit->is_overstay;
            });
    }

    /**
     * Mark overstaying visits and notify admin + driver.
     */
    public function markOverstays(Yard $yard): int
    {
        $overstays = $this->checkOverstays($yard);

        foreach ($overstays as $visit) {
            $visit->update(['status' => 'overstay']);

            $overstayMins = $visit->overstay_minutes;
            $slotLabel = $visit->slot ? 'Slot ' . $visit->slot->slot_number : 'No slot';

            // Notify the driver
            if ($visit->driver_id) {
                Notification::notifyUser(
                    $visit->driver_id,
                    'Overstay Alert',
                    "Your vehicle ({$visit->vehicle_plate}) has exceeded the allowed stay at {$yard->name} ({$slotLabel}) by {$overstayMins} minutes. Please check out as soon as possible.",
                    'warning'
                );
            }

            // Notify the yard manager
            if ($yard->manager_id) {
                Notification::notifyUser(
                    $yard->manager_id,
                    'Overstay Alert - ' . $visit->vehicle_plate,
                    "Vehicle {$visit->vehicle_plate} (Driver: {$visit->driver_name}) is overstaying at {$yard->name} ({$slotLabel}) by {$overstayMins} minutes.",
                    'warning'
                );
            }
        }

        return $overstays->count();
    }
}
