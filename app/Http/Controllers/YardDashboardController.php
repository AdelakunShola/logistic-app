<?php

namespace App\Http\Controllers;

use App\Models\Yard;
use App\Models\YardSlot;
use App\Models\YardVisit;
use App\Services\YardAllocationService;
use Illuminate\Http\Request;

class YardDashboardController extends Controller
{
    protected YardAllocationService $allocationService;

    public function __construct(YardAllocationService $allocationService)
    {
        $this->allocationService = $allocationService;
    }

    public function index(Yard $yard)
    {
        $yard->load(['zones.slots.currentVehicle', 'zones.slots.currentDriver', 'zones.slots.activeVisit']);

        $utilization = $this->allocationService->getYardUtilization($yard);

        // Mark overstays
        $this->allocationService->markOverstays($yard);

        $activeVisits = $yard->visits()
            ->whereNull('check_out_time')
            ->with(['slot.zone', 'driver'])
            ->orderByDesc('check_in_time')
            ->get();

        $recentActivity = $yard->visits()
            ->with(['slot', 'driver'])
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        $overstayCount = $yard->visits()->where('status', 'overstay')->whereNull('check_out_time')->count();

        $todayAppointments = $yard->appointments()
            ->today()
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['driver'])
            ->orderBy('scheduled_arrival')
            ->get();

        return view('backend.yards.dashboard', compact(
            'yard', 'utilization', 'activeVisits', 'recentActivity', 'overstayCount', 'todayAppointments'
        ));
    }

    // AJAX endpoint for auto-refresh (every 10 seconds)
    public function refresh(Yard $yard)
    {
        $yard->load(['zones.slots']);

        // Mark overstays
        $this->allocationService->markOverstays($yard);

        $utilization = $this->allocationService->getYardUtilization($yard);

        // Get slot data for rendering
        $zones = $yard->zones->map(function ($zone) {
            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'type' => $zone->type,
                'color' => $zone->color,
                'position_data' => $zone->position_data,
                'slots' => $zone->slots->map(function ($slot) {
                    $activeVisit = $slot->activeVisit;
                    return [
                        'id' => $slot->id,
                        'slot_number' => $slot->slot_number,
                        'type' => $slot->type,
                        'size' => $slot->size,
                        'status' => $slot->status,
                        'position_data' => $slot->position_data,
                        'vehicle_plate' => $activeVisit?->vehicle_plate,
                        'driver_name' => $activeVisit?->driver_name,
                        'purpose' => $activeVisit?->purpose,
                        'check_in_time' => $activeVisit?->check_in_time?->format('H:i'),
                        'is_overstay' => $activeVisit?->is_overstay ?? false,
                    ];
                }),
            ];
        });

        $activeVisits = $yard->visits()
            ->whereNull('check_out_time')
            ->with(['slot.zone', 'driver'])
            ->orderByDesc('check_in_time')
            ->get()
            ->map(function ($visit) {
                return [
                    'id' => $visit->id,
                    'driver_name' => $visit->driver_name,
                    'vehicle_plate' => $visit->vehicle_plate,
                    'purpose' => $visit->purpose,
                    'slot_number' => $visit->slot?->slot_number,
                    'zone_name' => $visit->slot?->zone?->name,
                    'check_in_time' => $visit->check_in_time->format('H:i'),
                    'check_in_iso' => $visit->check_in_time->toIso8601String(),
                    'duration_minutes' => $visit->duration_minutes,
                    'expected_duration_minutes' => $visit->expected_duration_minutes,
                    'is_overstay' => $visit->is_overstay,
                    'status' => $visit->status,
                ];
            });

        $overstayCount = $yard->visits()->where('status', 'overstay')->whereNull('check_out_time')->count();

        return response()->json([
            'utilization' => $utilization,
            'zones' => $zones,
            'active_visits' => $activeVisits,
            'overstay_count' => $overstayCount,
            'timestamp' => now()->format('H:i:s'),
        ]);
    }

    // AJAX endpoint for stats
    public function stats(Yard $yard)
    {
        $utilization = $this->allocationService->getYardUtilization($yard);

        $todayVisits = $yard->visits()->whereDate('check_in_time', today())->count();
        $todayCheckouts = $yard->visits()->whereDate('check_out_time', today())->count();
        $avgDwell = $yard->visits()
            ->whereDate('check_in_time', today())
            ->whereNotNull('actual_duration_minutes')
            ->avg('actual_duration_minutes');

        return response()->json([
            'utilization' => $utilization,
            'today_visits' => $todayVisits,
            'today_checkouts' => $todayCheckouts,
            'avg_dwell_minutes' => round($avgDwell ?? 0),
        ]);
    }
}
