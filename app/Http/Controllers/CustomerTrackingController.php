<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentIssue;
use App\Models\ReturnModel;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerTrackingController extends Controller
{
    public function index()
    {
        // Get recent tracking numbers from cookies (last 5 searches)
        $recentTracking = json_decode(Cookie::get('recent_tracking', '[]'), true);
        
        return view('customer.tracking.index', [
            'shipment' => null,
            'recentTracking' => array_slice($recentTracking, 0, 5),
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:50',
        ]);

        $trackingNumber = strtoupper(trim($request->tracking_number));
        
        // Store in recent tracking (cookie)
        $recentTracking = json_decode(Cookie::get('recent_tracking', '[]'), true);
        $recentTracking = array_unique(array_merge([$trackingNumber], $recentTracking));
        Cookie::queue('recent_tracking', json_encode(array_slice($recentTracking, 0, 5)), 43200); // 30 days

        return redirect()->route('tracking.show', $trackingNumber);
    }

    public function show($tracking_number)
    {
        $shipment = Shipment::with(['trackingHistory' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->where('tracking_number', strtoupper($tracking_number))
        ->first();

        if (!$shipment) {
            return redirect()->route('tracking.index')
                ->with('error', 'Shipment not found. Please check your tracking number and try again.');
        }

        // Calculate progress percentage
        $statusProgress = [
            'pending' => 10,
            'picked_up' => 30,
            'in_transit' => 65,
            'out_for_delivery' => 85,
            'delivered' => 100,
            'cancelled' => 0,
        ];
        
        $progress = $statusProgress[$shipment->status] ?? 0;

        // Get current location from latest tracking history
        $currentLocation = $shipment->trackingHistory->first()->location ?? 
                          $shipment->pickup_city . ', ' . $shipment->pickup_state;

        // Get recent tracking from cookies
        $recentTracking = json_decode(Cookie::get('recent_tracking', '[]'), true);

        return view('customer.tracking.index', [
            'shipment' => $shipment,
            'progress' => $progress,
            'currentLocation' => $currentLocation,
            'recentTracking' => array_slice($recentTracking, 0, 5),
            'carrierSupport' => [
                'name' => 'FastFreight Logistics',
                'phone' => '+1 (800) 555-1234',
                'email' => 'support@fastfreight.com',
            ],
        ]);
    }

    public function reportIssue(Request $request, $tracking_number)
    {
        $request->validate([
            'issue_type' => 'required|string|in:damaged,delayed,incorrect_tracking,lost,wrong_address,missing_items,poor_service,other',
            'description' => 'required|string|max:1000',
        ]);

        $shipment = Shipment::where('tracking_number', strtoupper($tracking_number))->first();

        if (!$shipment) {
            return redirect()->route('tracking.index')
                ->with('error', 'Shipment not found.');
        }

        // Create issue report (you'll need to create this model and migration)
        ShipmentIssue::create([
            'shipment_id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'issue_type' => $request->issue_type,
            'description' => $request->description,
            'status' => 'pending',
            'reported_at' => now(),
        ]);

        return redirect()->route('tracking.show', $tracking_number)
            ->with('success', 'Your issue has been reported successfully. Our team will contact you within 24-48 hours.');
    }

    public function requestReturn(Request $request, $tracking_number)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'return_reason' => 'required|string|in:defective_product,wrong_item_sent,changed_mind,damaged_in_transit,not_as_described,quality_issue,size_issue,other',
            'description' => 'required|string|max:1000',
        ]);

        $shipment = Shipment::where('tracking_number', strtoupper($tracking_number))->first();

        if (!$shipment) {
            return redirect()->route('tracking.index')
                ->with('error', 'Shipment not found.');
        }

        if ($shipment->status !== 'delivered') {
            return redirect()->route('tracking.show', $tracking_number)
                ->with('error', 'Return requests can only be made for delivered shipments.');
        }

        // Check if a return already exists for this shipment
        $existingReturn = ReturnModel::where('shipment_id', $shipment->id)->first();
        if ($existingReturn) {
            return redirect()->route('tracking.show', $tracking_number)
                ->with('error', 'A return request already exists for this shipment (Return #' . $existingReturn->return_number . ').');
        }

        try {
            DB::beginTransaction();

            $return = ReturnModel::create([
                'return_number' => ReturnModel::generateReturnNumber(),
                'order_number' => 'ORD-' . $shipment->tracking_number,
                'shipment_id' => $shipment->id,
                'customer_id' => $shipment->customer_id,
                'return_reason' => $request->return_reason,
                'pickup_contact_name' => $request->name,
                'description' => $request->description,
                'customer_notes' => 'Requested via tracking page. Phone: ' . $request->phone . ', Email: ' . $request->email,
                'return_date' => now()->toDateString(),
                'request_date' => now()->toDateString(),
                'status' => 'pending_review',
                'pickup_address' => $shipment->delivery_address . ', ' .
                                   $shipment->delivery_city . ', ' .
                                   $shipment->delivery_state . ' ' .
                                   $shipment->delivery_postal_code,
                'total_amount' => $shipment->total_amount,
                'return_value' => $shipment->total_value ?? 0,
                'refund_amount' => $shipment->total_amount,
                'refund_status' => 'pending',
                'items' => [],
            ]);

            // Update shipment status
            $shipment->update(['status' => 'returned']);

            // Create tracking history entry
            $shipment->trackingHistory()->create([
                'status' => 'return_requested',
                'location' => $shipment->delivery_city . ', ' . $shipment->delivery_state,
                'description' => 'Return requested by customer via tracking page: ' . ucfirst(str_replace('_', ' ', $request->return_reason)),
            ]);

            // Notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'shipment_id' => $shipment->id,
                    'title' => 'New Return Request (Tracking Page)',
                    'message' => "Return request {$return->return_number} from {$request->name} for shipment {$shipment->tracking_number}. Reason: " . ucfirst(str_replace('_', ' ', $request->return_reason)),
                    'type' => 'warning',
                    'channel' => 'system',
                    'action_url' => route('admin.returns.show', $return->id),
                ]);
            }

            DB::commit();

            return redirect()->route('tracking.show', $tracking_number)
                ->with('success', 'Your return request has been submitted successfully! Return ID: ' . $return->return_number . '. Our team will review it and contact you within 24-48 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return request from tracking page failed: ' . $e->getMessage());

            return redirect()->route('tracking.show', $tracking_number)
                ->with('error', 'Failed to submit return request. Please try again or contact our support team.');
        }
    }
}