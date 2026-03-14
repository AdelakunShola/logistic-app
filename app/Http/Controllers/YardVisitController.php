<?php

namespace App\Http\Controllers;

use App\Models\Yard;
use App\Models\YardVisit;
use App\Models\YardSlot;
use App\Models\YardAppointment;
use App\Services\YardAllocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class YardVisitController extends Controller
{
    protected YardAllocationService $allocationService;

    public function __construct(YardAllocationService $allocationService)
    {
        $this->allocationService = $allocationService;
    }

    // Admin: Visit history list
    public function index(Request $request, Yard $yard)
    {
        $query = $yard->visits()->with(['slot.zone', 'driver', 'vehicle']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('purpose') && $request->purpose !== 'all') {
            $query->where('purpose', $request->purpose);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('check_in_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_in_time', '<=', $request->date_to);
        }

        $visits = $query->orderByDesc('check_in_time')->paginate(20);

        $stats = [
            'active' => $yard->visits()->active()->count(),
            'today' => $yard->visits()->whereDate('check_in_time', today())->count(),
            'overstay' => $yard->visits()->where('status', 'overstay')->whereNull('check_out_time')->count(),
        ];

        return view('backend.yards.visits.index', compact('yard', 'visits', 'stats'));
    }

    // Admin: Check in a vehicle
    public function checkIn(Request $request, Yard $yard)
    {
        $validated = $request->validate([
            'driver_name' => 'required|string|max:255',
            'vehicle_plate' => 'required|string|max:50',
            'vehicle_type' => 'nullable|string|max:100',
            'purpose' => 'required|in:pickup,delivery,staging,parking,maintenance',
            'expected_duration_minutes' => 'required|integer|min:15',
            'driver_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'yard_slot_id' => 'nullable|exists:yard_slots,id',
            'confirmation_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Look up appointment by confirmation code
        $appointment = null;
        if (!empty($validated['confirmation_code'])) {
            $appointment = YardAppointment::where('confirmation_code', $validated['confirmation_code'])
                ->where('yard_id', $yard->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();
        }

        $visit = DB::transaction(function () use ($validated, $yard, $appointment) {
            $visit = YardVisit::create([
                'yard_id' => $yard->id,
                'driver_name' => $validated['driver_name'],
                'vehicle_plate' => $validated['vehicle_plate'],
                'vehicle_type' => $validated['vehicle_type'] ?? null,
                'purpose' => $validated['purpose'],
                'expected_duration_minutes' => $validated['expected_duration_minutes'],
                'driver_id' => $validated['driver_id'] ?? null,
                'vehicle_id' => $validated['vehicle_id'] ?? null,
                'shipment_id' => $validated['shipment_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'check_in_time' => now(),
                'status' => 'checked_in',
                'registered_by' => 'admin',
            ]);

            // Assign slot: appointment's reserved slot > manual slot > auto-assign
            if ($appointment && $appointment->yard_slot_id) {
                $slot = YardSlot::find($appointment->yard_slot_id);
                if ($slot && in_array($slot->status, ['reserved', 'available'])) {
                    $this->allocationService->assignSlotToVisit($slot, $visit);
                }
            } elseif (!empty($validated['yard_slot_id'])) {
                $slot = YardSlot::find($validated['yard_slot_id']);
                if ($slot && $slot->isAvailable()) {
                    $this->allocationService->assignSlotToVisit($slot, $visit);
                }
            } elseif ($yard->auto_assign_enabled) {
                $this->allocationService->allocateSlot($visit);
            }

            // Mark appointment as checked in
            if ($appointment) {
                $appointment->update(['status' => 'checked_in']);
            }

            return $visit;
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vehicle checked in successfully.',
                'visit' => $visit->load('slot'),
            ]);
        }

        return redirect()->route('admin.yards.visits.index', $yard)->with('success', 'Vehicle checked in successfully.');
    }

    // Admin: Check out a vehicle
    public function checkOut(Request $request, YardVisit $visit)
    {
        if ($visit->is_checked_out) {
            return response()->json(['success' => false, 'message' => 'Vehicle is already checked out.'], 422);
        }

        DB::transaction(function () use ($visit) {
            $visit->update([
                'check_out_time' => now(),
                'actual_duration_minutes' => $visit->check_in_time->diffInMinutes(now()),
                'status' => 'checked_out',
            ]);

            if ($visit->slot) {
                $this->allocationService->releaseSlot($visit->slot, $visit);
            }
        });

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Vehicle checked out successfully.']);
        }

        return back()->with('success', 'Vehicle checked out successfully.');
    }

    // Admin: Reassign a visit to a different slot
    public function reassignSlot(Request $request, YardVisit $visit)
    {
        $request->validate(['yard_slot_id' => 'required|exists:yard_slots,id']);

        $newSlot = YardSlot::find($request->yard_slot_id);

        if (!$newSlot->isAvailable()) {
            return response()->json(['success' => false, 'message' => 'Selected slot is not available.'], 422);
        }

        DB::transaction(function () use ($visit, $newSlot) {
            if ($visit->slot) {
                $this->allocationService->releaseSlot($visit->slot, $visit);
            }
            $this->allocationService->assignSlotToVisit($newSlot, $visit);
        });

        return response()->json(['success' => true, 'message' => 'Vehicle reassigned to slot ' . $newSlot->slot_number]);
    }

    // Driver: Self check-in form
    public function selfCheckInForm()
    {
        $yards = Yard::where('status', 'active')
            ->where('allow_self_registration', true)
            ->get();

        $driverVehicle = Auth::user()->assignedVehicle;

        return view('driver.yard.check-in', compact('yards', 'driverVehicle'));
    }

    // Driver: Self check-in
    public function selfCheckIn(Request $request)
    {
        $validated = $request->validate([
            'yard_id' => 'required|exists:yards,id',
            'vehicle_plate' => 'required|string|max:50',
            'vehicle_type' => 'nullable|string|max:100',
            'purpose' => 'required|in:pickup,delivery,staging,parking,maintenance',
            'confirmation_code' => 'nullable|string|max:20',
            'shipment_id' => 'nullable|exists:shipments,id',
        ]);

        $yard = Yard::findOrFail($validated['yard_id']);

        if (!$yard->allow_self_registration) {
            return back()->with('error', 'Self check-in is not enabled for this yard.');
        }

        // Check if appointment required and validate confirmation code
        $appointment = null;
        if ($yard->require_appointment || !empty($validated['confirmation_code'])) {
            if (empty($validated['confirmation_code'])) {
                return back()->with('error', 'This yard requires an appointment. Please enter your confirmation code.');
            }

            $appointment = YardAppointment::where('confirmation_code', $validated['confirmation_code'])
                ->where('yard_id', $yard->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            if (!$appointment) {
                return back()->with('error', 'Invalid or expired confirmation code.');
            }
        }

        $driver = Auth::user();

        $visit = DB::transaction(function () use ($validated, $yard, $driver, $appointment) {
            $vehicle = $driver->assignedVehicle;

            $visit = YardVisit::create([
                'yard_id' => $yard->id,
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle?->id,
                'driver_name' => $driver->user_name ?? ($driver->first_name . ' ' . $driver->last_name),
                'vehicle_plate' => $validated['vehicle_plate'],
                'vehicle_type' => $validated['vehicle_type'] ?? $vehicle?->vehicle_type,
                'purpose' => $validated['purpose'],
                'expected_duration_minutes' => $yard->max_stay_hours * 60,
                'shipment_id' => $validated['shipment_id'] ?? null,
                'check_in_time' => now(),
                'status' => 'checked_in',
                'registered_by' => 'self',
            ]);

            // If appointment has a reserved slot, assign that specific slot
            if ($appointment && $appointment->yard_slot_id) {
                $reservedSlot = YardSlot::find($appointment->yard_slot_id);
                if ($reservedSlot && in_array($reservedSlot->status, ['reserved', 'available'])) {
                    $this->allocationService->assignSlotToVisit($reservedSlot, $visit);
                }
            } elseif ($yard->auto_assign_enabled) {
                $this->allocationService->allocateSlot($visit);
            }

            // Mark appointment as checked in
            if ($appointment) {
                $appointment->update(['status' => 'checked_in']);
            }

            return $visit;
        });

        return redirect()->route('driver.yard.my-visit')->with('success', 'Checked in successfully! ' .
            ($visit->slot ? 'Your assigned slot: ' . $visit->slot->slot_number : 'No slot assigned yet - please wait for assignment.'));
    }

    // Driver: My current visit
    public function myVisit()
    {
        $driver = Auth::user();
        $activeVisit = YardVisit::where('driver_id', $driver->id)
            ->whereNull('check_out_time')
            ->with(['yard', 'slot.zone'])
            ->latest()
            ->first();

        return view('driver.yard.my-visit', compact('activeVisit'));
    }

    // Driver: Self check-out
    public function selfCheckOut()
    {
        $driver = Auth::user();
        $visit = YardVisit::where('driver_id', $driver->id)
            ->whereNull('check_out_time')
            ->latest()
            ->first();

        if (!$visit) {
            return redirect()->route('driver.yard.check-in')->with('error', 'No active yard visit found.');
        }

        DB::transaction(function () use ($visit) {
            $visit->update([
                'check_out_time' => now(),
                'actual_duration_minutes' => $visit->check_in_time->diffInMinutes(now()),
                'status' => 'checked_out',
            ]);

            if ($visit->slot) {
                $this->allocationService->releaseSlot($visit->slot, $visit);
            }
        });

        return redirect()->route('driver.yard.check-in')->with('success', 'Checked out successfully. Total time: ' . $visit->actual_duration_minutes . ' minutes.');
    }
}
