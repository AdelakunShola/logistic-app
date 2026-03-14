<?php

namespace App\Http\Controllers;

use App\Models\YardZone;
use App\Models\YardSlot;
use App\Models\YardSlotHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YardSlotController extends Controller
{
    public function store(Request $request, YardZone $zone)
    {
        $validated = $request->validate([
            'slot_number' => 'required|string|max:20',
            'type' => 'required|in:truck_parking,dock,staging,waiting',
            'size' => 'required|in:small,medium,large,oversized',
            'position_data' => 'nullable|array',
            'features' => 'nullable|array',
        ]);

        $validated['yard_zone_id'] = $zone->id;
        $slot = YardSlot::create($validated);

        return response()->json(['success' => true, 'slot' => $slot]);
    }

    public function update(Request $request, YardSlot $slot)
    {
        $validated = $request->validate([
            'slot_number' => 'sometimes|string|max:20',
            'type' => 'sometimes|in:truck_parking,dock,staging,waiting',
            'size' => 'sometimes|in:small,medium,large,oversized',
            'status' => 'sometimes|in:available,occupied,reserved,maintenance,blocked',
            'position_data' => 'nullable|array',
            'features' => 'nullable|array',
        ]);

        $slot->update($validated);

        return response()->json(['success' => true, 'slot' => $slot]);
    }

    public function destroy(YardSlot $slot)
    {
        if ($slot->status === 'occupied') {
            return response()->json(['success' => false, 'message' => 'Cannot delete an occupied slot.'], 422);
        }

        $slot->delete();
        return response()->json(['success' => true, 'message' => 'Slot deleted.']);
    }

    public function updateStatus(Request $request, YardSlot $slot)
    {
        $request->validate(['status' => 'required|in:available,maintenance,blocked']);

        $previousStatus = $slot->status;

        if ($slot->status === 'occupied') {
            return response()->json(['success' => false, 'message' => 'Cannot change status of occupied slot. Check out the vehicle first.'], 422);
        }

        $slot->update(['status' => $request->status]);

        YardSlotHistory::create([
            'yard_slot_id' => $slot->id,
            'action' => $request->status === 'blocked' ? 'blocked' : 'released',
            'previous_status' => $previousStatus,
            'new_status' => $request->status,
            'performed_by' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Slot status updated.']);
    }
}
