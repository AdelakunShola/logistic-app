<?php

namespace App\Http\Controllers;

use App\Models\Yard;
use App\Models\YardZone;
use Illuminate\Http\Request;

class YardZoneController extends Controller
{
    public function store(Request $request, Yard $yard)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:parking,loading_dock,staging,waiting,maintenance',
            'capacity' => 'required|integer|min:1',
            'position_data' => 'nullable|array',
            'color' => 'nullable|string|max:7',
            'priority' => 'nullable|integer',
        ]);

        $validated['yard_id'] = $yard->id;
        $zone = YardZone::create($validated);

        return response()->json(['success' => true, 'zone' => $zone]);
    }

    public function update(Request $request, YardZone $zone)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:parking,loading_dock,staging,waiting,maintenance',
            'capacity' => 'sometimes|integer|min:1',
            'position_data' => 'nullable|array',
            'color' => 'nullable|string|max:7',
            'priority' => 'nullable|integer',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $zone->update($validated);

        return response()->json(['success' => true, 'zone' => $zone]);
    }

    public function destroy(YardZone $zone)
    {
        $zone->delete();
        return response()->json(['success' => true, 'message' => 'Zone deleted.']);
    }
}
