<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('warehouse_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('manager_name', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by state
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        $warehouses = $query->withCount([
            'inventories as current_inventory' => function ($q) {
                $q->whereNull('checked_out_at');
            },
            'shipments as active_shipments'
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Stats
        $stats = [
            'total' => Warehouse::count(),
            'active' => Warehouse::where('status', 'active')->count(),
            'inactive' => Warehouse::where('status', 'inactive')->count(),
            'maintenance' => Warehouse::where('status', 'maintenance')->count(),
            'total_capacity' => Warehouse::sum('storage_capacity'),
            'total_occupancy' => Warehouse::sum('current_occupancy'),
            'avg_utilization' => Warehouse::avg('utilization_percentage') ?? 0,
            'near_capacity' => Warehouse::whereRaw('(current_occupancy / storage_capacity) >= 0.8')->count(),
        ];

        // Get unique cities and states for filters
        $cities = Warehouse::select('city')->distinct()->pluck('city');
        $states = Warehouse::select('state')->distinct()->pluck('state');

        // Warehouse types and statuses
        $types = ['main', 'regional', 'distribution', 'sortation'];
        $statuses = ['active', 'inactive', 'maintenance', 'closed'];

        return view('backend.warehouses.index', compact(
            'warehouses',
            'stats',
            'cities',
            'states',
            'types',
            'statuses'
        ));
    }

    public function create()
    {
        $types = ['main', 'regional', 'distribution', 'sortation'];
        $statuses = ['active', 'inactive', 'maintenance', 'closed'];
        $safetyRatings = ['A', 'B', 'C', 'D', 'F'];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('backend.warehouses.create', compact('types', 'statuses', 'safetyRatings', 'days'));
    }

   public function store(Request $request)
{
    try {
        $data = $request->all();

        // Handle checkboxes
        $data['is_pickup_point'] = $request->has('is_pickup_point');
        $data['is_delivery_point'] = $request->has('is_delivery_point');
        $data['accepts_cod'] = $request->has('accepts_cod');
        $data['has_cold_storage'] = $request->has('has_cold_storage');
        $data['has_24h_security'] = $request->has('has_24h_security');

        // Handle operating days
        if ($request->has('operating_days')) {
            $data['operating_days'] = $request->operating_days;
        }

        $warehouse = Warehouse::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Warehouse created successfully',
                'warehouse' => $warehouse
            ]);
        }

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse created successfully');

    } catch (\Exception $e) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating warehouse: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Error creating warehouse: ' . $e->getMessage())
            ->withInput();
    }
}


    public function show($id)
    {
        $warehouse = Warehouse::with([
            'inventories' => function ($q) {
                $q->whereNull('checked_out_at')->latest();
            },
            'shipments' => function ($q) {
                $q->latest()->limit(10);
            },
            'staff'
        ])->findOrFail($id);

        // Get transfer statistics
        $transferStats = [
            'incoming' => $warehouse->transfersTo()->where('status', 'in_transit')->count(),
            'outgoing' => $warehouse->transfersFrom()->where('status', 'in_transit')->count(),
            'completed_today' => $warehouse->transfersTo()
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
        ];

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'warehouse' => $warehouse,
                'transfer_stats' => $transferStats
            ]);
        }

        return view('backend.warehouses.show', compact('warehouse', 'transferStats'));
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $types = ['main', 'regional', 'distribution', 'sortation'];
        $statuses = ['active', 'inactive', 'maintenance', 'closed'];
        $safetyRatings = ['A', 'B', 'C', 'D', 'F'];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('backend.warehouses.edit', compact('warehouse', 'types', 'statuses', 'safetyRatings', 'days'));
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'warehouse_code' => 'required|string|unique:warehouses,warehouse_code,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:main,regional,distribution,sortation',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'storage_capacity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,maintenance,closed',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->all();

            // Handle checkboxes
            $data['is_pickup_point'] = $request->has('is_pickup_point');
            $data['is_delivery_point'] = $request->has('is_delivery_point');
            $data['accepts_cod'] = $request->has('accepts_cod');
            $data['has_cold_storage'] = $request->has('has_cold_storage');
            $data['has_24h_security'] = $request->has('has_24h_security');

            // Handle operating days
            if ($request->has('operating_days')) {
                $data['operating_days'] = $request->operating_days;
            }

            $warehouse->update($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Warehouse updated successfully',
                    'warehouse' => $warehouse
                ]);
            }

            return redirect()->route('admin.warehouses.index')
                ->with('success', 'Warehouse updated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating warehouse: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error updating warehouse: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);

            // Check if warehouse has active inventory
            $hasActiveInventory = $warehouse->inventories()->whereNull('checked_out_at')->exists();
            if ($hasActiveInventory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete warehouse with active inventory'
                ], 422);
            }

            $warehouse->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Warehouse deleted successfully'
                ]);
            }

            return redirect()->route('admin.warehouses.index')
                ->with('success', 'Warehouse deleted successfully');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting warehouse: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error deleting warehouse: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            // Check for active inventory in any warehouse
            $hasActiveInventory = Warehouse::whereIn('id', $ids)
                ->whereHas('inventories', function ($q) {
                    $q->whereNull('checked_out_at');
                })
                ->exists();

            if ($hasActiveInventory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete warehouses with active inventory'
                ], 422);
            }

            Warehouse::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' warehouse(s) deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting warehouses: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);
            $warehouse->status = $request->status;
            $warehouse->save();

            return response()->json([
                'success' => true,
                'message' => 'Warehouse status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export($format)
    {
        // Implementation for CSV/Excel/PDF export
        // You can use packages like maatwebsite/excel for this
        return response()->json([
            'success' => false,
            'message' => 'Export functionality to be implemented'
        ]);
    }
}