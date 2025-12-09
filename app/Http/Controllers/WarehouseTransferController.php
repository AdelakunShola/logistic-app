<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarehouseTransfer;
use App\Models\Warehouse;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class WarehouseTransferController extends Controller
{
    /**
     * Display a listing of warehouse transfers
     */
    public function index(Request $request)
    {
        $query = WarehouseTransfer::with([
            'shipment', 
            'fromWarehouse', 
            'toWarehouse', 
            'driver', 
            'initiatedBy',
            'receivedBy'
        ]);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('shipment', function($sq) use ($search) {
                    $sq->where('tracking_number', 'like', "%{$search}%");
                })
                ->orWhereHas('fromWarehouse', function($wq) use ($search) {
                    $wq->where('name', 'like', "%{$search}%")
                       ->orWhere('warehouse_code', 'like', "%{$search}%");
                })
                ->orWhereHas('toWarehouse', function($wq) use ($search) {
                    $wq->where('name', 'like', "%{$search}%")
                       ->orWhere('warehouse_code', 'like', "%{$search}%");
                })
                ->orWhereHas('driver', function($dq) use ($search) {
                    $dq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Transfer Type Filter
        if ($request->filled('transfer_type') && $request->transfer_type != 'all') {
            $query->where('transfer_type', $request->transfer_type);
        }

        // From Warehouse Filter
        if ($request->filled('from_warehouse_id')) {
            $query->where('from_warehouse_id', $request->from_warehouse_id);
        }

        // To Warehouse Filter
        if ($request->filled('to_warehouse_id')) {
            $query->where('to_warehouse_id', $request->to_warehouse_id);
        }

        // Driver Filter
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        // Date Range Filter
        if ($request->filled('date_from')) {
            $query->whereDate('initiated_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('initiated_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $transfers = $query->paginate(15);

        // Get data for filters
        $warehouses = Warehouse::where('status', 'active')->get();
        $drivers = User::where('role', 'driver')
                      ->where('status', 'active')
                      ->get();

        // Statistics
        $stats = [
            'total' => WarehouseTransfer::count(),
            'pending' => WarehouseTransfer::where('status', 'pending')->count(),
            'in_transit' => WarehouseTransfer::where('status', 'in_transit')->count(),
            'completed' => WarehouseTransfer::where('status', 'completed')->count(),
            'cancelled' => WarehouseTransfer::where('status', 'cancelled')->count(),
            'today' => WarehouseTransfer::whereDate('initiated_at', today())->count(),
            'unassigned' => WarehouseTransfer::where('status', 'pending')
                                            ->whereNull('driver_id')
                                            ->count(),
        ];

        $transferTypes = ['inter_warehouse', 'delivery_dispatch', 'return'];
        $statuses = ['pending', 'in_transit', 'completed', 'cancelled'];

        return view('backend.warehouse_transfers.index', compact(
            'transfers',
            'warehouses',
            'drivers',
            'stats',
            'transferTypes',
            'statuses'
        ));
    }

    /**
     * Show the form for creating a new transfer
     */
    public function create()
    {
        $warehouses = Warehouse::where('status', 'active')->get();
        $drivers = User::where('role', 'driver')
                      ->where('status', 'active')
                      ->where('is_available', true)
                      ->get();
        
        $transferTypes = ['inter_warehouse', 'delivery_dispatch', 'return'];

        return view('backend.warehouse_transfers.create', compact(
            'warehouses',
            'drivers',
            'transferTypes'
        ));
    }

    /**
     * Store a newly created transfer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_ids' => 'required|array|min:1',
            'shipment_ids.*' => 'exists:shipments,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_type' => 'required|in:inter_warehouse,delivery_dispatch,return',
            'driver_id' => 'nullable|exists:users,id',
            'transfer_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $transfers = [];

            // Create transfer for each shipment
            foreach ($validated['shipment_ids'] as $shipmentId) {
                $shipment = Shipment::findOrFail($shipmentId);

                // Check if shipment is at the from_warehouse
                if ($shipment->current_warehouse_id != $validated['from_warehouse_id']) {
                    throw new \Exception("Shipment {$shipment->tracking_number} is not at the selected warehouse.");
                }

                $transfer = WarehouseTransfer::create([
                    'shipment_id' => $shipmentId,
                    'from_warehouse_id' => $validated['from_warehouse_id'],
                    'to_warehouse_id' => $validated['to_warehouse_id'],
                    'transfer_type' => $validated['transfer_type'],
                    'status' => 'pending',
                    'initiated_by' => Auth::id(),
                    'driver_id' => $validated['driver_id'] ?? null,
                    'initiated_at' => now(),
                    'transfer_notes' => $validated['transfer_notes'] ?? null,
                ]);

                // Update shipment status
                $shipment->update([
                    'status' => 'transfer_initiated',
                ]);

                $transfers[] = $transfer;
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => count($transfers) . ' transfer(s) initiated successfully',
                    'transfers' => $transfers
                ]);
            }

            return redirect()->route('admin.warehouse.transfers.index')
                           ->with('success', count($transfers) . ' transfer(s) initiated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', 'Error: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified transfer
     */
    public function show($id)
    {
        $transfer = WarehouseTransfer::with([
            'shipment.sender',
            'shipment.receiver',
            'fromWarehouse',
            'toWarehouse',
            'driver',
            'initiatedBy',
            'receivedBy'
        ])->findOrFail($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'transfer' => $transfer
            ]);
        }

        return view('backend.warehouse_transfers.show', compact('transfer'));
    }

    /**
     * Show the form for editing the specified transfer
     */
    public function edit($id)
    {
        $transfer = WarehouseTransfer::findOrFail($id);

        if (!$transfer->canBeEdited()) {
            return back()->with('error', 'This transfer cannot be edited');
        }

        $warehouses = Warehouse::where('status', 'active')->get();
        $drivers = User::where('role', 'driver')
                      ->where('status', 'active')
                      ->get();
        
        $transferTypes = ['inter_warehouse', 'delivery_dispatch', 'return'];

        return view('backend.warehouse_transfers.edit', compact(
            'transfer',
            'warehouses',
            'drivers',
            'transferTypes'
        ));
    }

    /**
     * Update the specified transfer
     */
    public function update(Request $request, $id)
    {
        $transfer = WarehouseTransfer::findOrFail($id);

        if (!$transfer->canBeEdited()) {
            return response()->json([
                'success' => false,
                'message' => 'This transfer cannot be edited'
            ], 422);
        }

        $validated = $request->validate([
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_type' => 'required|in:inter_warehouse,delivery_dispatch,return',
            'driver_id' => 'nullable|exists:users,id',
            'transfer_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $transfer->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transfer updated successfully',
                    'transfer' => $transfer->fresh()
                ]);
            }

            return redirect()->route('admin.warehouse.transfers.index')
                           ->with('success', 'Transfer updated successfully');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', 'Error: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Remove the specified transfer
     */
    public function destroy($id)
    {
        $transfer = WarehouseTransfer::findOrFail($id);

        if (!$transfer->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'This transfer cannot be deleted'
            ], 422);
        }

        try {
            // Revert shipment status
            if ($transfer->shipment) {
                $transfer->shipment->update([
                    'status' => 'at_warehouse',
                ]);
            }

            $transfer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transfer deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Bulk delete transfers
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:warehouse_transfers,id'
        ]);

        try {
            $transfers = WarehouseTransfer::whereIn('id', $validated['ids'])->get();

            foreach ($transfers as $transfer) {
                if ($transfer->canBeCancelled()) {
                    // Revert shipment status
                    if ($transfer->shipment) {
                        $transfer->shipment->update(['status' => 'at_warehouse']);
                    }
                    $transfer->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Selected transfers deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Assign driver to transfer
     */
    public function assignDriver(Request $request, $id)
    {
        $transfer = WarehouseTransfer::findOrFail($id);

        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        try {
            $driver = User::findOrFail($validated['driver_id']);

            // Check if driver is available
            if (!$driver->is_available || $driver->status !== 'active') {
                throw new \Exception('Selected driver is not available');
            }

            $transfer->update([
                'driver_id' => $validated['driver_id'],
                'vehicle_number' => $driver->vehicle_number,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Driver assigned successfully',
                'transfer' => $transfer->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update transfer status
     */
    public function updateStatus(Request $request, $id)
    {
        $transfer = WarehouseTransfer::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_transit,completed,cancelled',
            'reason' => 'required_if:status,cancelled|string|max:500',
        ]);

        try {
            $newStatus = $validated['status'];

            switch ($newStatus) {
                case 'in_transit':
                    if (!$transfer->canDepart()) {
                        throw new \Exception('Cannot mark as in transit. Ensure driver is assigned.');
                    }
                    $transfer->markAsDeparted();
                    break;

                case 'completed':
                    if (!$transfer->canComplete()) {
                        throw new \Exception('Cannot complete transfer. Ensure transfer has arrived.');
                    }
                    $transfer->markAsCompleted(Auth::id());
                    break;

                case 'cancelled':
                    $transfer->cancel($validated['reason'] ?? null);
                    break;

                default:
                    $transfer->update(['status' => $newStatus]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transfer status updated successfully',
                'transfer' => $transfer->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get shipments available for transfer from a warehouse
     */
    public function getWarehouseShipments($warehouseId)
    {
        try {
            $shipments = Shipment::where('current_warehouse_id', $warehouseId)
                                ->whereIn('status', ['at_warehouse', 'received'])
                                ->whereDoesntHave('warehouseTransfers', function($query) {
                                    $query->whereIn('status', ['pending', 'in_transit']);
                                })
                                ->with(['sender', 'receiver'])
                                ->get();

            return response()->json([
                'success' => true,
                'shipments' => $shipments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Generate transfer manifest
     */
    public function generateManifest($id)
    {
        $transfer = WarehouseTransfer::with([
            'shipment.sender',
            'shipment.receiver',
            'fromWarehouse',
            'toWarehouse',
            'driver',
            'initiatedBy'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('backend.warehouse_transfers.manifest', compact('transfer'));

        return $pdf->download("transfer-manifest-{$transfer->transfer_code}.pdf");
    }

    /**
     * Print transfer document
     */
    public function printTransfer($id)
    {
        $transfer = WarehouseTransfer::with([
            'shipment.sender',
            'shipment.receiver',
            'fromWarehouse',
            'toWarehouse',
            'driver',
            'initiatedBy',
            'receivedBy'
        ])->findOrFail($id);

        return view('backend.warehouse_transfers.print', compact('transfer'));
    }
}