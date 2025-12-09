<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\ShipmentDelay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DriverDeliveryController extends Controller
{
    /**
     * Active Deliveries Page
     */
    public function activeDeliveries(Request $request)
    {
        $driverId = Auth::id();
        
        $query = Shipment::with(['customer', 'carrier', 'shipmentItems'])
            ->where('assigned_driver_id', $driverId)
            ->whereIn('status', ['pending', 'picked_up', 'in_transit', 'out_for_delivery']);
        
        // Apply filters
        $filters = [
            'search' => $request->input('search'),
            'priority' => $request->input('priority'),
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];
        
        if ($filters['search']) {
            $query->where(function($q) use ($filters) {
                $q->where('tracking_number', 'like', "%{$filters['search']}%")
                  ->orWhere('pickup_address', 'like', "%{$filters['search']}%")
                  ->orWhere('delivery_address', 'like', "%{$filters['search']}%")
                  ->orWhereHas('customer', function($q) use ($filters) {
                      $q->where('first_name', 'like', "%{$filters['search']}%")
                        ->orWhere('last_name', 'like', "%{$filters['search']}%");
                  });
            });
        }
        
        if ($filters['priority']) {
            $query->where('delivery_priority', $filters['priority']);
        }
        
        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }
        
        if ($filters['date_from']) {
            $query->whereDate('pickup_date', '>=', $filters['date_from']);
        }
        
        if ($filters['date_to']) {
            $query->whereDate('expected_delivery_date', '<=', $filters['date_to']);
        }
        
        // Sort
        $sortBy = $request->input('sort_by', 'expected_delivery_date');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        $shipments = $query->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Shipment::where('assigned_driver_id', $driverId)
                ->whereIn('status', ['pending', 'picked_up', 'in_transit', 'out_for_delivery'])
                ->count(),
            'pending' => Shipment::where('assigned_driver_id', $driverId)
                ->where('status', 'pending')
                ->count(),
            'in_transit' => Shipment::where('assigned_driver_id', $driverId)
                ->where('status', 'in_transit')
                ->count(),
            'out_for_delivery' => Shipment::where('assigned_driver_id', $driverId)
                ->where('status', 'out_for_delivery')
                ->count(),
            'urgent' => Shipment::where('assigned_driver_id', $driverId)
                ->whereIn('status', ['pending', 'picked_up', 'in_transit', 'out_for_delivery'])
                ->where('delivery_priority', 'overnight')
                ->count(),
        ];
        
        return view('driver.delivery.active-deliveries', compact('shipments', 'stats', 'filters'));
    }




    
    /**
     * Completed Deliveries Page
     */
    public function completedDeliveries(Request $request)
    {
        $driverId = Auth::id();
        
        $query = Shipment::with(['customer', 'carrier', 'shipmentItems'])
            ->where('assigned_driver_id', $driverId)
            ->where('status', 'delivered');
        
        // Apply filters
        $filters = [
            'search' => $request->input('search'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'rating' => $request->input('rating'),
        ];
        
        if ($filters['search']) {
            $query->where(function($q) use ($filters) {
                $q->where('tracking_number', 'like', "%{$filters['search']}%")
                  ->orWhere('delivery_address', 'like', "%{$filters['search']}%")
                  ->orWhereHas('customer', function($q) use ($filters) {
                      $q->where('first_name', 'like', "%{$filters['search']}%")
                        ->orWhere('last_name', 'like', "%{$filters['search']}%");
                  });
            });
        }
        
        if ($filters['date_from']) {
            $query->whereDate('actual_delivery_date', '>=', $filters['date_from']);
        }
        
        if ($filters['date_to']) {
            $query->whereDate('actual_delivery_date', '<=', $filters['date_to']);
        }
        
        // Sort
        $sortBy = $request->input('sort_by', 'actual_delivery_date');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $shipments = $query->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Shipment::where('assigned_driver_id', $driverId)
                ->where('status', 'delivered')
                ->count(),
            'today' => Shipment::where('assigned_driver_id', $driverId)
                ->where('status', 'delivered')
                ->whereDate('actual_delivery_date', Carbon::today())
                ->count(),
            'this_week' => Shipment::where('assigned_driver_id', $driverId)
                ->where('status', 'delivered')
                ->whereBetween('actual_delivery_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count(),
            'this_month' => Shipment::where('assigned_driver_id', $driverId)
                ->where('status', 'delivered')
                ->whereMonth('actual_delivery_date', Carbon::now()->month)
                ->whereYear('actual_delivery_date', Carbon::now()->year)
                ->count(),
            'on_time' => Shipment::where('assigned_driver_id', $driverId)
                ->where('status', 'delivered')
                ->whereColumn('actual_delivery_date', '<=', 'expected_delivery_date')
                ->count(),
        ];
        
        return view('driver.delivery.completed-deliveries', compact('shipments', 'stats', 'filters'));
    }
    
    /**
     * Delayed Deliveries Page
     */
    public function delayedDeliveries(Request $request)
    {
        $driverId = Auth::id();
        
        $query = Shipment::with(['customer', 'carrier', 'shipmentItems', 'delays' => function($q) {
                $q->whereNull('resolved_at')->latest();
            }])
            ->where('assigned_driver_id', $driverId)
            ->whereHas('delays', function($q) {
                $q->whereNull('resolved_at');
            });
        
        // Apply filters
        $filters = [
            'search' => $request->input('search'),
            'delay_reason' => $request->input('delay_reason'),
            'escalated' => $request->input('escalated'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];
        
        if ($filters['search']) {
            $query->where(function($q) use ($filters) {
                $q->where('tracking_number', 'like', "%{$filters['search']}%")
                  ->orWhere('delivery_address', 'like', "%{$filters['search']}%")
                  ->orWhereHas('customer', function($q) use ($filters) {
                      $q->where('first_name', 'like', "%{$filters['search']}%")
                        ->orWhere('last_name', 'like', "%{$filters['search']}%");
                  });
            });
        }
        
        if ($filters['delay_reason']) {
            $query->whereHas('delays', function($q) use ($filters) {
                $q->where('delay_reason', $filters['delay_reason'])
                  ->whereNull('resolved_at');
            });
        }
        
        if ($filters['escalated'] !== null) {
            $query->whereHas('delays', function($q) use ($filters) {
                $q->where('escalated', $filters['escalated'])
                  ->whereNull('resolved_at');
            });
        }
        
        if ($filters['date_from']) {
            $query->whereHas('delays', function($q) use ($filters) {
                $q->whereDate('delayed_at', '>=', $filters['date_from']);
            });
        }
        
        if ($filters['date_to']) {
            $query->whereHas('delays', function($q) use ($filters) {
                $q->whereDate('delayed_at', '<=', $filters['date_to']);
            });
        }
        
        // Sort
        $query->orderBy('expected_delivery_date', 'asc');
        
        $shipments = $query->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Shipment::where('assigned_driver_id', $driverId)
                ->whereHas('delays', function($q) {
                    $q->whereNull('resolved_at');
                })
                ->count(),
            'escalated' => Shipment::where('assigned_driver_id', $driverId)
                ->whereHas('delays', function($q) {
                    $q->where('escalated', true)->whereNull('resolved_at');
                })
                ->count(),
            'customer_notified' => Shipment::where('assigned_driver_id', $driverId)
                ->whereHas('delays', function($q) {
                    $q->where('customer_notified', true)->whereNull('resolved_at');
                })
                ->count(),
            'avg_delay_hours' => ShipmentDelay::whereHas('shipment', function($q) use ($driverId) {
                    $q->where('assigned_driver_id', $driverId);
                })
                ->whereNull('resolved_at')
                ->avg('delay_hours') ?? 0,
        ];
        
        return view('driver.delivery.delayed-deliveries', compact('shipments', 'stats', 'filters'));
    }
    
public function quickView(Shipment $shipment)
{
    if ($shipment->assigned_driver_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $shipment->load(['customer', 'carrier', 'shipmentItems', 'delays']);
    
    return response()->json([
        'success' => true,
        'shipment' => [
            'id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'status' => ucfirst(str_replace('_', ' ', $shipment->status)),
            'customer' => $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A',
            'pickup_address' => $shipment->pickup_address . ', ' . $shipment->pickup_city . ', ' . $shipment->pickup_state,
            'delivery_address' => $shipment->delivery_address . ', ' . $shipment->delivery_city . ', ' . $shipment->delivery_state,
            'expected_delivery' => $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('M d, Y h:i A') : 'N/A',
            'actual_delivery' => $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('M d, Y h:i A') : 'N/A',
            'priority' => ucfirst($shipment->delivery_priority),
            'items_count' => $shipment->number_of_items,
            'total_weight' => number_format($shipment->total_weight, 1) . ' lbs',
            'total_amount' => $shipment->total_amount,
            'special_instructions' => $shipment->special_instructions ?? 'None',
            'contact_phone' => $shipment->delivery_contact_phone,
            'contact_name' => $shipment->delivery_contact_name,
            // Payment information
            'payment_mode' => $shipment->payment_mode,
            'cod_amount' => $shipment->cod_amount,
            // Delivery proof
            'delivery_signature' => $shipment->delivery_signature ? Storage::url($shipment->delivery_signature) : null,
            'delivery_photo' => $shipment->delivery_photo ? Storage::url($shipment->delivery_photo) : null,
            'delivery_notes' => $shipment->delivery_notes,
            // Special services
            'insurance_required' => $shipment->insurance_required,
            'insurance_amount' => $shipment->insurance_amount,
            'signature_required' => $shipment->signature_required,
            'temperature_controlled' => $shipment->temperature_controlled,
            'fragile_handling' => $shipment->fragile_handling,
            // Shipment items
            'items' => $shipment->shipmentItems->map(function($item) {
                return [
                    'description' => $item->item_description,
                    'quantity' => $item->quantity,
                    'weight' => number_format($item->weight, 1),
                    'dimensions' => $item->dimensions,
                    'value' => $item->value ?? null,
                    'special_handling' => $item->special_handling ?? false,
                ];
            }),
        ]
    ]);
}
    
    /**
     * Update Delivery Status (AJAX)
     */
    public function updateStatus(Request $request, Shipment $shipment)
    {
        if ($shipment->assigned_driver_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'status' => 'required|in:picked_up,in_transit,out_for_delivery',
            'notes' => 'nullable|string|max:500',
        ]);
        
        try {
            $shipment->update([
                'status' => $request->status,
            ]);
            
            $shipment->logActivity('status_updated', "Status updated to {$request->status} by driver", [
                'old_status' => $shipment->getOriginal('status'),
                'new_status' => $request->status,
                'notes' => $request->notes,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Report Delay (AJAX)
     */
    public function reportDelay(Request $request, Shipment $shipment)
    {
        if ($shipment->assigned_driver_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'delay_reason' => 'required|in:traffic_congestion,weather_conditions,vehicle_issues,address_issues,customer_unavailable,customs_delay,port_congestion,documentation_issues,mechanical_failure,road_closure,other',
            'delay_description' => 'required|string|max:1000',
            'delay_hours' => 'required|integer|min:1|max:168',
            'notify_customer' => 'boolean',
        ]);
        
        try {
            DB::beginTransaction();
            
            $newDeliveryDate = $shipment->expected_delivery_date 
                ? Carbon::parse($shipment->expected_delivery_date)->addHours($request->delay_hours)
                : null;
            
            $delay = ShipmentDelay::create([
                'shipment_id' => $shipment->id,
                'driver_id' => Auth::id(),
                'delay_reason' => $request->delay_reason,
                'delay_description' => $request->delay_description,
                'delay_hours' => $request->delay_hours,
                'delayed_at' => now(),
                'original_delivery_date' => $shipment->expected_delivery_date,
                'new_delivery_date' => $newDeliveryDate,
                'reported_by' => Auth::id(),
                'customer_notified' => $request->notify_customer ?? false,
                'customer_notified_at' => $request->notify_customer ? now() : null,
            ]);
            
            // Update shipment expected delivery date
            if ($newDeliveryDate) {
                $shipment->update([
                    'expected_delivery_date' => $newDeliveryDate,
                ]);
            }
            
            $shipment->logActivity('delay_reported', 'Delivery delay reported', [
                'reason' => $request->delay_reason,
                'hours' => $request->delay_hours,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Delay reported successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to report delay: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Resolve Delay (AJAX)
     */
    public function resolveDelay(Request $request, Shipment $shipment)
    {
        if ($shipment->assigned_driver_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'resolution_notes' => 'required|string|max:1000',
        ]);
        
        try {
            $delay = $shipment->delays()->whereNull('resolved_at')->latest()->first();
            
            if (!$delay) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active delay found for this shipment.',
                ], 404);
            }
            
            $delay->update([
                'resolved_at' => now(),
                'resolution_notes' => $request->resolution_notes,
            ]);
            
            $shipment->logActivity('delay_resolved', 'Delivery delay resolved', [
                'delay_reason' => $delay->delay_reason,
                'resolution' => $request->resolution_notes,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Delay resolved successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resolve delay: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Complete Delivery (AJAX)
     */
    public function completeDelivery(Request $request, Shipment $shipment)
{
    if ($shipment->assigned_driver_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $request->validate([
        'signature' => 'nullable|string',
        'photo' => 'nullable|string',
        'notes' => 'nullable|string|max:500',
        'cod_collected' => 'boolean',
    ]);
    
    try {
        // Save signature as file
        $signaturePath = null;
        if ($request->signature) {
            $signatureImage = $request->signature;
            $signatureImage = str_replace('data:image/png;base64,', '', $signatureImage);
            $signatureImage = str_replace(' ', '+', $signatureImage);
            $signatureData = base64_decode($signatureImage);
            
            $signatureFileName = 'signature_' . $shipment->tracking_number . '_' . time() . '.png';
            $signaturePath = 'signatures/' . $signatureFileName;
            
            Storage::disk('public')->put($signaturePath, $signatureData);
        }
        
        // Save photo as file
        $photoPath = null;
        if ($request->photo) {
            $photoImage = $request->photo;
            
            // Detect image type
            if (strpos($photoImage, 'data:image/jpeg') !== false) {
                $photoImage = str_replace('data:image/jpeg;base64,', '', $photoImage);
                $extension = 'jpg';
            } elseif (strpos($photoImage, 'data:image/jpg') !== false) {
                $photoImage = str_replace('data:image/jpg;base64,', '', $photoImage);
                $extension = 'jpg';
            } else {
                $photoImage = str_replace('data:image/png;base64,', '', $photoImage);
                $extension = 'png';
            }
            
            $photoImage = str_replace(' ', '+', $photoImage);
            $photoData = base64_decode($photoImage);
            
            $photoFileName = 'delivery_' . $shipment->tracking_number . '_' . time() . '.' . $extension;
            $photoPath = 'delivery_photos/' . $photoFileName;
            
            Storage::disk('public')->put($photoPath, $photoData);
        }
        
        $shipment->update([
            'status' => 'delivered',
            'actual_delivery_date' => now(),
            'delivery_signature' => $signaturePath,
            'delivery_photo' => $photoPath,
            'delivery_notes' => $request->notes,
        ]);
        
        // Validate COD collection
        if ($shipment->payment_mode === 'cod' && !$request->cod_collected) {
            return response()->json([
                'success' => false,
                'message' => 'COD amount must be collected before completing delivery.',
            ], 400);
        }
        
        $shipment->logActivity('delivered', 'Shipment delivered by driver', [
            'delivered_at' => now()->toDateTimeString(),
            'notes' => $request->notes,
            'cod_collected' => $request->cod_collected ?? false,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Delivery completed successfully!',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to complete delivery: ' . $e->getMessage(),
        ], 500);
    }
}
    
    /**
     * Export Deliveries
     */
    public function export(Request $request, $type)
    {
        // Implementation similar to admin export
        // Filter by driver ID
        $driverId = Auth::id();
        
        // Add export logic here based on $type (csv, excel, pdf)
        
        return response()->download($filePath);
    }
}