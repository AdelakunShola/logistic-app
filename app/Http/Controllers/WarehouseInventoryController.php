<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarehouseInventory;
use App\Models\Warehouse;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseInventoryController extends Controller
{
    /**
     * Display a listing of the inventory
     */
    public function index(Request $request)
    {
        $query = WarehouseInventory::with(['warehouse', 'shipment', 'checkedInBy', 'checkedOutBy']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('shipment', function($sq) use ($search) {
                    $sq->where('tracking_number', 'like', "%{$search}%")
                       ->orWhere('sender_name', 'like', "%{$search}%")
                       ->orWhere('recipient_name', 'like', "%{$search}%");
                })
                ->orWhereHas('warehouse', function($wq) use ($search) {
                    $wq->where('name', 'like', "%{$search}%")
                       ->orWhere('warehouse_code', 'like', "%{$search}%");
                })
                ->orWhere('storage_location', 'like', "%{$search}%")
                ->orWhere('bin_number', 'like', "%{$search}%");
            });
        }

        // Filter by warehouse
        if ($request->filled('warehouse') && $request->warehouse !== 'all') {
            $query->where('warehouse_id', $request->warehouse);
        }

        // Filter by status (in storage / checked out)
        if ($request->filled('status')) {
            if ($request->status === 'in_storage') {
                $query->active();
            } elseif ($request->status === 'checked_out') {
                $query->checkedOut();
            }
        }

        // Filter by condition
        if ($request->filled('condition') && $request->condition !== 'all') {
            $query->where('package_condition', $request->condition);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            if ($request->priority === 'priority') {
                $query->priority();
            } elseif ($request->priority === 'special_handling') {
                $query->specialHandling();
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'checked_in_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $inventories = $query->paginate(20)->withQueryString();

        // Get statistics
        $stats = [
            'total_items' => WarehouseInventory::active()->count(),
            'priority_items' => WarehouseInventory::active()->priority()->count(),
            'damaged_items' => WarehouseInventory::active()->damaged()->count(),
            'special_handling' => WarehouseInventory::active()->specialHandling()->count(),
            'overdue_items' => WarehouseInventory::active()
                ->where('checked_in_at', '<=', now()->subDays(7))
                ->count(),
        ];

        // Get filter options
        $warehouses = Warehouse::where('status', 'active')->orderBy('name')->get();
        $conditions = ['good', 'damaged', 'requires_attention'];

        return view('backend.warehouse-inventory.index', compact(
            'inventories',
            'stats',
            'warehouses',
            'conditions'
        ));
    }

    /**
     * Show the form for creating a new inventory entry (Check-in)
     */
  public function create()
{
    $warehouses = Warehouse::where('status', 'active')->orderBy('name')->get();
    
    $availableShipments = Shipment::availableForCheckIn()
        ->orderBy('created_at', 'desc')
        ->get();

    return view('backend.warehouse-inventory.create', compact('warehouses', 'availableShipments'));
}

    /**
     * Store a newly created inventory entry (Check-in)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'shipment_id' => 'required|exists:shipments,id',
            'storage_location' => 'nullable|string|max:255',
            'bin_number' => 'nullable|string|max:255',
            'package_condition' => 'required|in:good,damaged,requires_attention',
            'is_priority' => 'boolean',
            'requires_special_handling' => 'boolean',
            'handling_notes' => 'nullable|string',
        ]);

        // Check if warehouse has capacity
        $warehouse = Warehouse::findOrFail($validated['warehouse_id']);
        if ($warehouse->current_occupancy >= $warehouse->storage_capacity) {
            return back()->with('error', 'Warehouse is at full capacity!')->withInput();
        }

        // Check if shipment is already in a warehouse
        $existingInventory = WarehouseInventory::where('shipment_id', $validated['shipment_id'])
            ->active()
            ->first();
        
        if ($existingInventory) {
            return back()->with('error', 'This shipment is already in a warehouse!')->withInput();
        }

        $validated['checked_in_at'] = now();
        $validated['checked_in_by'] = Auth::id();
        $validated['is_priority'] = $request->has('is_priority');
        $validated['requires_special_handling'] = $request->has('requires_special_handling');

        DB::beginTransaction();
        try {
            $inventory = WarehouseInventory::create($validated);

            // Update shipment status and current warehouse
            $shipment = Shipment::findOrFail($validated['shipment_id']);
            $shipment->update([
                'current_warehouse_id' => $validated['warehouse_id'],
                'status' => 'at_warehouse',
            ]);

            DB::commit();

            return redirect()->route('admin.warehouse-inventory.index')
                ->with('success', 'Package checked in successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error checking in package: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified inventory entry
     */
    public function show(WarehouseInventory $inventory)
    {
        $inventory->load(['warehouse', 'shipment', 'checkedInBy', 'checkedOutBy']);
        
        return view('backend.warehouse-inventory.show', compact('inventory'));
    }

    /**
     * Update the specified inventory entry
     */
    public function update(Request $request, WarehouseInventory $inventory)
    {
        $validated = $request->validate([
            'storage_location' => 'nullable|string|max:255',
            'bin_number' => 'nullable|string|max:255',
            'package_condition' => 'required|in:good,damaged,requires_attention',
            'is_priority' => 'boolean',
            'requires_special_handling' => 'boolean',
            'handling_notes' => 'nullable|string',
        ]);

        $validated['is_priority'] = $request->has('is_priority');
        $validated['requires_special_handling'] = $request->has('requires_special_handling');

        $inventory->update($validated);

        return back()->with('success', 'Inventory updated successfully!');
    }

    /**
     * Remove the specified inventory entry
     */
    public function destroy(WarehouseInventory $inventory)
    {
        if ($inventory->checked_out_at) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete checked-out inventory!'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Update shipment status
            if ($inventory->shipment) {
                $inventory->shipment->update([
                    'current_warehouse_id' => null,
                ]);
            }

            $inventory->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory entry deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check-in a package
     */
    public function checkIn(Request $request)
    {
        return $this->store($request);
    }

    /**
     * Check-out a package
     */
    public function checkOut(Request $request, WarehouseInventory $inventorsy)
    {
        if ($inventorsy->checked_out_at) {
            return back()->with('error', 'Package already checked out!');
        }

        DB::beginTransaction();
        try {
            $inventorsy->update([
                'checked_out_at' => now(),
                'checked_out_by' => Auth::id(),
            ]);

            // Update shipment status
            if ($inventorsy->shipment) {
                $inventorsy->shipment->update([
                    'status' => 'out_for_delivery',
                    'current_warehouse_id' => null,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Package checked out successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error checking out package: ' . $e->getMessage());
        }
    }

    /**
     * Get inventory by warehouse
     */
    public function byWarehouse(Warehouse $warehouse)
    {
        $inventories = WarehouseInventory::with(['shipment', 'checkedInBy', 'checkedOutBy'])
            ->where('warehouse_id', $warehouse->id)
            ->active()
            ->orderBy('checked_in_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_items' => $inventories->total(),
            'priority_items' => WarehouseInventory::where('warehouse_id', $warehouse->id)
                ->active()->priority()->count(),
            'damaged_items' => WarehouseInventory::where('warehouse_id', $warehouse->id)
                ->active()->damaged()->count(),
        ];

        return view('admin.warehouse-inventory.by-warehouse', compact('warehouse', 'inventories', 'stats'));
    }

    /**
     * Export inventory data
     */
    public function export($format)
    {
        $inventories = WarehouseInventory::with(['warehouse', 'shipment', 'checkedInBy', 'checkedOutBy'])
            ->get();

        if ($format === 'csv') {
            return $this->exportCsv($inventories);
        }

        return back()->with('error', 'Export format not supported');
    }

    /**
     * Export to CSV
     */
    private function exportCsv($inventories)
    {
        $filename = 'warehouse_inventory_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($inventories) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID',
                'Warehouse',
                'Tracking Number',
                'Storage Location',
                'Bin Number',
                'Condition',
                'Priority',
                'Special Handling',
                'Checked In At',
                'Checked In By',
                'Checked Out At',
                'Checked Out By',
                'Storage Duration (hours)',
            ]);

            // Data
            foreach ($inventories as $inventory) {
                fputcsv($file, [
                    $inventory->id,
                    $inventory->warehouse->name ?? 'N/A',
                    $inventory->shipment->tracking_number ?? 'N/A',
                    $inventory->storage_location ?? 'N/A',
                    $inventory->bin_number ?? 'N/A',
                    ucfirst($inventory->package_condition),
                    $inventory->is_priority ? 'Yes' : 'No',
                    $inventory->requires_special_handling ? 'Yes' : 'No',
                    $inventory->checked_in_at->format('Y-m-d H:i:s'),
                    $inventory->checkedInBy->name ?? 'N/A',
                    $inventory->checked_out_at ? $inventory->checked_out_at->format('Y-m-d H:i:s') : 'Still in storage',
                    $inventory->checkedOutBy->name ?? 'N/A',
                    $inventory->storage_duration,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}