<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Carriers;
use App\Models\ReturnModel;
use App\Models\Shipment;
use App\Models\shipment_items;
use App\Models\ShipmentDelay;
use App\Models\ShipmentIssue;
use App\Models\ShipmentItem;
use App\Models\User;
use App\Models\Carrier;
use App\Models\Branch;
use App\Models\Hub;
use App\Models\Notification;
use App\Models\Vehicle;
use App\Models\PricingRule;
use App\Models\Setting;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Services\NotificationService;
use Carbon\Carbon;

class ShipmentController extends Controller
{
    /**
     * Display a listing of shipments
     */
    public function indexshipments(Request $request)
{
    $query = Shipment::with(['customer', 'assignedDriver', 'carrier'])
        ->orderBy('created_at', 'desc');

    // Apply filters from session or request
    $filters = $request->session()->get('shipment_filters', []);
    
    if ($request->has('clear_filters')) {
        $request->session()->forget('shipment_filters');
        return redirect()->route('admin.shipments.index');
    }
    
    // Update filters if new ones are provided
    if ($request->has('filter')) {
        $filters = array_merge($filters, $request->only(['status', 'type', 'priority', 'carrier', 'date_from', 'date_to']));
        $request->session()->put('shipment_filters', $filters);
    }

    // Apply status filter
    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    // Apply type filter
    if (!empty($filters['type'])) {
        $query->where('shipment_type', $filters['type']);
    }

    // Apply priority filter
    if (!empty($filters['priority'])) {
        $query->where('delivery_priority', $filters['priority']);
    }

    // Apply carrier filter
    if (!empty($filters['carrier'])) {
        $query->where('carrier_id', $filters['carrier']);
    }

    // Apply date range filter
    if (!empty($filters['date_from'])) {
        $query->whereDate('created_at', '>=', $filters['date_from']);
    }
    if (!empty($filters['date_to'])) {
        $query->whereDate('created_at', '<=', $filters['date_to']);
    }

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('tracking_number', 'like', "%{$search}%")
                ->orWhere('reference_number', 'like', "%{$search}%")
                ->orWhere('pickup_contact_name', 'like', "%{$search}%")
                ->orWhere('delivery_contact_name', 'like', "%{$search}%")
                ->orWhereHas('customer', function($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
        });
    }

    // For drivers - show only assigned shipments
    if (auth()->user()->role === 'driver') {
        $query->where('assigned_driver_id', auth()->id());
    }

    // For customers - show only their shipments
    if (auth()->user()->role === 'customer') {
        $query->where('customer_id', auth()->id());
    }

    // Get view preference (list or card)
    $viewMode = $request->session()->get('shipment_view_mode', 'list');
    if ($request->has('view_mode')) {
        $viewMode = $request->view_mode;
        $request->session()->put('shipment_view_mode', $viewMode);
    }

    // Pagination
    $perPage = $viewMode === 'card' ? 12 : 20;
    $shipments = $query->paginate($perPage)->appends($request->except('page'));

    // Calculate statistics
    $stats = [
        'total' => Shipment::count(),
        'in_transit' => Shipment::where('status', 'in_transit')->count(),
        'delivered' => Shipment::where('status', 'delivered')->count(),
        'pending' => Shipment::where('status', 'pending')->count(),
        'total_weight' => Shipment::sum('total_weight'),
        'avg_weight' => Shipment::avg('total_weight'),
        'total_value' => Shipment::sum('total_value'),
        'avg_value' => Shipment::avg('total_value'),
        'total_items' => Shipment::sum('number_of_items'),
        'avg_items' => Shipment::avg('number_of_items'),
    ];

    // Get carriers for filter dropdown
    $carriers = Carriers::where('status', 'active')->get();

    return view('backend.shipments.shipments', compact('shipments', 'stats', 'carriers', 'filters', 'viewMode'));
}

/**
 * Quick view shipment details (AJAX)
 */
public function quickView(Shipment $shipment)
{
    // Check permissions
    if (auth()->user()->role === 'customer' && $shipment->customer_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    if (auth()->user()->role === 'driver' && $shipment->assigned_driver_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $shipment->load(['customer', 'carrier', 'assignedDriver']);

    return response()->json([
        'success' => true,
        'shipment' => [
            'id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'status' => $shipment->status,
            'type' => $shipment->shipment_type,
            'priority' => $shipment->delivery_priority,
            'customer' => $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A',
            'origin' => $shipment->pickup_city . ', ' . $shipment->pickup_state,
            'destination' => $shipment->delivery_city . ', ' . $shipment->delivery_state,
            'carrier' => $shipment->carrier->name ?? 'N/A',
            'departure' => $shipment->pickup_date ? $shipment->pickup_date->format('M d, Y') : 'N/A',
            'eta' => $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('M d, Y') : 'N/A',
            'progress' => $this->calculateProgress($shipment),
        ]
    ]);
}

/**
 * Calculate shipment progress percentage
 */
private function calculateProgress($shipment)
{
    $statusProgress = [
        'draft' => 0,
        'pending' => 10,
        'picked_up' => 30,
        'in_transit' => 65,
        'out_for_delivery' => 85,
        'delivered' => 100,
        'failed' => 0,
        'returned' => 0,
        'cancelled' => 0,
    ];

    return $statusProgress[$shipment->status] ?? 0;
}
/**
 * Duplicate shipment
 */
public function duplicateShipment(Shipment $shipment)
{
    try {
        DB::beginTransaction();

        // Create new shipment with same data
        $newShipment = $shipment->replicate();
        $newShipment->tracking_number = Shipment::generateTrackingNumber();
        $newShipment->status = 'draft';
        $newShipment->pickup_date = null;
        $newShipment->expected_delivery_date = null;
        $newShipment->actual_delivery_date = null;
        $newShipment->created_at = now();
        $newShipment->updated_at = now();
        $newShipment->save();

        // Duplicate shipment items
        foreach ($shipment->shipmentItems as $item) {
            $newItem = $item->replicate();
            $newItem->shipment_id = $newShipment->id;
            $newItem->save();
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'duplicated',
            'model_type' => 'Shipment',
            'model_id' => $newShipment->id,
            'description' => "Duplicated from shipment {$shipment->tracking_number}",
            'new_values' => $newShipment->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Create notification
        Notification::create([
            'user_id' => auth()->id(),
            'shipment_id' => $newShipment->id,
            'title' => 'Shipment Duplicated',
            'message' => "Shipment {$shipment->tracking_number} has been duplicated as {$newShipment->tracking_number}",
            'type' => 'info',
            'channel' => 'system',
            'action_url' => route('backend.shipments.edit', $newShipment->id),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Shipment duplicated successfully!',
            'tracking_number' => $newShipment->tracking_number,
            'redirect_url' => route('backend.shipments.edit', $newShipment->id),
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Shipment duplication failed: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to duplicate shipment: ' . $e->getMessage(),
        ], 500);
    }
}

/**
 * Bulk delete shipments
 */
public function bulkDelete(Request $request)
{
    // Validate input
    $request->validate([
        'shipment_ids' => 'required|array|min:1',
        'shipment_ids.*' => 'integer|exists:shipments,id',
    ]);

    try {
        DB::beginTransaction();

        $deletedCount = 0;
        $failedCount = 0;
        $failedShipments = [];

        foreach ($request->shipment_ids as $shipmentId) {
            $shipment = Shipment::find($shipmentId);
            
            if (!$shipment) {
                $failedCount++;
                $failedShipments[] = "Shipment ID #{$shipmentId} not found";
                continue;
            }

            // Only allow deletion of draft and cancelled shipments
            if (!in_array($shipment->status, ['draft', 'cancelled'])) {
                $failedCount++;
                $failedShipments[] = "Shipment #{$shipment->tracking_number} cannot be deleted (status: {$shipment->status})";
                continue;
            }

            // Check permissions (if user is a customer)
            if (auth()->user()->hasRole('customer') && $shipment->customer_id !== auth()->id()) {
                $failedCount++;
                $failedShipments[] = "No permission to delete shipment #{$shipment->tracking_number}";
                continue;
            }

            // Log activity before deletion
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'bulk_deleted',
                'model_type' => 'Shipment',
                'model_id' => $shipment->id,
                'description' => "Bulk deleted shipment {$shipment->tracking_number}",
                'old_values' => $shipment->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Delete the shipment
            $shipment->delete();
            $deletedCount++;
        }

        DB::commit();

        // Build response message
        $message = "Successfully deleted {$deletedCount} shipment(s)";
        if ($failedCount > 0) {
            $message .= ". Failed to delete {$failedCount} shipment(s) (only draft or cancelled shipments can be deleted)";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'deleted_count' => $deletedCount,
            'failed_count' => $failedCount,
            'failed_shipments' => $failedShipments, // Include detailed failure reasons
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Bulk delete failed: ' . $e->getMessage(), [
            'user_id' => auth()->id(),
            'shipment_ids' => $request->shipment_ids,
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete shipments: ' . $e->getMessage(),
        ], 500);
    }
}
/**
 * Export shipments
 */
public function exportShipments(Request $request, $format)
{
    $query = Shipment::with(['customer', 'carrier', 'assignedDriver'])
        ->orderBy('created_at', 'desc');

    // Apply same filters as index
    $filters = $request->session()->get('shipment_filters', []);
    
    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }
    if (!empty($filters['type'])) {
        $query->where('shipment_type', $filters['type']);
    }
    if (!empty($filters['priority'])) {
        $query->where('delivery_priority', $filters['priority']);
    }
    if (!empty($filters['carrier'])) {
        $query->where('carrier_id', $filters['carrier']);
    }
    if (!empty($filters['date_from'])) {
        $query->whereDate('created_at', '>=', $filters['date_from']);
    }
    if (!empty($filters['date_to'])) {
        $query->whereDate('created_at', '<=', $filters['date_to']);
    }

    $shipments = $query->get();

    // Log export activity
    ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'exported',
        'model_type' => 'Shipment',
        'description' => "Exported {$shipments->count()} shipments as {$format}",
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    switch ($format) {
        case 'csv':
            return $this->exportAsCsv($shipments);
        case 'excel':
            return $this->exportAsExcel($shipments);
        case 'pdf':
            return $this->exportAsPdf($shipments);
        default:
            return redirect()->back()->with('error', 'Invalid export format');
    }
}

/**
 * Export as CSV
 */
private function exportAsCsv($shipments)
{
    $filename = 'shipments-' . date('Y-m-d-His') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ];

    $callback = function() use ($shipments) {
        $file = fopen('php://output', 'w');
        
        // CSV Headers
        fputcsv($file, [
            'ID',
            'Tracking Number',
            'Customer',
            'Origin',
            'Destination',
            'Type',
            'Priority',
            'Status',
            'Carrier',
            'Weight (kg)',
            'Value ($)',
            'Items',
            'Pickup Date',
            'Expected Delivery',
            'Actual Delivery',
            'Created At',
        ]);

        // CSV Data
        foreach ($shipments as $shipment) {
            fputcsv($file, [
                $shipment->id,
                $shipment->tracking_number,
                $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A',
                $shipment->pickup_city . ', ' . $shipment->pickup_state,
                $shipment->delivery_city . ', ' . $shipment->delivery_state,
                ucfirst($shipment->shipment_type),
                ucfirst($shipment->delivery_priority),
                ucfirst(str_replace('_', ' ', $shipment->status)),
                $shipment->carrier->name ?? 'N/A',
                $shipment->total_weight,
                number_format($shipment->total_value, 2),
                $shipment->number_of_items,
                $shipment->pickup_date ? $shipment->pickup_date->format('Y-m-d') : '',
                $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('Y-m-d') : '',
                $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('Y-m-d') : '',
                $shipment->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Export as Excel (using CSV format for now)
 */
private function exportAsExcel($shipments)
{
    // For now, use CSV format with .xlsx extension
    // Later, you can integrate Laravel Excel package
    return $this->exportAsCsv($shipments);
}

/**
 * Export as PDF
 */
private function exportAsPdf($shipments)
{
    // Basic HTML to PDF conversion without external package
    $html = view('admin.shipments.export-pdf', compact('shipments'))->render();
    
    $filename = 'shipments-' . date('Y-m-d-His') . '.pdf';
    
    // For now, return as HTML
    // Later, you can integrate DomPDF or similar
    return response($html)
        ->header('Content-Type', 'text/html')
        ->header('Content-Disposition', "inline; filename=\"$filename\"");
}

 
public function createshipments()
{
    // Get customers (users with customer role)
    $customers = User::where('role', 'customer')
        ->where('status', 'active')
        ->orderBy('first_name')
        ->get();
        
    $carriers = Carriers::where('status', 'active')->get();
    $branches = Branch::where('status', 'active')->get();
    $warehouse = Warehouse::where('status', 'active')->get();
    $hubs = Hub::where('status', 'active')->get();
    $drivers = User::where('role', 'driver')
        ->where('status', 'active')
        ->where('is_available', true)
        ->get();
    $vehicles = Vehicle::where('status', 'available')->get();

    // Get pricing settings for the view
    $pricingSettings = [
        'currency' => Setting::get('pricing_currency', 'USD'),
        'currency_symbol' => Setting::get('pricing_currency_symbol', '$'),
        
        // Standard Package
        'standard_package' => [
            'standard' => floatval(Setting::get('pricing_standard_package_standard', '15.99')),
            'express' => floatval(Setting::get('pricing_standard_package_express', '29.99')),
            'overnight' => floatval(Setting::get('pricing_standard_package_overnight', '49.99')),
        ],
        
        // Document Envelope
        'document_envelope' => [
            'standard' => floatval(Setting::get('pricing_document_envelope_standard', '9.99')),
            'express' => floatval(Setting::get('pricing_document_envelope_express', '19.99')),
            'overnight' => floatval(Setting::get('pricing_document_envelope_overnight', '34.99')),
        ],
        
        // Freight/Pallet
        'freight_pallet' => [
            'standard' => floatval(Setting::get('pricing_freight_pallet_standard', '99.99')),
            'express' => floatval(Setting::get('pricing_freight_pallet_express', '149.99')),
            'overnight' => floatval(Setting::get('pricing_freight_pallet_overnight', '249.99')),
        ],
        
        // Bulk Cargo
        'bulk_cargo' => [
            'standard' => floatval(Setting::get('pricing_bulk_cargo_standard', '199.99')),
            'express' => floatval(Setting::get('pricing_bulk_cargo_express', '299.99')),
            'overnight' => floatval(Setting::get('pricing_bulk_cargo_overnight', '449.99')),
        ],
        
        // Additional Charges
        'weight_threshold' => floatval(Setting::get('pricing_weight_threshold', '10')),
        'weight_rate_per_lb' => floatval(Setting::get('pricing_weight_rate_per_lb', '0.50')),
        'distance_rate_per_mile' => floatval(Setting::get('pricing_distance_rate_per_mile', '0.75')),
        'zone_local' => floatval(Setting::get('pricing_zone_local', '5.00')),
        'zone_regional' => floatval(Setting::get('pricing_zone_regional', '15.00')),
        'zone_national' => floatval(Setting::get('pricing_zone_national', '35.00')),
        'zone_international' => floatval(Setting::get('pricing_zone_international', '100.00')),
        'insurance_rate' => floatval(Setting::get('pricing_insurance_rate', '2')),
        'signature_fee' => floatval(Setting::get('pricing_signature_fee', '5.00')),
        'temperature_controlled_fee' => floatval(Setting::get('pricing_temperature_controlled_fee', '25.00')),
        'fragile_handling_fee' => floatval(Setting::get('pricing_fragile_handling_fee', '10.00')),
        'tax_percentage' => floatval(Setting::get('pricing_tax_percentage', '10')),
    ];

    return view('backend.shipments.create', compact(
        'customers',
        'carriers',
        'branches',
        'warehouse',
        'hubs',
        'drivers',
        'vehicles',
        'pricingSettings'
    ));
}

    public function storeshipments(Request $request)
{
    Log::info('=== SHIPMENT CREATION STARTED ===');
    Log::info('Request Method: ' . $request->method());
    Log::info('Request URL: ' . $request->fullUrl());
    Log::info('All Request Data:', $request->all());
    Log::info('Request Headers:', $request->headers->all());

    try {
        DB::beginTransaction();
        Log::info('Database transaction started');

        // Prepare shipment data with explicit field mapping
$shipmentData = [
    'tracking_number' => Shipment::generateTrackingNumber(),
    'status' => $request->has('save_as_draft') ? 'draft' : 'pending',
    'customer_id' => $request->customer_id,
    'shipment_type' => $request->shipment_type,
    'delivery_priority' => $request->delivery_priority,
    'shipping_zone' => $request->shipping_zone, // NEW: Add shipping zone
    
    // Pickup Information - ALL REQUIRED FIELDS
    'pickup_company_name' => $request->pickup_company_name ?? null,
    'pickup_contact_name' => $request->pickup_contact_name,
    'pickup_contact_phone' => $request->pickup_contact_phone,
    'pickup_contact_email' => $request->pickup_contact_email ?? null,
    'pickup_address' => $request->pickup_address,
    'pickup_address_line2' => $request->pickup_address_line2 ?? null,
    'pickup_city' => $request->pickup_city,
    'pickup_state' => $request->pickup_state,
    'pickup_country' => $request->pickup_country ?? 'USA',
    'pickup_postal_code' => $request->pickup_postal_code,
    'pickup_latitude' => $request->pickup_latitude ?? null,
    'pickup_longitude' => $request->pickup_longitude ?? null,
    
    // Delivery Information - ALL REQUIRED FIELDS
    'delivery_company_name' => $request->delivery_company_name ?? null,
    'delivery_contact_name' => $request->delivery_contact_name,
    'delivery_contact_phone' => $request->delivery_contact_phone,
    'delivery_contact_email' => $request->delivery_contact_email ?? null,
    'delivery_address' => $request->delivery_address,
    'delivery_address_line2' => $request->delivery_address_line2 ?? null,
    'delivery_city' => $request->delivery_city,
    'delivery_state' => $request->delivery_state,
    'delivery_country' => $request->delivery_country ?? 'USA',
    'delivery_postal_code' => $request->delivery_postal_code,
    'delivery_latitude' => $request->delivery_latitude ?? null,
    'delivery_longitude' => $request->delivery_longitude ?? null,
    
    // Dates
    'pickup_date' => $request->pickup_date ?? now(),
    'preferred_delivery_date' => $request->preferred_delivery_date ?? null,
    
    // Special Services (convert checkbox "on" to boolean)
    'insurance_required' => $request->has('insurance_required') ? 1 : 0,
    'insurance_amount' => $request->total_value ?? 0, // Use total_value for insurance amount
    'signature_required' => $request->has('signature_required') ? 1 : 0,
    'temperature_controlled' => $request->has('temperature_controlled') ? 1 : 0,
    'fragile_handling' => $request->has('fragile_handling') ? 1 : 0,
    'carrier_id' => $request->carrier_id ?? null,
    'assigned_driver_id' => $request->assigned_driver_id ?? null,
    'service_level' => $request->service_level ?? null,
    'payment_mode' => $request->payment_mode ?? 'prepaid',
    'cod_amount' => $request->cod_amount ?? 0,
    'special_instructions' => $request->special_instructions ?? null,
    
    // Pricing - Use calculated values from form
    'base_price' => $request->base_price ?? 0,
    'weight_charge' => $request->weight_charge ?? 0,
    'distance_charge' => $request->distance_charge ?? 0,
    'priority_charge' => $request->priority_charge ?? 0,
    'tax_amount' => $request->tax_amount ?? 0,
    'insurance_fee' => $request->insurance_fee ?? 0,
    'additional_services_fee' => $request->additional_services_fee ?? 0,
    'total_amount' => $request->total_amount ?? 0,
];
        
        // Calculate totals from items
        $totalWeight = 0;
        $totalValue = 0;
        $numberOfItems = 0;

        foreach ($request->items as $item) {
            $qty = $item['quantity'] ?? 1;
            $totalWeight += ($item['weight'] ?? 0) * $qty;
            $totalValue += ($item['value'] ?? 0) * $qty;
            $numberOfItems += $qty;
        }

        $shipmentData['total_weight'] = $totalWeight;
        $shipmentData['total_value'] = $totalValue;
        $shipmentData['number_of_items'] = $numberOfItems;

        Log::info('Prepared Shipment Data:', $shipmentData);

        // Create shipment
        $shipment = Shipment::create($shipmentData);
        Log::info('Shipment Created Successfully', ['id' => $shipment->id, 'tracking' => $shipment->tracking_number]);

        // Create shipment items
        foreach ($request->items as $index => $itemData) {
            $item = $shipment->shipmentItems()->create($itemData);
            Log::info("Item $index created", ['item_id' => $item->id]);
        }

        // Notify driver if one was assigned
        if ($request->assigned_driver_id) {
            try {
                $pickupLocation = $request->pickup_city . ', ' . $request->pickup_state;
                $deliveryLocation = $request->delivery_city . ', ' . $request->delivery_state;
                $pickupDate = \Carbon\Carbon::parse($request->pickup_date ?? now())->format('M d, Y');
                
                NotificationService::create(
                    userId: $request->assigned_driver_id,
                    title: 'New Shipment Assigned',
                    message: "You have been assigned shipment {$shipment->tracking_number}. Pickup from {$pickupLocation} to {$deliveryLocation} on {$pickupDate}.",
                    type: 'info',
                    actionUrl: route('admin.shipments.show', $shipment->id),
                    shipmentId: $shipment->id,
                    data: [
                        'shipment_id' => $shipment->id,
                        'tracking_number' => $shipment->tracking_number,
                        'pickup_location' => $pickupLocation,
                        'delivery_location' => $deliveryLocation,
                        'pickup_date' => $pickupDate,
                        'priority' => $request->delivery_priority
                    ]
                );
                
                Log::info('Driver notification sent', [
                    'driver_id' => $request->assigned_driver_id,
                    'shipment_id' => $shipment->id
                ]);
            } catch (\Exception $e) {
                // Log notification error but don't fail the shipment creation
                Log::error('Failed to send driver notification: ' . $e->getMessage());
            }
        }

        // Notify admins about new shipment (only if not draft)
        if (!$request->has('save_as_draft')) {
            try {
                NotificationService::notifyAdmins(
                    title: 'New Shipment Created',
                    message: "New shipment {$shipment->tracking_number} has been created and is pending processing.",
                    type: 'info',
                    actionUrl: route('admin.shipments.show', $shipment->id),
                    data: [
                        'shipment_id' => $shipment->id,
                        'tracking_number' => $shipment->tracking_number,
                        'customer_id' => $request->customer_id,
                        'total_amount' => $request->total_amount ?? 0
                    ]
                );
                
                Log::info('Admin notification sent for new shipment');
            } catch (\Exception $e) {
                Log::error('Failed to send admin notification: ' . $e->getMessage());
            }
        }

        DB::commit();
        Log::info('=== SHIPMENT CREATION COMPLETED SUCCESSFULLY ===');

        return redirect()->route('admin.shipments.index', $shipment->id)
            ->with('success', 'Shipment created successfully! Tracking: ' . $shipment->tracking_number);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('=== SHIPMENT CREATION FAILED ===');
        Log::error('Error Message: ' . $e->getMessage());
        Log::error('Error File: ' . $e->getFile());
        Log::error('Error Line: ' . $e->getLine());
        Log::error('Stack Trace:', ['trace' => $e->getTraceAsString()]);
        
        return redirect()->back()
            ->with('error', 'Failed to create shipment: ' . $e->getMessage())
            ->withInput();
    }
}

  
    public function editshipments(Shipment $shipment)
{
    // Check if shipment can be edited
    if (!$shipment->can_be_edited) {
        return redirect()->route('admin.shipments.show', $shipment->id)
            ->with('error', 'This shipment cannot be edited in its current status.');
    }

    // Check permissions
    if (auth()->user()->isCustomer() && $shipment->customer_id !== auth()->id()) {
        abort(403);
    }

    $customers = User::where('role', 'customer')
        ->where('status', 'active')
        ->get();
    $carriers = carriers::where('status', 'active')->get();
    $branches = Branch::where('status', 'active')->get();
    $warehouse = Warehouse::where('status', 'active')->get();
    $hubs = Hub::where('status', 'active')->get();
    $drivers = User::where('role', 'driver')
        ->where('status', 'active')
        ->get();
    $vehicles = Vehicle::where('status', 'available')->get();

    $shipment->load('shipmentItems');

    // Get pricing settings for the view
    $pricingSettings = [
        'currency' => Setting::get('pricing_currency', 'USD'),
        'currency_symbol' => Setting::get('pricing_currency_symbol', '$'),
        
        // Standard Package
        'standard_package' => [
            'standard' => floatval(Setting::get('pricing_standard_package_standard', '15.99')),
            'express' => floatval(Setting::get('pricing_standard_package_express', '29.99')),
            'overnight' => floatval(Setting::get('pricing_standard_package_overnight', '49.99')),
        ],
        
        // Document Envelope
        'document_envelope' => [
            'standard' => floatval(Setting::get('pricing_document_envelope_standard', '9.99')),
            'express' => floatval(Setting::get('pricing_document_envelope_express', '19.99')),
            'overnight' => floatval(Setting::get('pricing_document_envelope_overnight', '34.99')),
        ],
        
        // Freight/Pallet
        'freight_pallet' => [
            'standard' => floatval(Setting::get('pricing_freight_pallet_standard', '99.99')),
            'express' => floatval(Setting::get('pricing_freight_pallet_express', '149.99')),
            'overnight' => floatval(Setting::get('pricing_freight_pallet_overnight', '249.99')),
        ],
        
        // Bulk Cargo
        'bulk_cargo' => [
            'standard' => floatval(Setting::get('pricing_bulk_cargo_standard', '199.99')),
            'express' => floatval(Setting::get('pricing_bulk_cargo_express', '299.99')),
            'overnight' => floatval(Setting::get('pricing_bulk_cargo_overnight', '449.99')),
        ],
        
        // Additional Charges
        'weight_threshold' => floatval(Setting::get('pricing_weight_threshold', '10')),
        'weight_rate_per_lb' => floatval(Setting::get('pricing_weight_rate_per_lb', '0.50')),
        'distance_rate_per_mile' => floatval(Setting::get('pricing_distance_rate_per_mile', '0.75')),
        'zone_local' => floatval(Setting::get('pricing_zone_local', '5.00')),
        'zone_regional' => floatval(Setting::get('pricing_zone_regional', '15.00')),
        'zone_national' => floatval(Setting::get('pricing_zone_national', '35.00')),
        'zone_international' => floatval(Setting::get('pricing_zone_international', '100.00')),
        'insurance_rate' => floatval(Setting::get('pricing_insurance_rate', '2')),
        'signature_fee' => floatval(Setting::get('pricing_signature_fee', '5.00')),
        'temperature_controlled_fee' => floatval(Setting::get('pricing_temperature_controlled_fee', '25.00')),
        'fragile_handling_fee' => floatval(Setting::get('pricing_fragile_handling_fee', '10.00')),
        'tax_percentage' => floatval(Setting::get('pricing_tax_percentage', '10')),
    ];

    return view('backend.shipments.edit', compact(
        'shipment',
        'customers',
        'carriers',
        'branches',
        'warehouse',
        'hubs',
        'drivers',
        'vehicles',
        'pricingSettings'
    ));
}

   public function updateshipments(Request $request, Shipment $shipment)
{
    // Check if shipment can be edited
    if (!$shipment->can_be_edited) {
        return redirect()->route('admin.shipments.show', $shipment->id)
            ->with('error', 'This shipment cannot be edited in its current status.');
    }

    $validator = Validator::make($request->all(), [
    'pickup_contact_name' => 'required|string|max:255',
    'pickup_contact_phone' => 'required|string|max:20',
    'pickup_address' => 'required|string',
    'pickup_city' => 'required|string|max:100',
    'pickup_state' => 'required|string|max:100',
    'pickup_country' => 'required|string|max:100',
    'pickup_postal_code' => 'required|string|max:20',
    'delivery_contact_name' => 'required|string|max:255',
    'delivery_contact_phone' => 'required|string|max:20',
    'delivery_address' => 'required|string',
    'delivery_city' => 'required|string|max:100',
    'delivery_state' => 'required|string|max:100',
    'delivery_country' => 'required|string|max:100',
    'delivery_postal_code' => 'required|string|max:20',
    'shipment_type' => 'required|in:Standard Package,Document Envelope,Freight/Pallet,Bulk Cargo',
    'delivery_priority' => 'required|in:standard,express,overnight',
    'shipping_zone' => 'required|in:local,regional,national,international', // NEW: Add shipping zone validation
    'assigned_driver_id' => 'nullable|exists:users,id',
]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        DB::beginTransaction();

        $oldValues = $shipment->toArray();
        
        // Capture old driver ID before update
        $oldDriverId = $shipment->assigned_driver_id;
        $newDriverId = $request->assigned_driver_id;
        
        // Check if driver assignment changed
        $driverChanged = $oldDriverId != $newDriverId;
        
        // Update shipment
        $shipmentData = $request->except(['items']);
        $shipment->update($shipmentData);

        // Update or create items
        if ($request->has('items')) {
            // Delete existing items
            $shipment->shipmentItems()->delete();

            // Create new items
            $totalWeight = 0;
            $totalValue = 0;
            $numberOfItems = 0;

            foreach ($request->items as $itemData) {
                $shipment->shipmentItems()->create($itemData);
                $totalWeight += ($itemData['weight'] ?? 0) * ($itemData['quantity'] ?? 1);
                $totalValue += ($itemData['value'] ?? 0) * ($itemData['quantity'] ?? 1);
                $numberOfItems += $itemData['quantity'] ?? 1;
            }

            $shipment->update([
                'total_weight' => $totalWeight,
                'total_value' => $totalValue,
                'number_of_items' => $numberOfItems,
            ]);
        }

        // Recalculate pricing
        $shipment->calculatePricing();
        $shipment->save();

        // Handle driver reassignment notifications
        if ($driverChanged) {
            $pickupLocation = $shipment->pickup_city . ', ' . $shipment->pickup_state;
            $deliveryLocation = $shipment->delivery_city . ', ' . $shipment->delivery_state;
            
            // Notify old driver they are unassigned
            if ($oldDriverId) {
                try {
                    NotificationService::create(
                        userId: $oldDriverId,
                        title: 'Shipment Unassigned',
                        message: "You have been unassigned from shipment {$shipment->tracking_number}. This shipment has been reassigned to another driver.",
                        type: 'warning',
                        actionUrl: route('admin.shipments.show', $shipment->id),
                        shipmentId: $shipment->id,
                        data: [
                            'shipment_id' => $shipment->id,
                            'tracking_number' => $shipment->tracking_number,
                            'action' => 'unassigned',
                            'reason' => 'Driver reassignment'
                        ]
                    );
                    
                    Log::info('Previous driver unassignment notification sent', [
                        'driver_id' => $oldDriverId,
                        'shipment_id' => $shipment->id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to notify old driver: ' . $e->getMessage());
                }
            }
            
            // Notify new driver they are assigned
            if ($newDriverId) {
                try {
                    $pickupDate = \Carbon\Carbon::parse($shipment->pickup_date)->format('M d, Y');
                    
                    NotificationService::create(
                        userId: $newDriverId,
                        title: 'New Shipment Assigned',
                        message: "You have been assigned shipment {$shipment->tracking_number}. Pickup from {$pickupLocation} to {$deliveryLocation} on {$pickupDate}.",
                        type: 'info',
                        actionUrl: route('admin.shipments.show', $shipment->id),
                        shipmentId: $shipment->id,
                        data: [
                            'shipment_id' => $shipment->id,
                            'tracking_number' => $shipment->tracking_number,
                            'pickup_location' => $pickupLocation,
                            'delivery_location' => $deliveryLocation,
                            'pickup_date' => $pickupDate,
                            'priority' => $shipment->delivery_priority,
                            'action' => 'assigned'
                        ]
                    );
                    
                    Log::info('New driver assignment notification sent', [
                        'driver_id' => $newDriverId,
                        'shipment_id' => $shipment->id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to notify new driver: ' . $e->getMessage());
                }
            }
            
            // If driver was removed (unassigned) without reassignment
            if ($oldDriverId && !$newDriverId) {
                try {
                    NotificationService::notifyAdmins(
                        title: 'Shipment Driver Removed',
                        message: "Shipment {$shipment->tracking_number} no longer has an assigned driver and requires assignment.",
                        type: 'warning',
                        actionUrl: route('admin.shipments.show', $shipment->id),
                        data: [
                            'shipment_id' => $shipment->id,
                            'tracking_number' => $shipment->tracking_number,
                            'previous_driver_id' => $oldDriverId
                        ]
                    );
                    
                    Log::info('Admin notification sent for unassigned shipment');
                } catch (\Exception $e) {
                    Log::error('Failed to notify admins: ' . $e->getMessage());
                }
            }
        }

        DB::commit();

        // Log activity
        $shipment->logActivity('updated', 'Shipment updated', [
            'old' => $oldValues,
            'new' => $shipment->toArray(),
            'driver_changed' => $driverChanged,
            'old_driver_id' => $oldDriverId,
            'new_driver_id' => $newDriverId,
        ]);

        return redirect()->route('admin.shipments.index', $shipment->id)
            ->with('success', 'Shipment updated successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Shipment update failed: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to update shipment. Please try again.')
            ->withInput();
    }
}


  public function saveDraftshipments(Request $request)
    {
        Log::info('=== DRAFT SAVE STARTED ===');
        Log::info('Request Data:', $request->all());
        
        try {
            DB::beginTransaction();

            $shipmentData = [
                'status' => 'draft',
                'tracking_number' => $request->tracking_number ?? Shipment::generateTrackingNumber(),
                'customer_id' => $request->customer_id ?? auth()->id(),
                'shipment_type' => $request->shipment_type ?? 'standard',
                'delivery_priority' => $request->delivery_priority ?? 'standard',
                
                // Pickup - Provide defaults for required fields
                'pickup_contact_name' => $request->pickup_contact_name ?? 'Draft',
                'pickup_contact_phone' => $request->pickup_contact_phone ?? '000-000-0000',
                'pickup_address' => $request->pickup_address ?? 'Draft Address',
                'pickup_city' => $request->pickup_city ?? 'Draft City',
                'pickup_state' => $request->pickup_state ?? 'Draft State',
                'pickup_country' => $request->pickup_country ?? 'USA',
                'pickup_postal_code' => $request->pickup_postal_code ?? '00000',
                
                // Delivery - Provide defaults for required fields
                'delivery_contact_name' => $request->delivery_contact_name ?? 'Draft',
                'delivery_contact_phone' => $request->delivery_contact_phone ?? '000-000-0000',
                'delivery_address' => $request->delivery_address ?? 'Draft Address',
                'delivery_city' => $request->delivery_city ?? 'Draft City',
                'delivery_state' => $request->delivery_state ?? 'Draft State',
                'delivery_country' => $request->delivery_country ?? 'USA',
                'delivery_postal_code' => $request->delivery_postal_code ?? '00000',
                
                'total_weight' => $request->total_weight ?? 0,
                'total_value' => $request->total_value ?? 0,
                'number_of_items' => $request->number_of_items ?? 1,
                'base_price' => $request->base_price ?? 0,
                'total_amount' => $request->total_amount ?? 0,
            ];

            Log::info('Draft Data Prepared:', $shipmentData);

            if ($request->has('shipment_id') && $request->shipment_id) {
                $shipment = Shipment::findOrFail($request->shipment_id);
                $shipment->update($shipmentData);
                Log::info('Existing draft updated', ['id' => $shipment->id]);
            } else {
                $shipment = Shipment::create($shipmentData);
                Log::info('New draft created', ['id' => $shipment->id]);
            }

            DB::commit();
            Log::info('=== DRAFT SAVED SUCCESSFULLY ===');

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully!',
                'shipment_id' => $shipment->id,
                'tracking_number' => $shipment->tracking_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== DRAFT SAVE FAILED ===');
            Log::error('Error: ' . $e->getMessage());
            Log::error('Trace:', ['trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft: ' . $e->getMessage(),
            ], 500);
        }
    }

    
    public function showshipments(Shipment $shipment)
    {
        // Check permissions
        if (auth()->user()->isCustomer() && $shipment->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this shipment.');
        }

        if (auth()->user()->isDriver() && $shipment->assigned_driver_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this shipment.');
        }

        $shipment->load([
            'customer',
            'sender',
            'carrier',
            'assignedDriver',
            'assignedVehicle',
            'currentBranch',
            'currentWarehouse',
            'currentHub',
            'shipmentItems',
            'trackingHistory.updatedBy',
            'notifications',
        ]);

        return view('backend.shipments.show', compact('shipment'));
    }



public function bulkPrint(Request $request)
{
    $ids = explode(',', $request->input('ids', ''));
    
    $shipments = Shipment::whereIn('id', $ids)
        ->with(['customer', 'carrier', 'shipmentItems'])
        ->get();
    
    if ($shipments->isEmpty()) {
        return redirect()->back()->with('error', 'No shipments found for printing.');
    }
    
    // Return a print-friendly view
    return view('backend.shipments.bulk-print', compact('shipments'));
}

/**
 * Bulk export shipments
 */
public function bulkExport(Request $request)
{
    $ids = explode(',', $request->input('ids', ''));
    
    $shipments = Shipment::whereIn('id', $ids)
        ->with(['customer', 'carrier', 'shipmentItems'])
        ->get();
    
    if ($shipments->isEmpty()) {
        return redirect()->back()->with('error', 'No shipments found for export.');
    }
    
    $filename = 'shipments_bulk_' . date('Y-m-d_His') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];
    
    $callback = function() use ($shipments) {
        $file = fopen('php://output', 'w');
        
        // Add BOM for Excel UTF-8 support
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add headers
        fputcsv($file, [
            'ID', 
            'Tracking Number', 
            'Customer Name',
            'Customer Email',
            'Status', 
            'Origin City',
            'Origin State',
            'Destination City',
            'Destination State',
            'Priority', 
            'Carrier',
            'Pickup Date', 
            'Expected Delivery', 
            'Total Weight (kg)', 
            'Total Value ($)',
            'Number of Items',
            'Service Level',
            'Created At'
        ]);
        
        foreach ($shipments as $shipment) {
            fputcsv($file, [
                $shipment->id,
                $shipment->tracking_number,
                $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A',
                $shipment->customer ? $shipment->customer->email : 'N/A',
                ucfirst(str_replace('_', ' ', $shipment->status)),
                $shipment->pickup_city,
                $shipment->pickup_state,
                $shipment->delivery_city,
                $shipment->delivery_state,
                ucfirst($shipment->delivery_priority),
                $shipment->carrier->name ?? 'N/A',
                $shipment->pickup_date ? $shipment->pickup_date->format('Y-m-d') : 'N/A',
                $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('Y-m-d') : 'N/A',
                $shipment->total_weight ?? 0,
                $shipment->total_value ?? 0,
                $shipment->number_of_items ?? 0,
                $shipment->service_level ? ucfirst($shipment->service_level) : 'N/A',
                $shipment->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

/**
 * Bulk update shipments
 */
public function bulkUpdate(Request $request)
{
    // Validate input
    $request->validate([
        'shipment_ids' => 'required|array|min:1',
        'shipment_ids.*' => 'integer|exists:shipments,id',
        'updates' => 'required|array|min:1',
        'updates.status' => 'sometimes|in:pending,picked_up,in_transit,out_for_delivery,delivered,cancelled',
        'updates.priority' => 'sometimes|in:standard,express,overnight',
        'updates.carrier' => 'sometimes|exists:carriers,id',
        'updates.service_level' => 'sometimes|in:road,air,ocean,rail',
        'updates.expected_delivery_date' => 'sometimes|date',
        'updates.send_notification' => 'sometimes|boolean',
    ]);

    try {
        $updates = $request->input('updates', []);
        $sendNotification = $updates['send_notification'] ?? false;
        unset($updates['send_notification']); // Remove from updates array

        if (empty($updates)) {
            return response()->json([
                'success' => false,
                'message' => 'No fields to update'
            ], 400);
        }

        DB::beginTransaction();

        $updatedCount = 0;
        $failedCount = 0;
        $failedShipments = [];

        foreach ($request->shipment_ids as $shipmentId) {
            $shipment = Shipment::find($shipmentId);
            
            if (!$shipment) {
                $failedCount++;
                $failedShipments[] = "Shipment ID #{$shipmentId} not found";
                continue;
            }

            // Check if shipment can be edited
            if (!$shipment->can_be_edited) {
                $failedCount++;
                $failedShipments[] = "Shipment #{$shipment->tracking_number} cannot be edited (status: {$shipment->status})";
                continue;
            }

            // Check permissions (if user is a customer)
            if (auth()->user()->hasRole('customer') && $shipment->customer_id !== auth()->id()) {
                $failedCount++;
                $failedShipments[] = "No permission to edit shipment #{$shipment->tracking_number}";
                continue;
            }

            $oldValues = $shipment->toArray();

            // Prepare data for update - map field names to database columns
            $updateData = [];
            
            if (isset($updates['status'])) {
                $updateData['status'] = $updates['status'];
            }
            
            if (isset($updates['priority'])) {
                $updateData['delivery_priority'] = $updates['priority'];
            }
            
            if (isset($updates['carrier'])) {
                $updateData['carrier_id'] = $updates['carrier'];
            }
            
            if (isset($updates['service_level'])) {
                $updateData['service_level'] = $updates['service_level'];
            }
            
            if (isset($updates['expected_delivery_date'])) {
                $updateData['expected_delivery_date'] = $updates['expected_delivery_date'];
            }

            // Update the shipment
            $shipment->update($updateData);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'bulk_updated',
                'model_type' => 'Shipment',
                'model_id' => $shipment->id,
                'description' => "Bulk updated shipment {$shipment->tracking_number}",
                'old_values' => $oldValues,
                'new_values' => $shipment->fresh()->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Send notification if requested
            if ($sendNotification && $shipment->customer && $shipment->customer->email) {
                try {
                    // Send email notification
                    // Mail::to($shipment->customer->email)->send(new ShipmentUpdated($shipment));
                    
                    // Or use your notification system
                    // Notification::send($shipment->customer, new ShipmentUpdatedNotification($shipment));
                } catch (\Exception $e) {
                    Log::warning("Failed to send notification for shipment {$shipment->tracking_number}: " . $e->getMessage());
                }
            }

            $updatedCount++;
        }

        DB::commit();

        // Build response message
        $message = "Successfully updated {$updatedCount} shipment(s)";
        if ($failedCount > 0) {
            $message .= ". Failed to update {$failedCount} shipment(s)";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'updated_count' => $updatedCount,
            'failed_count' => $failedCount,
            'failed_shipments' => $failedShipments,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Bulk update failed: ' . $e->getMessage(), [
            'user_id' => auth()->id(),
            'shipment_ids' => $request->shipment_ids,
            'updates' => $request->updates,
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to update shipments: ' . $e->getMessage(),
        ], 500);
    }
}

    /**
     * Remove the specified shipment
     */
    public function destroyshipments(Shipment $shipment)
    {
        // Check permissions
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can delete shipments.');
        }

        // Check if shipment can be deleted
        if (!in_array($shipment->status, ['draft', 'cancelled'])) {
            return redirect()->back()
                ->with('error', 'Only draft or cancelled shipments can be deleted.');
        }

        try {
            $trackingNumber = $shipment->tracking_number;
            $shipment->delete();

            return redirect()->route('admin.shipments.index')
                ->with('success', "Shipment {$trackingNumber} deleted successfully!");

        } catch (\Exception $e) {
            Log::error('Shipment deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete shipment. Please try again.');
        }
    }

public function calculatePricingshipments(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'shipment_type' => 'required|in:Standard Package,Document Envelope,Freight/Pallet,Bulk Cargo',
            'delivery_priority' => 'required|in:standard,express,overnight',
            'total_weight' => 'required|numeric|min:0',
            'total_value' => 'nullable|numeric|min:0',
            'shipping_zone' => 'required|in:local,regional,national,international',
            'insurance_required' => 'nullable|boolean',
            'signature_required' => 'nullable|boolean',
            'temperature_controlled' => 'nullable|boolean',
            'fragile_handling' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get pricing settings from database
        $currencySymbol = Setting::get('pricing_currency_symbol', '$');
        
        // 1. Calculate Base Price based on shipment type and priority
        $basePrice = $this->getBasePriceFromSettings($request->shipment_type, $request->delivery_priority);
        
        // 2. Calculate Weight Charge - Rate per lb × Total Weight
        $weightRatePerLb = floatval(Setting::get('pricing_weight_rate_per_lb', 0.50));
        $totalWeight = floatval($request->total_weight);
        $weightCharge = $totalWeight * $weightRatePerLb;
        
        // 3. Calculate Distance Charge - Zone-based flat rate
        $distanceCharge = $this->getZonePricing($request->shipping_zone);
        
        // 4. Calculate Individual Service Fees
        $signatureFee = 0;
        $temperatureFee = 0;
        $fragileFee = 0;
        
        if ($request->signature_required) {
            $signatureFee = floatval(Setting::get('pricing_signature_fee', 5.00));
        }
        
        if ($request->temperature_controlled) {
            $temperatureFee = floatval(Setting::get('pricing_temperature_controlled_fee', 25.00));
        }
        
        if ($request->fragile_handling) {
            $fragileFee = floatval(Setting::get('pricing_fragile_handling_fee', 10.00));
        }
        
        $additionalServicesFee = $signatureFee + $temperatureFee + $fragileFee;
        
        // 5. Calculate Insurance Fee (percentage of shipment value)
        $insuranceFee = 0;
        $insuranceRate = floatval(Setting::get('pricing_insurance_rate', 2));
        $totalValue = floatval($request->total_value ?? 0);
        
        if ($request->insurance_required && $totalValue > 0) {
            $insuranceFee = ($totalValue * $insuranceRate) / 100;
        }
        
        // 6. Calculate Subtotal (before tax)
        $subtotal = $basePrice + $weightCharge + $distanceCharge + $additionalServicesFee + $insuranceFee;
        
        // 7. Calculate Tax (percentage of subtotal)
        $taxPercentage = floatval(Setting::get('pricing_tax_percentage', 10));
        $taxAmount = ($subtotal * $taxPercentage) / 100;
        
        // 8. Calculate Total
        $totalAmount = $subtotal + $taxAmount;

        // Zone name mapping
        $zoneNames = [
            'local' => 'Local (Same City)',
            'regional' => 'Regional (Same State)',
            'national' => 'National (Different States)',
            'international' => 'International'
        ];

        return response()->json([
            'success' => true,
            'pricing' => [
                'currency_symbol' => $currencySymbol,
                'base_price' => number_format($basePrice, 2, '.', ''),
                'weight_charge' => number_format($weightCharge, 2, '.', ''),
                'weight_rate' => number_format($weightRatePerLb, 2, '.', ''),
                'total_weight' => number_format($totalWeight, 2, '.', ''),
                'distance_charge' => number_format($distanceCharge, 2, '.', ''),
                'shipping_zone' => $request->shipping_zone,
                'zone_name' => $zoneNames[$request->shipping_zone] ?? 'Unknown',
                
                // Individual service fees
                'signature_fee' => number_format($signatureFee, 2, '.', ''),
                'temperature_fee' => number_format($temperatureFee, 2, '.', ''),
                'fragile_fee' => number_format($fragileFee, 2, '.', ''),
                'additional_services_fee' => number_format($additionalServicesFee, 2, '.', ''),
                
                // Insurance details
                'insurance_fee' => number_format($insuranceFee, 2, '.', ''),
                'insurance_rate' => $insuranceRate,
                'total_value' => number_format($totalValue, 2, '.', ''),
                
                'subtotal' => number_format($subtotal, 2, '.', ''),
                'tax_amount' => number_format($taxAmount, 2, '.', ''),
                'tax_percentage' => $taxPercentage,
                'total_amount' => number_format($totalAmount, 2, '.', ''),
            ],
        ]);

    } catch (\Exception $e) {
        Log::error('Pricing calculation failed: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to calculate pricing: ' . $e->getMessage(),
        ], 500);
    }
}

// Add this new helper method
private function getZonePricing($zone)
{
    $zonePricing = [
        'local' => floatval(Setting::get('pricing_zone_local', 5.00)),
        'regional' => floatval(Setting::get('pricing_zone_regional', 15.00)),
        'national' => floatval(Setting::get('pricing_zone_national', 35.00)),
        'international' => floatval(Setting::get('pricing_zone_international', 100.00)),
    ];
    
    return $zonePricing[$zone] ?? 0;
}

private function getBasePriceFromSettings($shipmentType, $priority)
{
    // Map shipment type to setting key prefix
    $typeMap = [
        'Standard Package' => 'standard_package',
        'Document Envelope' => 'document_envelope',
        'Freight/Pallet' => 'freight_pallet',
        'Bulk Cargo' => 'bulk_cargo',
    ];
    
    $typeKey = $typeMap[$shipmentType] ?? 'standard_package';
    $settingKey = "pricing_{$typeKey}_{$priority}";
    
    return floatval(Setting::get($settingKey, 15.99));
}

private function calculateDistanceCharge(
    $pickupLat, 
    $pickupLng, 
    $deliveryLat, 
    $deliveryLng,
    $pickupCity,
    $pickupState,
    $pickupCountry,
    $deliveryCity,
    $deliveryState,
    $deliveryCountry
) {
    // Check if we have valid coordinates for both locations
    if ($pickupLat && $pickupLng && $deliveryLat && $deliveryLng) {
        try {
            // Calculate actual distance using Haversine formula
            $distance = $this->haversineDistance(
                $pickupLat, 
                $pickupLng, 
                $deliveryLat, 
                $deliveryLng
            );
            
            // Get rate per mile from settings
            $ratePerMile = floatval(Setting::get('pricing_distance_rate_per_mile', 0.75));
            
            return $distance * $ratePerMile;
            
        } catch (\Exception $e) {
            Log::warning('Distance calculation failed, falling back to zone-based pricing: ' . $e->getMessage());
        }
    }
    
    // Fallback to zone-based pricing
    return $this->getZoneBasedDistanceCharge(
        $pickupCity,
        $pickupState,
        $pickupCountry,
        $deliveryCity,
        $deliveryState,
        $deliveryCountry
    );
}

private function getZoneBasedDistanceCharge(
    $pickupCity,
    $pickupState,
    $pickupCountry,
    $deliveryCity,
    $deliveryState,
    $deliveryCountry
) {
    // Normalize country values (handle both 'USA' and 'United States')
    $pickupCountry = strtoupper(trim($pickupCountry ?? 'USA'));
    $deliveryCountry = strtoupper(trim($deliveryCountry ?? 'USA'));
    
    // Normalize to common format
    $pickupCountry = in_array($pickupCountry, ['USA', 'US', 'UNITED STATES']) ? 'USA' : $pickupCountry;
    $deliveryCountry = in_array($deliveryCountry, ['USA', 'US', 'UNITED STATES']) ? 'USA' : $deliveryCountry;
    
    // Check if international
    if ($pickupCountry !== $deliveryCountry) {
        return floatval(Setting::get('pricing_zone_international', 100.00));
    }
    
    // Check if same state (regional or local)
    $pickupState = strtoupper(trim($pickupState ?? ''));
    $deliveryState = strtoupper(trim($deliveryState ?? ''));
    
    if ($pickupState !== $deliveryState) {
        // Different states = National
        return floatval(Setting::get('pricing_zone_national', 35.00));
    }
    
    // Same state - check if same city
    $pickupCity = strtoupper(trim($pickupCity ?? ''));
    $deliveryCity = strtoupper(trim($deliveryCity ?? ''));
    
    if ($pickupCity === $deliveryCity && $pickupCity !== '') {
        // Same city = Local
        return floatval(Setting::get('pricing_zone_local', 5.00));
    }
    
    // Same state, different city = Regional
    return floatval(Setting::get('pricing_zone_regional', 15.00));
}




private function haversineDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 3959; // Earth's radius in miles
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    $distance = $earthRadius * $c;
    
    return $distance;
}


    public function getDistanceshipments(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pickup_latitude' => 'required|numeric',
                'pickup_longitude' => 'required|numeric',
                'delivery_latitude' => 'required|numeric',
                'delivery_longitude' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $tempShipment = new Shipment([
                'pickup_latitude' => $request->pickup_latitude,
                'pickup_longitude' => $request->pickup_longitude,
                'delivery_latitude' => $request->delivery_latitude,
                'delivery_longitude' => $request->delivery_longitude,
            ]);

            $distance = $tempShipment->calculateDistance();

            return response()->json([
                'success' => true,
                'distance' => number_format($distance, 2),
                'distance_charge' => number_format($distance * 0.75, 2),
            ]);

        } catch (\Exception $e) {
            Log::error('Distance calculation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate distance.',
            ], 500);
        }
    }

    public function validateAddressshipments(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'postal_code' => 'required|string',
                'country' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $isValid = !empty($request->address) && 
                       !empty($request->city) && 
                       !empty($request->postal_code);

            return response()->json([
                'success' => true,
                'valid' => $isValid,
                'message' => $isValid ? 'Address is valid' : 'Address validation failed',
            ]);

        } catch (\Exception $e) {
            Log::error('Address validation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'Failed to validate address.',
            ], 500);
        }
    }

    /**
     * Update shipment status
     */
    public function updateStatus(Request $request, Shipment $shipment)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,pending,picked_up,in_transit,out_for_delivery,delivered,failed,returned,cancelled',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $shipment->updateStatus(
                $request->status,
                $request->description,
                $request->location
            );

            return redirect()->back()
                ->with('success', 'Shipment status updated successfully!');

        } catch (\Exception $e) {
            Log::error('Status update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * Track shipment (public)
     */
    public function track(Request $request)
    {
        $trackingNumber = $request->input('tracking_number');
        
        if (!$trackingNumber) {
            return view('public.track');
        }

        $shipment = Shipment::where('tracking_number', $trackingNumber)
            ->with(['trackingHistory', 'customer'])
            ->first();

        if (!$shipment) {
            return view('public.track')
                ->with('error', 'Shipment not found. Please check your tracking number.');
        }

        return view('public.track-result', compact('shipment'));
    }




















public function recordDelayForShipment(Request $request, Shipment $shipment)
{
    $validator = Validator::make($request->all(), [
        'delay_reason' => 'required|in:traffic,weather,vehicle_breakdown,address_issue,customer_unavailable,customs_delay,port_congestion,documentation_issue,mechanical_failure,road_closure,other',
        'delay_description' => 'nullable|string|max:1000',
        'delay_hours' => 'required|integer|min:1',
        'new_delivery_date' => 'required|date|after:now',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        DB::beginTransaction();

        // Record the delay using the Shipment model method
        $delay = $shipment->recordDelay([
            'driver_id' => $shipment->assigned_driver_id,
            'delay_reason' => $request->delay_reason,
            'delay_description' => $request->delay_description,
            'delay_hours' => $request->delay_hours,
            'delay_duration_minutes' => $request->delay_hours * 60,
            'delayed_at' => now(),
            'original_delivery_date' => $shipment->expected_delivery_date,
            'new_delivery_date' => $request->new_delivery_date,
            'reported_by' => auth()->id(),
        ]);

        // Create notification for customer
        Notification::create([
            'user_id' => $shipment->customer_id,
            'shipment_id' => $shipment->id,
            'title' => 'Shipment Delay Notification',
            'message' => "Your shipment {$shipment->tracking_number} has been delayed by {$request->delay_hours} hours. New expected delivery: " . Carbon::parse($request->new_delivery_date)->format('M d, Y'),
            'type' => 'warning',
            'channel' => 'system',
            'data' => json_encode([
                'delay_id' => $delay->id,
                'delay_hours' => $request->delay_hours,
                'reason' => $request->delay_reason,
            ]),
            'action_url' => route('admin.shipments.show', $shipment->id),
        ]);

        // Notify driver if assigned
        if ($shipment->assigned_driver_id) {
            Notification::create([
                'user_id' => $shipment->assigned_driver_id,
                'shipment_id' => $shipment->id,
                'title' => 'Delay Recorded',
                'message' => "A delay has been recorded for shipment {$shipment->tracking_number}. Please update customer on delivery status.",
                'type' => 'info',
                'channel' => 'system',
                'action_url' => route('admin.shipments.show', $shipment->id),
            ]);
        }

        DB::commit();

        return redirect()->back()->with('success', 'Delay recorded successfully! ' . 
            ($delay->is_critical ? 'This is a critical delay and has been automatically escalated to management.' : ''));

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to record delay: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to record delay. Please try again.')
            ->withInput();
    }
}

public function getShipmentDelays(Shipment $shipment)
{
    try {
        $delays = $shipment->delays()
            ->with(['driver', 'reportedBy'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'delays' => $delays->map(function($delay) {
                return [
                    'id' => $delay->id,
                    'reason' => ucfirst(str_replace('_', ' ', $delay->delay_reason)),
                    'description' => $delay->delay_description,
                    'hours' => $delay->delay_hours,
                    'severity' => $delay->severity,
                    'is_resolved' => $delay->is_resolved,
                    'delayed_at' => $delay->delayed_at->format('M d, Y h:i A'),
                    'resolved_at' => $delay->resolved_at ? $delay->resolved_at->format('M d, Y h:i A') : null,
                    'reported_by' => $delay->reportedBy ? $delay->reportedBy->first_name . ' ' . $delay->reportedBy->last_name : 'System',
                ];
            }),
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to get shipment delays: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to load delays.',
        ], 500);
    }
}

public function index(Request $request)
{
    $query = ShipmentDelay::with([
        'shipment.customer',
        'shipment.carrier',
        'shipment.assignedDriver',
        'driver',
        'reportedBy'
    ])->whereNull('resolved_at');

    // Filter by severity
    if ($request->filled('severity') && $request->severity !== 'all') {
        $query->bySeverity($request->severity);
    }

    // Filter by carrier
    if ($request->filled('carrier') && $request->carrier !== 'all') {
        $query->byCarrier($request->carrier);
    }

    // Search by tracking number or customer
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('shipment', function($q) use ($search) {
            $q->where('tracking_number', 'like', "%{$search}%")
              ->orWhereHas('customer', function($q2) use ($search) {
                  $q2->where('first_name', 'like', "%{$search}%")
                     ->orWhere('last_name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    // Order by severity (critical first) and delay hours
    $query->orderByDesc('delay_hours');

    // Paginate results (15 per page by default, customizable via per_page parameter)
    $delays = $query->paginate($request->get('per_page', 15))->withQueryString();

    // Calculate statistics (using all records, not just paginated ones)
    $allDelays = ShipmentDelay::with('shipment')
        ->whereNull('resolved_at')
        ->get();

    $stats = [
        'total_delayed' => $allDelays->count(),
        'critical_delays' => $allDelays->where('severity', 'critical')->count(),
        'avg_delay_hours' => $allDelays->avg('delay_hours') ?? 0,
        'total_value_affected' => $allDelays->sum(function($delay) {
            return $delay->shipment->total_value ?? 0;
        }),
    ];

    // Get carriers for filter
    $carriers = Carriers::where('status', 'active')->get();

    return view('backend.shipments.delayed', compact('delays', 'stats', 'carriers'));
}

public function show(ShipmentDelay $delay)
{
    $delay->load([
        'shipment.customer',
        'shipment.carrier',
        'shipment.assignedDriver',
        'shipment.trackingHistory',
        'driver',
        'reportedBy'
    ]);

    return view('admin.delayed-shipments.show', compact('delay'));
}

public function getDetails(ShipmentDelay $delay)
{
    $delay->load(['shipment.customer', 'shipment.carrier', 'driver']);

    return response()->json([
        'success' => true,
        'delay' => [
            'id' => $delay->id,
            'tracking_number' => $delay->shipment->tracking_number,
            'customer' => $delay->shipment->customer->first_name . ' ' . $delay->shipment->customer->last_name,
            'email' => $delay->shipment->customer->email,
            'phone' => $delay->shipment->customer->phone ?? 'N/A',
            'origin' => $delay->shipment->pickup_city . ', ' . $delay->shipment->pickup_state,
            'destination' => $delay->shipment->delivery_city . ', ' . $delay->shipment->delivery_state,
            'carrier' => $delay->shipment->carrier->name ?? 'N/A',
            'value' => '$' . number_format($delay->shipment->total_value, 0),
            'delay' => $delay->delay_hours . ' hours',
            'severity' => ucfirst($delay->severity),
            'cause' => ucfirst(str_replace('_', ' ', $delay->delay_reason)),
        ],
    ]);
}

public function startResolution(Request $request, ShipmentDelay $delay)
{
    $request->validate([
        'resolution_type' => 'required|in:reroute,expedite,replacement,refund,escalate',
        'priority' => 'required|in:low,medium,high,urgent',
        'notes' => 'nullable|string',
    ]);

    try {
        DB::beginTransaction();

        // Update delay with resolution info
        $delay->update([
            'delay_description' => $delay->delay_description . "\n\nResolution Started:\nType: {$request->resolution_type}\nPriority: {$request->priority}\nNotes: {$request->notes}",
        ]);

        // Create notification for driver
        if ($delay->driver_id) {
            Notification::create([
                'user_id' => $delay->driver_id,
                'shipment_id' => $delay->shipment_id,
                'title' => 'Delay Resolution Started',
                'message' => "Resolution process started for shipment {$delay->shipment->tracking_number}. Type: {$request->resolution_type}",
                'type' => 'info',
                'channel' => 'system',
                'action_url' => route('admin.shipments.show', $delay->shipment_id),
            ]);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'resolution_started',
            'model_type' => 'ShipmentDelay',
            'model_id' => $delay->id,
            'description' => "Resolution started for shipment {$delay->shipment->tracking_number}",
            'new_values' => [
                'resolution_type' => $request->resolution_type,
                'priority' => $request->priority,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Resolution process started successfully!',
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Resolution start failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to start resolution process.',
        ], 500);
    }
}

public function contactCustomer(Request $request, ShipmentDelay $delay)
{
    $request->validate([
        'contact_method' => 'required|in:email,phone,sms',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    try {
        $customer = $delay->shipment->customer;

        // Create notification
        Notification::create([
            'user_id' => $customer->id,
            'shipment_id' => $delay->shipment_id,
            'title' => $request->subject,
            'message' => $request->message,
            'type' => 'shipment_update',
            'channel' => $request->contact_method === 'email' ? 'email' : 'system',
            'action_url' => route('admin.shipments.show', $delay->shipment_id),
        ]);

        // Mark as customer notified
        $delay->update(['customer_notified' => true]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'customer_contacted',
            'model_type' => 'ShipmentDelay',
            'model_id' => $delay->id,
            'description' => "Customer contacted for shipment {$delay->shipment->tracking_number} via {$request->contact_method}",
            'new_values' => [
                'contact_method' => $request->contact_method,
                'subject' => $request->subject,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Message sent successfully via {$request->contact_method}!",
        ]);

    } catch (\Exception $e) {
        Log::error('Customer contact failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to send message.',
        ], 500);
    }
}

public function resolve(Request $request, ShipmentDelay $delay)
{
    $request->validate([
        'notes' => 'nullable|string',
    ]);

    try {
        $delay->resolve($request->notes);

        return response()->json([
            'success' => true,
            'message' => 'Delay resolved successfully!',
        ]);

    } catch (\Exception $e) {
        Log::error('Delay resolution failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to resolve delay.',
        ], 500);
    }
}

public function export(Request $request, $format = 'csv')
{
    $query = ShipmentDelay::with(['shipment.customer', 'shipment.carrier'])
        ->whereNull('resolved_at');

    if ($request->filled('severity') && $request->severity !== 'all') {
        $query->bySeverity($request->severity);
    }

    if ($request->filled('carrier') && $request->carrier !== 'all') {
        $query->byCarrier($request->carrier);
    }

    $delays = $query->orderByDesc('delay_hours')->get();

    switch ($format) {
        case 'csv':
            return $this->exportCsv($delays);
        case 'excel':
            return $this->exportExcel($delays);
        case 'pdf':
            return $this->exportPdf($delays);
        default:
            return redirect()->back()->with('error', 'Invalid export format');
    }
}

private function exportCsv($delays)
{
    $filename = 'delayed-shipments-' . date('Y-m-d') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($delays) {
        $file = fopen('php://output', 'w');
        
        // Headers
        fputcsv($file, [
            'Tracking Number',
            'Customer',
            'Origin',
            'Destination',
            'Carrier',
            'Delay Hours',
            'Severity',
            'Reason',
            'Value',
            'Delayed At',
        ]);

        // Data
        foreach ($delays as $delay) {
            fputcsv($file, [
                $delay->shipment->tracking_number,
                $delay->shipment->customer->first_name . ' ' . $delay->shipment->customer->last_name,
                $delay->shipment->pickup_city . ', ' . $delay->shipment->pickup_state,
                $delay->shipment->delivery_city . ', ' . $delay->shipment->delivery_state,
                $delay->shipment->carrier->name ?? 'N/A',
                $delay->delay_hours,
                $delay->severity,
                str_replace('_', ' ', $delay->delay_reason),
                '$' . number_format($delay->shipment->total_value, 2),
                $delay->delayed_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

private function exportExcel($delays)
{
    // For simplicity, using CSV format
    // You can integrate Laravel Excel package for true Excel format
    return $this->exportCsv($delays);
}

private function exportPdf($delays)
{
    // You can integrate dompdf or similar for PDF generation
    return redirect()->back()->with('info', 'PDF export coming soon!');
}








/////shipment tracking


    public function trackindex()
{
    // Get recent tracking numbers from cookie
    $recentTracking = json_decode(Cookie::get('recent_tracking', '[]'), true);
    
    // Initialize shipment as null
    $shipment = null;
    $progress = 0;
    $currentLocation = null;
    $carrierSupport = null;
    
    return view('backend.shipments.track', compact(
        'recentTracking',
        'shipment',
        'progress',
        'currentLocation',
        'carrierSupport'
    ));
}

public function tracksearch(Request $request)
{
    $request->validate([
        'tracking_number' => 'required|string|max:50'
    ]);

    $trackingNumber = strtoupper(trim($request->tracking_number));

    // Store in recent tracking (cookie)
    $recentTracking = json_decode(Cookie::get('recent_tracking', '[]'), true);
    
    if (!in_array($trackingNumber, $recentTracking)) {
        array_unshift($recentTracking, $trackingNumber);
        $recentTracking = array_slice($recentTracking, 0, 5); // Keep only last 5
    }

    Cookie::queue('recent_tracking', json_encode($recentTracking), 43200); // 30 days

    // Redirect to show method which will display on same page
    return redirect()->route('admin.shipment.track.show', $trackingNumber);
}

public function trackshow($tracking_number)
{
    $trackingNumber = strtoupper(trim($tracking_number));
    
    $shipment = Shipment::where('tracking_number', $trackingNumber)
        ->with([
            'trackingHistory' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'trackingHistory.branch',
            'trackingHistory.warehouse',
            'trackingHistory.hub',
            'trackingHistory.updatedBy',
            'carrier',
            'currentBranch',
            'currentWarehouse',
            'currentHub',
            'customer'
        ])
        ->first();

    if (!$shipment) {
        return redirect()->route('admin.shipment.track.index')
            ->with('error', 'Tracking number not found. Please check and try again.');
    }

    // Get recent tracking numbers from cookie
    $recentTracking = json_decode(Cookie::get('recent_tracking', '[]'), true);

    // Calculate progress percentage
    $progress = $shipment->progress;

    // Get current location
    $currentLocation = $this->getCurrentLocation($shipment);

    // Get carrier support info
    $carrierSupport = $this->getCarrierSupport($shipment);

    // Log the tracking view
    ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'tracking_viewed',
        'model_type' => 'Shipment',
        'model_id' => $shipment->id,
        'description' => "Tracking details viewed for: {$trackingNumber}",
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    // Return same view but with shipment data
    return view('backend.shipments.track', compact(
        'shipment',
        'recentTracking',
        'progress',
        'currentLocation',
        'carrierSupport'
    ));
}

public function trackreportIssue(Request $request, $tracking_number)
    {
        $request->validate([
            'issue_type' => 'required|string',
            'description' => 'required|string|max:1000',
        ]);

        $trackingNumber = strtoupper(trim($tracking_number));
        
        $shipment = Shipment::where('tracking_number', $trackingNumber)->firstOrFail();

        // Create the issue record in shipment_issues table
        $issue = ShipmentIssue::create([
            'shipment_id' => $shipment->id,
            'reported_by' => auth()->id(), // null if guest user
            'issue_type' => $request->issue_type,
            'description' => $request->description,
            'status' => 'pending',
            'priority' => $this->determinePriority($request->issue_type),
            'reporter_ip' => $request->ip(),
            'reporter_user_agent' => $request->userAgent(),
            'metadata' => [
                'tracking_number' => $trackingNumber,
                'shipment_status' => $shipment->status,
                'reported_at' => now()->toDateTimeString(),
            ],
        ]);

        // Create notification for admin/support team
        Notification::create([
            'user_id' => null, // System notification for admins
            'shipment_id' => $shipment->id,
            'title' => 'New Issue Reported',
            'message' => "Issue Type: {$request->issue_type} for shipment {$trackingNumber}",
            'type' => 'error',
            'channel' => 'system',
            'data' => json_encode([
                'issue_id' => $issue->id,
                'issue_type' => $request->issue_type,
                'priority' => $issue->priority,
                'tracking_number' => $trackingNumber
            ]),
        ]);

        // Log the issue report
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'issue_reported',
            'model_type' => 'ShipmentIssue',
            'model_id' => $issue->id,
            'description' => "Issue reported for shipment {$trackingNumber}: {$request->issue_type}",
            'new_values' => json_encode([
                'issue_id' => $issue->id,
                'issue_type' => $request->issue_type,
                'description' => $request->description,
                'priority' => $issue->priority,
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Notify customer if logged in
        if ($shipment->customer_id) {
            Notification::create([
                'user_id' => $shipment->customer_id,
                'shipment_id' => $shipment->id,
                'title' => 'Issue Report Received',
                'message' => "We have received your issue report for shipment {$trackingNumber}. Our team will investigate and contact you shortly.",
                'type' => 'info',
                'channel' => 'system',
                'data' => json_encode([
                    'issue_id' => $issue->id,
                ]),
            ]);
        }

        return redirect()->route('admin.shipment.track.show', $trackingNumber)
            ->with('success', 'Issue reported successfully. Our support team will contact you within 24-48 hours.');
    }

    /**
     * Determine priority based on issue type
     */
    private function determinePriority($issueType)
    {
        $criticalIssues = ['lost'];
        $highPriority = ['damaged', 'missing_items'];
        $mediumPriority = ['delayed', 'wrong_address', 'incorrect_tracking'];
        
        $issueTypeLower = strtolower($issueType);
        
        if (in_array($issueTypeLower, $criticalIssues)) {
            return 'critical';
        } elseif (in_array($issueTypeLower, $highPriority)) {
            return 'high';
        } elseif (in_array($issueTypeLower, $mediumPriority)) {
            return 'medium';
        }
        
        return 'low';
    }
    private function getCurrentLocation($shipment)
    {
        if ($shipment->currentHub) {
            return $shipment->currentHub->name . ', ' . $shipment->currentHub->city;
        }
        
        if ($shipment->currentBranch) {
            return $shipment->currentBranch->name . ', ' . $shipment->currentBranch->city;
        }


        if ($shipment->currentWarehouse) {
            return $shipment->currentWarehouse->name . ', ' . $shipment->currentWarehouse->city;
        }

        $latestTracking = $shipment->trackingHistory->first();
        if ($latestTracking && $latestTracking->location) {
            return $latestTracking->location;
        }

        return 'Location not available';
    }

    private function getCarrierSupport($shipment)
    {
        if ($shipment->carrier) {
            return [
                'name' => $shipment->carrier->name,
                'phone' => $shipment->carrier->phone ?? '+1 (800) 555-1234',
                'email' => $shipment->carrier->email ?? 'support@carrier.com',
            ];
        }

        return [
            'name' => 'FastFreight Logistics',
            'phone' => '+1 (800) 555-1234',
            'email' => 'support@fastfreight.com',
        ];
    }








    ///////////delivery schedule

public function scheduleIndex(Request $request)
{
    $query = Shipment::with(['customer', 'assignedDriver', 'carrier'])
        ->whereIn('status', ['draft', 'pending', 'picked_up', 'in_transit', 'out_for_delivery']);

    // Tab filtering
    $tab = $request->get('tab', 'all');
    
    switch ($tab) {
        case 'today':
            $query->whereDate('pickup_date', today());
            break;
        case 'tomorrow':
            $query->whereDate('pickup_date', today()->addDay());
            break;
        case 'week':
            $query->whereBetween('pickup_date', [now()->startOfWeek(), now()->endOfWeek()]);
            break;
    }

    // Apply other filters
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('tracking_number', 'like', "%{$search}%")
              ->orWhere('delivery_address', 'like', "%{$search}%")
              ->orWhereHas('customer', function($q2) use ($search) {
                  $q2->where('first_name', 'like', "%{$search}%")
                     ->orWhere('last_name', 'like', "%{$search}%");
              });
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('priority')) {
        $query->where('delivery_priority', $request->priority);
    }

    if ($request->filled('type')) {
        $query->where('shipment_type', $request->type);
    }

    $shipments = $query->orderBy('pickup_date', 'asc')->paginate(20);

    // Calculate statistics (same as before)
    $today = now()->toDateString();
    $weekStart = now()->startOfWeek()->toDateString();
    $weekEnd = now()->endOfWeek()->toDateString();

    $stats = [
        'today_deliveries' => Shipment::whereDate('pickup_date', $today)->count(),
        'today_on_schedule' => Shipment::whereDate('pickup_date', $today)
            ->whereIn('status', ['pending', 'picked_up', 'in_transit'])
            ->count(),
        'today_delayed' => ShipmentDelay::whereDate('delayed_at', $today)
            ->whereNull('resolved_at')
            ->count(),
        'week_deliveries' => Shipment::whereBetween('pickup_date', [$weekStart, $weekEnd])->count(),
        'week_growth' => 12,
        'on_time_rate' => $this->calculateOnTimeRate(),
        'avg_delivery_time' => $this->calculateAverageDeliveryTime(),
        'time_improvement' => 3,
    ];

    $customers = User::where('role', 'customer')->where('status', 'active')->get();
    $drivers = User::where('role', 'driver')->where('status', 'active')->get();
    $carriers = Carriers::where('status', 'active')->get();

    return view('backend.orders.scheduled', compact('shipments', 'stats', 'customers', 'drivers', 'carriers'));
}

private function calculateOnTimeRate()
{
    $totalDelivered = Shipment::where('status', 'delivered')
        ->whereMonth('actual_delivery_date', now()->month)
        ->count();

    if ($totalDelivered === 0) {
        return 0;
    }

    $onTime = Shipment::where('status', 'delivered')
        ->whereMonth('actual_delivery_date', now()->month)
        ->whereRaw('actual_delivery_date <= expected_delivery_date')
        ->count();

    return round(($onTime / $totalDelivered) * 100, 1);
}

private function calculateAverageDeliveryTime()
{
    $deliveries = Shipment::where('status', 'delivered')
        ->whereMonth('actual_delivery_date', now()->month)
        ->whereNotNull('pickup_date')
        ->whereNotNull('actual_delivery_date')
        ->get();

    if ($deliveries->isEmpty()) {
        return 0;
    }

    $totalMinutes = 0;
    $count = 0;

    foreach ($deliveries as $delivery) {
        $pickupTime = $delivery->pickup_date;
        $deliveryTime = $delivery->actual_delivery_date;
        
        if ($pickupTime && $deliveryTime) {
            $diff = $pickupTime->diffInMinutes($deliveryTime);
            $totalMinutes += $diff;
            $count++;
        }
    }

    return $count > 0 ? round($totalMinutes / $count) : 0;
}

public function rescheduleDelivery(Request $request, Shipment $shipment)
{
    $request->validate([
        'new_delivery_date' => 'required|date|after:today',
        'new_time_slot' => 'required|string',
        'reschedule_reason' => 'required|string|max:1000',
    ]);

    try {
        DB::beginTransaction();

        $oldDate = $shipment->pickup_date;
        $oldExpectedDate = $shipment->expected_delivery_date;

        // Parse time slot
        $timeSlot = explode('-', $request->new_time_slot);
        $pickupTime = $timeSlot[0] ?? '09:00';
        $deliveryTime = $timeSlot[1] ?? '11:00';

        // Update shipment dates
        $shipment->update([
            'pickup_date' => $request->new_delivery_date . ' ' . $pickupTime,
            'expected_delivery_date' => $request->new_delivery_date . ' ' . $deliveryTime,
            'pickup_scheduled_date' => $request->new_delivery_date . ' ' . $pickupTime,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'rescheduled',
            'model_type' => 'Shipment',
            'model_id' => $shipment->id,
            'description' => "Rescheduled shipment {$shipment->tracking_number}",
            'old_values' => [
                'pickup_date' => $oldDate,
                'expected_delivery_date' => $oldExpectedDate,
            ],
            'new_values' => [
                'pickup_date' => $shipment->pickup_date,
                'expected_delivery_date' => $shipment->expected_delivery_date,
                'reason' => $request->reschedule_reason,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Notify customer
        if ($shipment->customer_id) {
            Notification::create([
                'user_id' => $shipment->customer_id,
                'shipment_id' => $shipment->id,
                'title' => 'Delivery Rescheduled',
                'message' => "Your delivery has been rescheduled to {$request->new_delivery_date}. Reason: {$request->reschedule_reason}",
                'type' => 'warning',
                'channel' => 'system',
                'action_url' => route('admin.shipments.show', $shipment->id),
            ]);
        }

        // Notify driver if assigned
        if ($shipment->assigned_driver_id) {
            Notification::create([
                'user_id' => $shipment->assigned_driver_id,
                'shipment_id' => $shipment->id,
                'title' => 'Delivery Rescheduled',
                'message' => "Delivery {$shipment->tracking_number} has been rescheduled to {$request->new_delivery_date}",
                'type' => 'info',
                'channel' => 'system',
                'action_url' => route('admin.shipments.show', $shipment->id),
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Delivery rescheduled successfully!',
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Reschedule failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to reschedule delivery: ' . $e->getMessage(),
        ], 500);
    }
}

public function assignDriverToShipment(Request $request, Shipment $shipment)
{
    $request->validate([
        'driver_id' => 'required|exists:users,id',
        'assignment_notes' => 'nullable|string|max:1000',
    ]);

    try {
        DB::beginTransaction();

        $driver = User::findOrFail($request->driver_id);

        // Check if driver is available
        if ($driver->role !== 'driver' || $driver->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Selected user is not an active driver',
            ], 400);
        }

        $oldDriverId = $shipment->assigned_driver_id;

        // Assign driver
        $shipment->update([
            'assigned_driver_id' => $request->driver_id,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'driver_assigned',
            'model_type' => 'Shipment',
            'model_id' => $shipment->id,
            'description' => "Assigned driver {$driver->first_name} {$driver->last_name} to shipment {$shipment->tracking_number}",
            'old_values' => ['driver_id' => $oldDriverId],
            'new_values' => [
                'driver_id' => $request->driver_id,
                'notes' => $request->assignment_notes,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Notify driver
        Notification::create([
            'user_id' => $request->driver_id,
            'shipment_id' => $shipment->id,
            'title' => 'New Delivery Assignment',
            'message' => "You have been assigned to delivery {$shipment->tracking_number}. " . 
                        ($request->assignment_notes ? "Note: {$request->assignment_notes}" : ''),
            'type' => 'info',
            'channel' => 'system',
            'action_url' => route('admin.shipments.show', $shipment->id),
        ]);

        // Notify customer
        if ($shipment->customer_id) {
            Notification::create([
                'user_id' => $shipment->customer_id,
                'shipment_id' => $shipment->id,
                'title' => 'Driver Assigned',
                'message' => "Driver {$driver->first_name} {$driver->last_name} has been assigned to your delivery",
                'type' => 'info',
                'channel' => 'system',
                'action_url' => route('admin.shipments.show', $shipment->id),
            ]);
        }

        // Notify previous driver if changed
        if ($oldDriverId && $oldDriverId !== $request->driver_id) {
            Notification::create([
                'user_id' => $oldDriverId,
                'shipment_id' => $shipment->id,
                'title' => 'Delivery Reassigned',
                'message' => "Delivery {$shipment->tracking_number} has been reassigned to another driver",
                'type' => 'warning',
                'channel' => 'system',
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Driver assigned successfully!',
            'driver' => [
                'name' => $driver->first_name . ' ' . $driver->last_name,
                'id' => 'DRV-' . str_pad($driver->id, 3, '0', STR_PAD_LEFT),
            ],
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Driver assignment failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to assign driver: ' . $e->getMessage(),
        ], 500);
    }
}


public function getShipmentDetails(Shipment $shipment)
{
    $shipment->load(['customer', 'assignedDriver', 'carrier', 'shipmentItems']);
    
    return response()->json([
        'success' => true,
        'shipment' => [
            'id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'customer_name' => $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A',
            'customer_email' => $shipment->customer->email ?? 'N/A',
            'customer_phone' => $shipment->delivery_contact_phone ?? $shipment->pickup_contact_phone ?? 'N/A',
            'customer_initials' => $shipment->customer ? strtoupper(substr($shipment->customer->first_name, 0, 1) . substr($shipment->customer->last_name, 0, 1)) : 'NA',
            'pickup_date' => $shipment->pickup_date ? $shipment->pickup_date->format('Y-m-d') : 'N/A',
            'time_slot' => $shipment->pickup_date && $shipment->expected_delivery_date ? 
                $shipment->pickup_date->format('H:i') . ' - ' . $shipment->expected_delivery_date->format('H:i') : 'N/A',
            'status' => ucfirst(str_replace('_', ' ', $shipment->status)),
            'priority' => ucfirst($shipment->delivery_priority),
            'type' => ucfirst($shipment->shipment_type),
            'delivery_address' => $shipment->delivery_address . ', ' . $shipment->delivery_city . ', ' . $shipment->delivery_state . ' ' . $shipment->delivery_postal_code,
            'driver_name' => $shipment->assignedDriver ? $shipment->assignedDriver->first_name . ' ' . $shipment->assignedDriver->last_name : 'Unassigned',
            'driver_id' => $shipment->assignedDriver ? 'DRV-' . str_pad($shipment->assignedDriver->id, 3, '0', STR_PAD_LEFT) : '',
            'driver_phone' => $shipment->assignedDriver->phone ?? '',
            'package_info' => $shipment->number_of_items . ' item(s), ' . $shipment->total_weight . ' kg',
            'total_value' => number_format($shipment->total_value, 2),
            'special_instructions' => $shipment->special_instructions,
            'created_at' => $shipment->created_at->format('M d, Y \a\t h:i A'),
            'driver_assigned_at' => $shipment->assignedDriver ? $shipment->updated_at->format('M d, Y \a\t h:i A') : null,
            'pending_since' => 'Scheduled for ' . ($shipment->pickup_date ? $shipment->pickup_date->format('Y-m-d') : 'N/A'),
        ]
    ]);
}









////////RETURN
    public function createReturn(Shipment $shipment)
{
    // Only delivered or completed shipments can be returned
    if (!in_array($shipment->status, ['delivered', 'completed'])) {
        return redirect()->back()
            ->with('error', 'Only delivered shipments can be returned.');
    }

    // Check if return already exists
    $existingReturn = ReturnModel::where('shipment_id', $shipment->id)->first();
    if ($existingReturn) {
        return redirect()->route('admin.returns.show', $existingReturn->id)
            ->with('info', 'A return already exists for this shipment.');
    }

    $shipment->load(['customer', 'shipmentItems', 'carrier']);

    return view('backend.shipments.create-return', compact('shipment'));
}

public function storeReturn(Request $request, Shipment $shipment)
{
    $request->validate([
        'return_reason' => 'nullable|in:defective_product,wrong_item_sent,changed_mind,damaged_in_transit,not_as_described,quality_issue,size_issue,other',
        'description' => 'nullable|string|max:1000',
        'customer_notes' => 'nullable|string|max:1000',
        'return_items' => 'nullable|array|min:1',
        'return_items.*' => 'integer|exists:shipment_items,id',
        'images.*' => 'nullable|image|max:5120',
    ]);

    try {
        DB::beginTransaction();

        // Calculate return value from selected items
        $returnValue = 0;
        $returnItems = [];
        
        if ($request->has('return_items')) {
            foreach ($request->return_items as $itemId) {
                $item = shipment_items::find($itemId);
                if ($item && $item->shipment_id == $shipment->id) {
                    $returnValue += $item->value * $item->quantity;
                    $returnItems[] = [
                        'id' => $item->id,
                        'description' => $item->description,
                        'category' => $item->category,
                        'quantity' => $item->quantity,
                        'weight' => $item->weight,
                        'value' => $item->value,
                        'sku' => $item->description,
                    ];
                }
            }
        }

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('returns', 'public');
                $images[] = $path;
            }
        }

        // Get customer info - handle both registered users and guest customers
        $customer = $shipment->customer;
        $orderCount = Shipment::where('customer_id', $customer->id)->count();
        $returnCount = ReturnModel::where('customer_id', $customer->id)->count();

        // Determine pickup contact name (sender info = pickup info)
        $pickupContactName = $shipment->pickup_contact_name ?? 
                            ($customer ? $customer->first_name . ' ' . $customer->last_name : 'N/A');

        // Create return
        $return = ReturnModel::create([
            'return_number' => ReturnModel::generateReturnNumber(),
            'order_number' => 'ORD-' . $shipment->tracking_number,
            'shipment_id' => $shipment->id,
            'customer_id' => $shipment->customer_id,
            'return_reason' => $request->return_reason,
            'pickup_contact_name' => $pickupContactName, // Sender/Pickup info
            'description' => $request->description,
            'customer_notes' => $request->customer_notes,
            'return_date' => now()->toDateString(),
            'request_date' => now()->toDateString(),
            'status' => 'pending_review',
            'warehouse' => optional($shipment->currentWarehouse)->name ?? optional($shipment->currentWarehouse)->name ?? 'Main Warehouse',
            'tracking_number' => null,
            'pickup_address' => $shipment->delivery_address . ', ' . 
                               $shipment->delivery_city . ', ' . 
                               $shipment->delivery_state . ' ' . 
                               $shipment->delivery_postal_code,
            'total_amount' => $shipment->total_amount, // FIXED: Added total_amount from shipment
            'return_value' => $returnValue,
            'refund_amount' => $shipment->total_amount, // Use total_amount for refund
            'refund_status' => 'pending',
            'items' => $returnItems,
            'attached_images' => $images,
            'customer_order_count' => $orderCount,
            'customer_return_count' => $returnCount,
            'customer_since' => $customer->created_at ? $customer->created_at->toDateString() : null,
        ]);

        // Update shipment status
        $shipment->update([
            'status' => 'returned',
        ]);

        // Create tracking history
        $shipment->trackingHistory()->create([
            'status' => 'return_requested',
            'location' => $shipment->delivery_city . ', ' . $shipment->delivery_state,
            'description' => "Return requested: " . ($return->formatted_return_reason ?? $return->return_reason),
            'updated_by' => auth()->id(),
        ]);

        // Notify customer
        Notification::create([
            'user_id' => $customer->id,
            'shipment_id' => $shipment->id,
            'title' => 'Return Request Received',
            'message' => "Your return request {$return->return_number} for shipment {$shipment->tracking_number} has been received and is under review.",
            'type' => 'info',
            'channel' => 'system',
            'action_url' => route('admin.returns.show', $return->id),
        ]);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'shipment_id' => $shipment->id,
                'title' => 'New Return Request',
                'message' => "New return request {$return->return_number} from {$pickupContactName} for shipment {$shipment->tracking_number}",
                'type' => 'warning',
                'channel' => 'system',
                'action_url' => route('admin.returns.show', $return->id),
            ]);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'return_created',
            'model_type' => 'Shipment',
            'model_id' => $shipment->id,
            'description' => "Created return request {$return->return_number} for shipment {$shipment->tracking_number}",
            'new_values' => $return->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        DB::commit();

        return redirect()->route('admin.returns.show', $return->id)
            ->with('success', 'Return request created successfully! Return ID: ' . $return->return_number);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Return creation from shipment failed: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to create return request: ' . $e->getMessage())
            ->withInput();
    }
}




public function rejectReturn(Request $request, ReturnModel $return)
{
    $request->validate([
        'rejection_reason' => 'required|string|max:500',
        'admin_notes' => 'nullable|string|max:1000',
    ]);

    try {
        DB::beginTransaction();

        // Get the shipment
        $shipment = $return->shipment;

        // Update return status
        $return->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        // Update shipment status back to 'delivered'
        // Since return was rejected, shipment should go back to delivered status
        $shipment->update([
            'status' => 'delivered',
        ]);

        // Create tracking history
        $shipment->trackingHistory()->create([
            'status' => 'return_rejected',
            'location' => $shipment->delivery_city . ', ' . $shipment->delivery_state,
            'description' => "Return request {$return->return_number} rejected: {$request->rejection_reason}",
            'updated_by' => auth()->id(),
        ]);

        // Notify customer
        Notification::create([
            'user_id' => $return->customer_id,
            'shipment_id' => $shipment->id,
            'title' => 'Return Request Rejected',
            'message' => "Your return request {$return->return_number} for shipment {$shipment->tracking_number} has been rejected. Reason: {$request->rejection_reason}",
            'type' => 'error',
            'channel' => 'system',
            'action_url' => route('admin.returns.show', $return->id),
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'return_rejected',
            'model_type' => 'Return',
            'model_id' => $return->id,
            'description' => "Rejected return request {$return->return_number} for shipment {$shipment->tracking_number}",
            'old_values' => ['status' => 'pending_review'],
            'new_values' => ['status' => 'rejected', 'rejection_reason' => $request->rejection_reason],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        DB::commit();

        return redirect()->route('admin.returns.show', $return->id)
            ->with('success', 'Return request has been rejected successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Return rejection failed: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to reject return request: ' . $e->getMessage());
    }
}




public function approveReturn(Request $request, ReturnModel $return)
{
    $request->validate([
        'admin_notes' => 'nullable|string|max:1000',
        'scheduled_pickup_date' => 'nullable|date|after_or_equal:today',
        'refund_method' => 'nullable|in:original_payment,store_credit,bank_transfer',
    ]);

    try {
        DB::beginTransaction();

        $shipment = $return->shipment;

        // Generate tracking number for return shipment
        $returnTrackingNumber = 'RET-' . strtoupper(Str::random(10));

        // Update return status
        $return->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'tracking_number' => $returnTrackingNumber,
            'scheduled_pickup_date' => $request->scheduled_pickup_date,
            'refund_method' => $request->refund_method ?? 'original_payment',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        // Shipment status remains 'returned' (or you can set to 'return_approved')
        $shipment->update([
            'status' => 'returned', // Keep as returned, or create new status 'return_approved'
        ]);

        // Create tracking history
        $shipment->trackingHistory()->create([
            'status' => 'return_approved',
            'location' => $shipment->delivery_city . ', ' . $shipment->delivery_state,
            'description' => "Return request {$return->return_number} approved. Pickup scheduled for " . 
                           ($request->scheduled_pickup_date ? Carbon::parse($request->scheduled_pickup_date)->format('M d, Y') : 'TBD'),
            'updated_by' => auth()->id(),
        ]);

        // Notify customer
        Notification::create([
            'user_id' => $return->customer_id,
            'shipment_id' => $shipment->id,
            'title' => 'Return Request Approved',
            'message' => "Your return request {$return->return_number} has been approved! " .
                        "Return tracking number: {$returnTrackingNumber}. " .
                        ($request->scheduled_pickup_date ? "Pickup scheduled for " . Carbon::parse($request->scheduled_pickup_date)->format('M d, Y') : "We'll contact you to schedule pickup."),
            'type' => 'success',
            'channel' => 'system',
            'action_url' => route('admin.returns.show', $return->id),
        ]);

        // Notify warehouse/logistics team
        $warehouseStaff = User::whereIn('role', ['admin', 'warehouse_manager'])->get();
        foreach ($warehouseStaff as $staff) {
            Notification::create([
                'user_id' => $staff->id,
                'shipment_id' => $shipment->id,
                'title' => 'Return Pickup Required',
                'message' => "Return {$return->return_number} approved. Schedule pickup from {$return->pickup_address}",
                'type' => 'info',
                'channel' => 'system',
                'action_url' => route('admin.returns.show', $return->id),
            ]);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'return_approved',
            'model_type' => 'Return',
            'model_id' => $return->id,
            'description' => "Approved return request {$return->return_number} for shipment {$shipment->tracking_number}",
            'old_values' => ['status' => 'pending_review'],
            'new_values' => [
                'status' => 'approved',
                'tracking_number' => $returnTrackingNumber,
                'approved_by' => auth()->id(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        DB::commit();

        return redirect()->route('admin.returns.show', $return->id)
            ->with('success', "Return request approved successfully! Return tracking: {$returnTrackingNumber}");

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Return approval failed: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Failed to approve return request: ' . $e->getMessage());
    }
}
}