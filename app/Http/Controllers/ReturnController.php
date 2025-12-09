<?php

namespace App\Http\Controllers;

use App\Models\ReturnModel;
use App\Models\Shipment;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReturnController extends Controller
{
    /**
     * Display returns list
     */
    public function index(Request $request)
    {
        $query = ReturnModel::with(['customer', 'shipment'])
            ->orderBy('request_date', 'desc');

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Paginate results
        $returns = $query->paginate(20)->appends($request->except('page'));

        // Calculate statistics
        $stats = $this->calculateStats();

        return view('backend.orders.returns', compact('returns', 'stats'));
    }

    private function calculateStats()
    {
        $total = ReturnModel::count();
        $pending = ReturnModel::where('status', 'pending_review')->count();
        $processing = ReturnModel::where('status', 'processing')->count();
        $totalValue = Shipment::whereIn('status', ['returned'])
            ->sum('total_amount');

        // Calculate growth from last month
        $lastMonthTotal = ReturnModel::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $growth = $lastMonthTotal > 0 
            ? round((($total - $lastMonthTotal) / $lastMonthTotal) * 100, 1)
            : 0;

        return [
            'total' => $total,
            'pending' => $pending,
            'processing' => $processing,
            'total_value' => $totalValue,
            'growth' => $growth,
        ];
    }

    public function getDetails(ReturnModel $return)
    {
        try {
            $return->load(['customer', 'shipment', 'assignedDriver', 'reviewedBy', 'approvedBy']);

            return response()->json([
                'success' => true,
                'return' => [
                    'id' => $return->id,
                    'return_number' => $return->return_number,
                    'order_number' => $return->order_number,
                    'status' => $return->status,
                    'status_text' => $return->status_badge['text'],
                    'request_date' => $return->request_date->format('Y-m-d'),
                    'warehouse' => $return->warehouse,
                    'tracking_number' => $return->tracking_number,
                    'return_reason' => $return->return_reason,
                    'return_reason_text' => $return->formatted_return_reason,
                    'description' => $return->description,
                    'customer_notes' => $return->customer_notes,
                    'internal_notes' => $return->internal_notes,
                    'attached_images' => $return->attached_images,
                    'return_value' => number_format($return->return_value, 2),
                    'refund_amount' => number_format($return->refund_amount, 2),
                    'items' => $return->items,
                    
                    // Customer info
                    'customer_name' => $return->customer->first_name . ' ' . $return->customer->last_name,
                    'customer_email' => $return->customer->email,
                    'customer_order_count' => $return->customer_order_count ?? 0,
                    'customer_return_count' => $return->customer_return_count ?? 0,
                    'customer_since' => $return->customer_since ? $return->customer_since->format('F Y') : 'N/A',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading return details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load return details',
            ], 500);
        }
    }

    public function approve(Request $request, ReturnModel $return)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $return->approve(auth()->id(), $request->notes);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'approved',
                'model_type' => 'Return',
                'model_id' => $return->id,
                'description' => "Approved return request {$return->return_number}",
                'new_values' => [
                    'status' => 'approved',
                    'notes' => $request->notes,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return approved successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return approval failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve return: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reject(Request $request, ReturnModel $return)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $return->reject(auth()->id(), $request->reason);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'rejected',
                'model_type' => 'Return',
                'model_id' => $return->id,
                'description' => "Rejected return request {$return->return_number}",
                'new_values' => [
                    'status' => 'rejected',
                    'reason' => $request->reason,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return rejected',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return rejection failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject return: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update status to processing
     */
    public function updateToProcessing(ReturnModel $return)
    {
        try {
            DB::beginTransaction();

            $return->update([
                'status' => 'processing',
            ]);

            // Notify customer
            Notification::create([
                'user_id' => $return->customer_id,
                'title' => 'Return Processing',
                'message' => "Your return {$return->return_number} is now being processed.",
                'type' => 'info',
                'channel' => 'system',
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated_status',
                'model_type' => 'Return',
                'model_id' => $return->id,
                'description' => "Updated return {$return->return_number} to processing",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status updated to processing',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Status update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
            ], 500);
        }
    }

    public function complete(Request $request, ReturnModel $return)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $return->complete(auth()->id(), $request->notes);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'completed',
                'model_type' => 'Return',
                'model_id' => $return->id,
                'description' => "Completed return {$return->return_number}",
                'new_values' => [
                    'status' => 'completed',
                    'notes' => $request->notes,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return completed successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return completion failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete return',
            ], 500);
        }
    }

    public function export(Request $request, $format)
    {
        $query = ReturnModel::with(['customer', 'shipment'])
            ->orderBy('request_date', 'desc');

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $returns = $query->get();

        // Log export activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'exported',
            'model_type' => 'Return',
            'description' => "Exported {$returns->count()} returns as {$format}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        switch ($format) {
            case 'csv':
                return $this->exportAsCsv($returns);
            case 'excel':
                return $this->exportAsExcel($returns);
            case 'pdf':
                return $this->exportAsPdf($returns);
            default:
                return redirect()->back()->with('error', 'Invalid export format');
        }
    }

    private function exportAsCsv($returns)
    {
        $filename = 'returns-' . date('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = function() use ($returns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'Return ID',
                'Order ID',
                'Customer Name',
                'Customer Email',
                'Items',
                'Reason',
                'Status',
                'Return Value',
                'Refund Amount',
                'Refund Status',
                'Request Date',
                'Warehouse',
                'Tracking Number',
                'Created At',
            ]);

            // CSV Data
            foreach ($returns as $return) {
                fputcsv($file, [
                    $return->return_number,
                    $return->order_number ?? 'N/A',
                    $return->customer ? $return->customer->first_name . ' ' . $return->customer->last_name : 'N/A',
                    $return->customer->email ?? 'N/A',
                    $return->items_list,
                    $return->formatted_return_reason,
                    ucfirst(str_replace('_', ' ', $return->status)),
                    number_format($return->return_value, 2),
                    number_format($return->refund_amount, 2),
                    ucfirst($return->refund_status),
                    $return->request_date ? $return->request_date->format('Y-m-d') : '',
                    $return->warehouse ?? 'N/A',
                    $return->tracking_number ?? 'N/A',
                    $return->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportAsExcel($returns)
    {
        // For now, use CSV format with .xlsx extension
        // You can integrate Laravel Excel package later
        return $this->exportAsCsv($returns);
    }

    private function exportAsPdf($returns)
    {
        // Basic HTML to PDF conversion
        $html = view('backend.returns.export-pdf', compact('returns'))->render();
        
        $filename = 'returns-' . date('Y-m-d-His') . '.pdf';
        
        // For now, return as HTML
        // You can integrate DomPDF or similar package later
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }

    public function show(ReturnModel $return)
{
    $return->load([
        'customer',
        'shipment.shipmentItems',
        'assignedDriver',
        'assignedTo',
        'reviewedBy',
        'approvedBy'
    ]);

    return view('backend.orders.showreturn', compact('return'));
}

    public function create()
    {
        $customers = User::where('role', 'customer')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();
            
        $shipments = Shipment::where('status', 'delivered')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('backend.returns.create', compact('customers', 'shipments'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:users,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'order_number' => 'nullable|string|max:50',
            'return_reason' => 'required|in:defective_product,wrong_item_sent,changed_mind,damaged_in_transit,not_as_described,quality_issue,size_issue,other',
            'description' => 'nullable|string|max:1000',
            'customer_notes' => 'nullable|string|max:1000',
            'return_value' => 'required|numeric|min:0',
            'items' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Get customer order history
            $customer = User::find($request->customer_id);
            $orderCount = Shipment::where('customer_id', $customer->id)->count();
            $returnCount = ReturnModel::where('customer_id', $customer->id)->count();
            $customerSince = $customer->created_at;

            $return = ReturnModel::create([
                'return_number' => ReturnModel::generateReturnNumber(),
                'order_number' => $request->order_number,
                'shipment_id' => $request->shipment_id,
                'customer_id' => $request->customer_id,
                'return_reason' => $request->return_reason,
                'description' => $request->description,
                'customer_notes' => $request->customer_notes,
                'return_date' => now(),
                'request_date' => now(),
                'status' => 'pending_review',
                'warehouse' => $request->warehouse ?? 'Main Warehouse',
                'return_value' => $request->return_value,
                'refund_amount' => $request->return_value,
                'items' => $request->items,
                'customer_order_count' => $orderCount,
                'customer_return_count' => $returnCount,
                'customer_since' => $customerSince,
            ]);

            // Create notification for admin
            Notification::create([
                'user_id' => null, // System notification
                'title' => 'New Return Request',
                'message' => "New return request {$return->return_number} from {$customer->first_name} {$customer->last_name}",
                'type' => 'info',
                'channel' => 'system',
            ]);

            // Create notification for customer
            Notification::create([
                'user_id' => $customer->id,
                'title' => 'Return Request Received',
                'message' => "Your return request {$return->return_number} has been received and is under review.",
                'type' => 'info',
                'channel' => 'system',
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'model_type' => 'Return',
                'model_id' => $return->id,
                'description' => "Created return request {$return->return_number}",
                'new_values' => $return->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.returns.index')
                ->with('success', 'Return request created successfully! Return ID: ' . $return->return_number);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to create return request: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, ReturnModel $return)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:pending_review,approved,processing,completed,rejected,cancelled',
            'warehouse' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'internal_notes' => 'nullable|string|max:2000',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldValues = $return->toArray();
            
            $return->update($request->only([
                'status',
                'warehouse',
                'tracking_number',
                'internal_notes',
                'admin_notes',
            ]));

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'model_type' => 'Return',
                'model_id' => $return->id,
                'description' => "Updated return {$return->return_number}",
                'old_values' => $oldValues,
                'new_values' => $return->fresh()->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Return updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to update return')
                ->withInput();
        }
    }

    public function destroy(ReturnModel $return)
    {
        try {
            DB::beginTransaction();

            $returnNumber = $return->return_number;

            // Log activity before deletion
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'model_type' => 'Return',
                'model_id' => $return->id,
                'description' => "Deleted return {$returnNumber}",
                'old_values' => $return->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $return->delete();

            DB::commit();

            return redirect()->route('admin.returns.index')
                ->with('success', "Return {$returnNumber} deleted successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete return');
        }
    }

public function getShipmentDetails(Shipment $shipment)
{
    if (!$shipment->canBeReturned()) {
        return response()->json([
            'success' => false,
            'message' => 'This shipment cannot be returned',
        ], 400);
    }

    $shipment->load(['customer', 'shipmentItems', 'carrier']);

    return response()->json([
        'success' => true,
        'shipment' => [
            'id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'customer' => [
                'name' => $shipment->customer->first_name . ' ' . $shipment->customer->last_name,
                'email' => $shipment->customer->email,
                'phone' => $shipment->delivery_contact_phone,
            ],
            'items' => $shipment->shipmentItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'value' => number_format($item->value, 2),
                    'weight' => $item->weight,
                ];
            }),
            'total_value' => number_format($shipment->total_value, 2),
            'delivery_date' => $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('Y-m-d') : null,
            'delivery_address' => $shipment->delivery_address . ', ' . 
                                 $shipment->delivery_city . ', ' . 
                                 $shipment->delivery_state . ' ' . 
                                 $shipment->delivery_postal_code,
        ],
    ]);
}







}