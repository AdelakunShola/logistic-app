<?php

namespace App\Http\Controllers;

use App\Models\Yard;
use App\Models\YardAppointment;
use App\Models\YardSlot;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\YardAllocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YardAppointmentController extends Controller
{
    // Admin: List appointments
    public function index(Request $request, Yard $yard)
    {
        $query = $yard->appointments()->with(['driver', 'vehicle', 'slot', 'createdBy']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_arrival', $request->date);
        }

        $appointments = $query->orderBy('scheduled_arrival')->paginate(20);

        $stats = [
            'total' => $yard->appointments()->count(),
            'today' => $yard->appointments()->today()->count(),
            'pending' => $yard->appointments()->pending()->count(),
            'confirmed' => $yard->appointments()->confirmed()->count(),
        ];

        $drivers = User::where('role', 'driver')->where('status', 'active')->get();
        $vehicles = Vehicle::where('status', 'available')->get();

        return view('backend.yards.appointments.index', compact('yard', 'appointments', 'stats', 'drivers', 'vehicles'));
    }

    // Admin: Create appointment
    public function store(Request $request, Yard $yard)
    {
        $validated = $request->validate([
            'driver_name' => 'required|string|max:255',
            'vehicle_plate' => 'required|string|max:50',
            'vehicle_type' => 'nullable|string|max:100',
            'purpose' => 'required|in:pickup,delivery,staging,parking,maintenance',
            'scheduled_arrival' => 'required|date|after:now',
            'scheduled_departure' => 'required|date|after:scheduled_arrival',
            'estimated_duration_minutes' => 'nullable|integer|min:15',
            'driver_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'yard_slot_id' => 'nullable|exists:yard_slots,id',
            'notes' => 'nullable|string',
        ]);

        $validated['yard_id'] = $yard->id;
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        if (empty($validated['estimated_duration_minutes'])) {
            $arrival = \Carbon\Carbon::parse($validated['scheduled_arrival']);
            $departure = \Carbon\Carbon::parse($validated['scheduled_departure']);
            $validated['estimated_duration_minutes'] = $arrival->diffInMinutes($departure);
        }

        $appointment = YardAppointment::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment created. Confirmation code: ' . $appointment->confirmation_code,
                'appointment' => $appointment,
            ]);
        }

        return redirect()->route('admin.yards.appointments.index', $yard)
            ->with('success', 'Appointment created. Confirmation code: ' . $appointment->confirmation_code);
    }

    // Admin: Update appointment
    public function update(Request $request, YardAppointment $appointment)
    {
        $validated = $request->validate([
            'driver_name' => 'sometimes|string|max:255',
            'vehicle_plate' => 'sometimes|string|max:50',
            'vehicle_type' => 'nullable|string|max:100',
            'purpose' => 'sometimes|in:pickup,delivery,staging,parking,maintenance',
            'scheduled_arrival' => 'sometimes|date',
            'scheduled_departure' => 'sometimes|date',
            'estimated_duration_minutes' => 'nullable|integer|min:15',
            'yard_slot_id' => 'nullable|exists:yard_slots,id',
            'notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,confirmed,cancelled',
        ]);

        $appointment->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'appointment' => $appointment]);
        }

        return back()->with('success', 'Appointment updated.');
    }

    // Admin: Delete appointment
    public function destroy(YardAppointment $appointment)
    {
        $yardId = $appointment->yard_id;
        $appointment->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Appointment deleted.']);
        }

        return redirect()->route('admin.yards.appointments.index', $yardId)->with('success', 'Appointment deleted.');
    }

    // Admin: Confirm appointment (and optionally reserve a slot)
    public function confirm(Request $request, YardAppointment $appointment)
    {
        $slotId = $request->input('yard_slot_id');

        // If a specific slot is chosen, reserve it
        if ($slotId) {
            $slot = YardSlot::find($slotId);
            if ($slot && $slot->isAvailable()) {
                $allocationService = app(YardAllocationService::class);
                $allocationService->reserveSlot($slot);
                $appointment->update([
                    'status' => 'confirmed',
                    'yard_slot_id' => $slot->id,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected slot is not available.',
                ], 422);
            }
        } else {
            $appointment->update(['status' => 'confirmed']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Appointment confirmed. Code: ' . $appointment->confirmation_code
                . ($slotId ? ' | Reserved slot: ' . ($slot->slot_number ?? '') : ''),
        ]);
    }

    // Driver: My appointments
    public function driverAppointments()
    {
        $driver = Auth::user();
        $appointments = YardAppointment::where('driver_id', $driver->id)
            ->with(['yard', 'slot'])
            ->orderByDesc('scheduled_arrival')
            ->paginate(15);

        return view('driver.yard.appointments', compact('appointments'));
    }
}
