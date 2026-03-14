<?php

namespace App\Http\Controllers;

use App\Models\Yard;
use App\Models\YardZone;
use App\Models\YardSlot;
use App\Models\Branch;
use App\Models\Hub;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YardController extends Controller
{
    public function index(Request $request)
    {
        $query = Yard::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('yard_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $yards = $query->withCount([
            'zones',
            'visits as active_visits_count' => function ($q) {
                $q->whereNull('check_out_time');
            },
        ])->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Yard::count(),
            'active' => Yard::where('status', 'active')->count(),
            'inactive' => Yard::where('status', 'inactive')->count(),
            'maintenance' => Yard::where('status', 'maintenance')->count(),
        ];

        return view('backend.yards.index', compact('yards', 'stats'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('status', 'active')->get();
        $hubs = Hub::where('status', 'active')->get();
        $branches = Branch::where('status', 'active')->get();
        $managers = User::where('role', 'admin')->where('status', 'active')->get();

        return view('backend.yards.create', compact('warehouses', 'hubs', 'branches', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'yard_code' => 'required|string|max:50|unique:yards',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'total_capacity' => 'required|integer|min:1',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'hub_id' => 'nullable|exists:hubs,id',
            'branch_id' => 'nullable|exists:branches,id',
            'manager_id' => 'nullable|exists:users,id',
            'operating_hours_start' => 'nullable|date_format:H:i',
            'operating_hours_end' => 'nullable|date_format:H:i',
            'max_stay_hours' => 'nullable|integer|min:1',
            'overstay_alert_minutes' => 'nullable|integer|min:1',
            'auto_assign_enabled' => 'nullable|boolean',
            'allow_self_registration' => 'nullable|boolean',
            'require_appointment' => 'nullable|boolean',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['auto_assign_enabled'] = $request->has('auto_assign_enabled');
        $validated['allow_self_registration'] = $request->has('allow_self_registration');
        $validated['require_appointment'] = $request->has('require_appointment');

        $yard = Yard::create($validated);

        return redirect()->route('admin.yards.show', $yard)->with('success', 'Yard created successfully.');
    }

    public function show(Yard $yard)
    {
        $yard->load(['zones.slots', 'manager', 'warehouse', 'hub', 'branch']);

        $stats = [
            'total_zones' => $yard->zones->count(),
            'total_slots' => $yard->slots()->count(),
            'occupied_slots' => $yard->slots()->where('yard_slots.status', 'occupied')->count(),
            'available_slots' => $yard->slots()->where('yard_slots.status', 'available')->count(),
            'reserved_slots' => $yard->slots()->where('yard_slots.status', 'reserved')->count(),
            'active_visits' => $yard->visits()->whereNull('check_out_time')->count(),
            'today_appointments' => $yard->appointments()->whereDate('scheduled_arrival', today())->count(),
        ];

        $stats['utilization'] = $stats['total_slots'] > 0
            ? round((($stats['occupied_slots'] + $stats['reserved_slots']) / $stats['total_slots']) * 100, 1)
            : 0;

        $recentVisits = $yard->visits()->with(['slot', 'driver'])->latest()->limit(10)->get();
        $upcomingAppointments = $yard->appointments()->upcoming()->with(['driver'])->limit(5)->get();

        return view('backend.yards.show', compact('yard', 'stats', 'recentVisits', 'upcomingAppointments'));
    }

    public function edit(Yard $yard)
    {
        $warehouses = Warehouse::where('status', 'active')->get();
        $hubs = Hub::where('status', 'active')->get();
        $branches = Branch::where('status', 'active')->get();
        $managers = User::where('role', 'admin')->where('status', 'active')->get();

        return view('backend.yards.edit', compact('yard', 'warehouses', 'hubs', 'branches', 'managers'));
    }

    public function update(Request $request, Yard $yard)
    {
        $validated = $request->validate([
            'yard_code' => 'required|string|max:50|unique:yards,yard_code,' . $yard->id,
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'total_capacity' => 'required|integer|min:1',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'hub_id' => 'nullable|exists:hubs,id',
            'branch_id' => 'nullable|exists:branches,id',
            'manager_id' => 'nullable|exists:users,id',
            'operating_hours_start' => 'nullable|date_format:H:i',
            'operating_hours_end' => 'nullable|date_format:H:i',
            'max_stay_hours' => 'nullable|integer|min:1',
            'overstay_alert_minutes' => 'nullable|integer|min:1',
            'auto_assign_enabled' => 'nullable|boolean',
            'allow_self_registration' => 'nullable|boolean',
            'require_appointment' => 'nullable|boolean',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['auto_assign_enabled'] = $request->has('auto_assign_enabled');
        $validated['allow_self_registration'] = $request->has('allow_self_registration');
        $validated['require_appointment'] = $request->has('require_appointment');

        $yard->update($validated);

        return redirect()->route('admin.yards.show', $yard)->with('success', 'Yard updated successfully.');
    }

    public function destroy(Yard $yard)
    {
        $yard->delete();
        return redirect()->route('admin.yards.index')->with('success', 'Yard deleted successfully.');
    }

    public function updateStatus(Request $request, Yard $yard)
    {
        $request->validate(['status' => 'required|in:active,inactive,maintenance']);
        $yard->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Yard status updated.']);
    }

    public function designer(Yard $yard)
    {
        $yard->load(['zones.slots']);

        $designerZones = $yard->zones->map(function ($zone) {
            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'type' => $zone->type,
                'capacity' => $zone->capacity,
                'color' => $zone->color,
                'priority' => $zone->priority,
                'status' => $zone->status,
                'position_data' => $zone->position_data,
                'slots' => $zone->slots->map(function ($slot) {
                    return [
                        'id' => $slot->id,
                        'slot_number' => $slot->slot_number,
                        'type' => $slot->type,
                        'size' => $slot->size,
                        'status' => $slot->status,
                        'position_data' => $slot->position_data,
                        'features' => $slot->features,
                        'yard_zone_id' => $slot->yard_zone_id,
                    ];
                }),
            ];
        });

        return view('backend.yards.designer', compact('yard', 'designerZones'));
    }

    public function saveLayout(Request $request, Yard $yard)
    {
        $request->validate([
            'yard_layout' => 'required|array',
            'zones' => 'nullable|array',
            'slots' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $yard) {
            $yard->update(['yard_layout' => $request->yard_layout]);

            // Sync zones — track new zone IDs by their index
            $zoneIdByIndex = [];
            if ($request->has('zones')) {
                foreach ($request->zones as $index => $zoneData) {
                    unset($zoneData['_zone_index']);
                    if (!empty($zoneData['id'])) {
                        $zone = YardZone::find($zoneData['id']);
                        if ($zone) {
                            $zone->update($zoneData);
                            $zoneIdByIndex[$index] = $zone->id;
                        }
                    } else {
                        unset($zoneData['id']);
                        $zoneData['yard_id'] = $yard->id;
                        $zone = YardZone::create($zoneData);
                        $zoneIdByIndex[$index] = $zone->id;
                    }
                }
            }

            // Sync slots — resolve yard_zone_id from _zone_index for new slots
            if ($request->has('slots')) {
                foreach ($request->slots as $slotData) {
                    $zoneIndex = $slotData['_zone_index'] ?? null;
                    unset($slotData['_zone_index']);

                    // Resolve yard_zone_id from zone index if not already set
                    if (empty($slotData['yard_zone_id']) && $zoneIndex !== null && isset($zoneIdByIndex[$zoneIndex])) {
                        $slotData['yard_zone_id'] = $zoneIdByIndex[$zoneIndex];
                    }

                    if (!empty($slotData['id'])) {
                        $slot = YardSlot::find($slotData['id']);
                        if ($slot) {
                            // Don't overwrite runtime statuses (occupied/reserved) from the designer
                            if (in_array($slot->status, ['occupied', 'reserved'])) {
                                unset($slotData['status']);
                            }
                            // Never overwrite vehicle/driver assignments from designer
                            unset($slotData['current_vehicle_id'], $slotData['current_driver_id']);
                            $slot->update($slotData);
                        }
                    } else {
                        unset($slotData['id']);
                        YardSlot::create($slotData);
                    }
                }
            }
        });

        return response()->json(['success' => true, 'message' => 'Yard layout saved.']);
    }
}
