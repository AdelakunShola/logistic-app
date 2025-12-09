<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

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
}