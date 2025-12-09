<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentDelay;
use App\Models\User;
use App\Models\Carriers;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        // Get date ranges
        // Get date ranges
$today = now()->toDateString();
$yesterday = now()->subDay()->toDateString(); // CHANGED THIS
$startOfMonth = now()->startOfMonth();
$lastMonthStart = now()->subMonth()->startOfMonth();
$lastMonthEnd = now()->subMonth()->endOfMonth();
        
        // ========================================
        // MAIN STATISTICS CARDS
        // ========================================
        
        $activeShipments = Shipment::whereIn('status', ['picked_up', 'in_transit', 'out_for_delivery'])->count();
        $activeShipmentsLastMonth = Shipment::whereIn('status', ['picked_up', 'in_transit', 'out_for_delivery'])
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();
        $activeShipmentsGrowth = $activeShipmentsLastMonth > 0 
            ? round((($activeShipments - $activeShipmentsLastMonth) / $activeShipmentsLastMonth) * 100, 0)
            : ($activeShipments > 0 ? 100 : 0);
        
        $deliveredToday = Shipment::where('status', 'delivered')
    ->whereDate('actual_delivery_date', now()->toDateString())
    ->count();
        $deliveredYesterday = Shipment::where('status', 'delivered')
    ->whereDate('actual_delivery_date', now()->subDay()->toDateString())
    ->count();
        $deliveredTodayGrowth = $deliveredYesterday > 0 
            ? round((($deliveredToday - $deliveredYesterday) / $deliveredYesterday) * 100, 0)
            : ($deliveredToday > 0 ? 100 : 0);
        
        $pendingOrders = Shipment::where('status', 'pending')->count();
        $pendingOrdersLastWeek = Shipment::where('status', 'pending')
            ->whereDate('created_at', '>=', now()->subWeek())
            ->whereDate('created_at', '<', now())
            ->count();
        $pendingOrdersGrowth = $pendingOrdersLastWeek > 0 
            ? round((($pendingOrders - $pendingOrdersLastWeek) / $pendingOrdersLastWeek) * 100, 0)
            : 0;
        
        $revenueMTD = Shipment::whereDate('created_at', '>=', $startOfMonth)->sum('total_amount');
        $revenueLastMonth = Shipment::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('total_amount');
        $revenueGrowth = $revenueLastMonth > 0 
            ? round((($revenueMTD - $revenueLastMonth) / $revenueLastMonth) * 100, 0)
            : ($revenueMTD > 0 ? 100 : 0);
        
        // ========================================
        // PERFORMANCE METRICS
        // ========================================
        
        $totalDelivered = Shipment::where('status', 'delivered')
            ->whereMonth('actual_delivery_date', now()->month)
            ->count();
        $onTimeDeliveries = Shipment::where('status', 'delivered')
            ->whereMonth('actual_delivery_date', now()->month)
            ->whereRaw('actual_delivery_date <= expected_delivery_date')
            ->count();
        $onTimeRate = $totalDelivered > 0 
            ? round(($onTimeDeliveries / $totalDelivered) * 100, 1) 
            : 0;
        
        $avgDeliveryTime = Shipment::where('status', 'delivered')
            ->whereMonth('actual_delivery_date', now()->month)
            ->whereNotNull('pickup_date')
            ->whereNotNull('actual_delivery_date')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, pickup_date, actual_delivery_date)) as avg_hours')
            ->value('avg_hours') ?? 0;
        
        $delayedShipments = ShipmentDelay::whereNull('resolved_at')->count();
        $criticalDelays = ShipmentDelay::whereNull('resolved_at')
            ->where('delay_hours', '>=', 48)
            ->count();
        
        $activeDrivers = User::where('role', 'driver')
            ->where('status', 'active')
            ->where('is_available', true)
            ->count();
        $busyDrivers = User::where('role', 'driver')
            ->where('status', 'active')
            ->where('is_available', false)
            ->count();
        
        // ========================================
        // SHIPMENT TRENDS (Last 12 Months)
        // ========================================
        
        $monthlyTrends = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M');
            
            $totalShipments = Shipment::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $deliveredShipments = Shipment::whereYear('actual_delivery_date', $month->year)
                ->whereMonth('actual_delivery_date', $month->month)
                ->where('status', 'delivered')
                ->count();
            
            $monthlyTrends[] = [
                'month' => $monthName,
                'total' => $totalShipments,
                'delivered' => $deliveredShipments,
            ];
        }
        
        // ========================================
        // FLEET STATUS OVERVIEW
        // ========================================
        
        $totalVehicles = Vehicle::count();
        $activeVehicles = Vehicle::where('status', 'active')->count();
        $maintenanceVehicles = Vehicle::where('status', 'maintenance')->count();
        $availableVehicles = Vehicle::where('status', 'inactive')->count();
        
        $fleetEfficiency = $totalVehicles > 0 
            ? round((Vehicle::where('status', 'active')->avg('utilization_percentage') ?? 0), 0)
            : 0;
        
        // ========================================
// RECENT ACTIVITIES (Enhanced with detailed info)
// ========================================

$recentActivities = collect();

// Get recent shipment activities
$shipmentActivities = Shipment::with(['customer', 'assignedDriver', 'sender'])
    ->select('id', 'tracking_number', 'status', 'pickup_city', 'pickup_state', 
             'delivery_city', 'delivery_state', 'created_at', 'updated_at', 
             'customer_id', 'assigned_driver_id', 'sender_id', 'actual_delivery_date')
    ->orderBy('updated_at', 'desc')
    ->limit(10)
    ->get()
    ->map(function($shipment) {
        $action = '';
        $description = '';
        $icon = 'truck';
        $user = null;
        
        switch($shipment->status) {
            case 'draft':
            case 'pending':
                $action = 'New shipment created';
                $description = "Shipment #{$shipment->tracking_number} from {$shipment->pickup_city}, {$shipment->pickup_state} to {$shipment->delivery_city}, {$shipment->delivery_state}";
                $icon = 'truck';
                $user = $shipment->customer ?? $shipment->sender;
                break;
            case 'delivered':
                $action = 'Delivery completed';
                $description = "Package #{$shipment->tracking_number} delivered successfully to client";
                $icon = 'package';
                $user = $shipment->assignedDriver ?? $shipment->customer;
                break;
            case 'picked_up':
                $action = 'Package picked up';
                $description = "Package #{$shipment->tracking_number} picked up from {$shipment->pickup_city}";
                $icon = 'truck';
                $user = $shipment->assignedDriver;
                break;
            case 'in_transit':
                $action = 'Shipment in transit';
                $description = "Package #{$shipment->tracking_number} is on the way to {$shipment->delivery_city}";
                $icon = 'truck';
                $user = $shipment->assignedDriver;
                break;
            case 'out_for_delivery':
                $action = 'Out for delivery';
                $description = "Package #{$shipment->tracking_number} is out for delivery in {$shipment->delivery_city}";
                $icon = 'truck';
                $user = $shipment->assignedDriver;
                break;
            case 'failed':
                $action = 'Delivery failed';
                $description = "#{$shipment->tracking_number} delivery attempt failed";
                $icon = 'alert';
                $user = $shipment->assignedDriver;
                break;
            case 'returned':
                $action = 'Package returned';
                $description = "Package #{$shipment->tracking_number} returned to sender";
                $icon = 'alert';
                $user = $shipment->assignedDriver;
                break;
            case 'cancelled':
                $action = 'Shipment cancelled';
                $description = "Package #{$shipment->tracking_number} has been cancelled";
                $icon = 'x-circle';
                $user = $shipment->customer;
                break;
            default:
                $action = 'Shipment updated';
                $description = "Package #{$shipment->tracking_number} status changed";
                $icon = 'truck';
                $user = $shipment->customer;
        }
        
        return [
            'id' => $shipment->id,
            'model_type' => 'Shipment',
            'action' => $action,
            'description' => $description,
            'icon' => $icon,
            'user' => $user,
            'created_at' => $shipment->updated_at,
            'status' => $shipment->status
        ];
    });

// Get recent vehicle activities (if you have Vehicle model)
$vehicleActivities = collect();
if (class_exists('App\Models\Vehicle')) {
    $vehicleActivities = Vehicle::select('id', 'vehicle_number', 'status', 'created_at', 'updated_at')
        ->where('updated_at', '>=', now()->subDays(7))
        ->orderBy('updated_at', 'desc')
        ->limit(5)
        ->get()
        ->map(function($vehicle) {
            $action = '';
            $description = '';
            $icon = 'wrench';
            
            if ($vehicle->status === 'maintenance') {
                $action = 'Vehicle maintenance due';
                $description = "Truck {$vehicle->vehicle_number} requires scheduled maintenance";
                $icon = 'alert-triangle';
            } elseif ($vehicle->status === 'active') {
                $action = 'Vehicle activated';
                $description = "Vehicle {$vehicle->vehicle_number} is now active and available";
                $icon = 'truck';
            } elseif ($vehicle->status === 'inactive') {
                $action = 'Vehicle deactivated';
                $description = "Vehicle {$vehicle->vehicle_number} has been taken offline";
                $icon = 'x-circle';
            } else {
                $action = 'Route optimized';
                $description = "Delivery route updated for better efficiency";
                $icon = 'package';
            }
            
            return [
                'id' => $vehicle->id,
                'model_type' => 'Vehicle',
                'action' => $action,
                'description' => $description,
                'icon' => $icon,
                'user' => null, // You can add driver relationship if needed
                'created_at' => $vehicle->updated_at,
                'status' => $vehicle->status
            ];
        });
}

// Get recent delay notifications (if you have ShipmentDelay model)
$delayActivities = collect();
if (class_exists('App\Models\ShipmentDelay')) {
    $delayActivities = ShipmentDelay::with(['shipment.customer', 'shipment.assignedDriver'])
        ->select('id', 'shipment_id', 'delay_reason', 'delay_hours', 'created_at')
        ->whereNull('resolved_at')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get()
        ->map(function($delay) {
            $user = $delay->shipment->assignedDriver ?? $delay->shipment->customer ?? null;
            
            return [
                'id' => $delay->id,
                'model_type' => 'ShipmentDelay',
                'action' => 'Shipment delayed',
                'description' => $delay->shipment 
                    ? "#{$delay->shipment->tracking_number} delayed due to {$delay->reason}"
                    : "Shipment delayed due to {$delay->reason}",
                'icon' => 'alert-circle',
                'user' => $user,
                'created_at' => $delay->created_at,
                'status' => 'delayed'
            ];
        });
}

// Merge all activities and sort by date
$recentActivities = $shipmentActivities
    ->concat($vehicleActivities)
    ->concat($delayActivities)
    ->sortByDesc('created_at')
    ->take(5)
    ->values();
        
        // ========================================
        // ACTIVE DELIVERIES BY LOCATION
        // ========================================
        
        $activeDeliveriesByLocation = Shipment::select(
                'delivery_city',
                'delivery_state',
                DB::raw('COUNT(*) as shipment_count'),
                DB::raw('SUM(CASE WHEN actual_delivery_date <= expected_delivery_date OR actual_delivery_date IS NULL THEN 1 ELSE 0 END) as on_time_count'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, pickup_date, NOW())) as avg_transit_hours')
            )
            ->whereIn('status', ['picked_up', 'in_transit', 'out_for_delivery'])
            ->groupBy('delivery_city', 'delivery_state')
            ->orderByDesc('shipment_count')
            ->limit(5)
            ->get()
            ->map(function($location) {
                $isOnTime = $location->on_time_count == $location->shipment_count;
                $isDelayed = $location->on_time_count < $location->shipment_count;
                $isCompleted = false;
                
                return [
                    'city' => $location->delivery_city,
                    'state' => $location->delivery_state,
                    'count' => $location->shipment_count,
                    'status' => $isDelayed ? 'delayed' : 'on time',
                    'avg_hours' => round($location->avg_transit_hours ?? 0, 1),
                    'is_on_time' => $isOnTime,
                    'is_delayed' => $isDelayed,
                ];
            });
        
        // ========================================
        // COMPILE ALL DATA
        // ========================================
        
        $stats = [
            'active_shipments' => $activeShipments,
            'active_shipments_growth' => $activeShipmentsGrowth,
            'delivered_today' => $deliveredToday,
            'delivered_today_growth' => $deliveredTodayGrowth,
            'pending_orders' => $pendingOrders,
            'pending_orders_growth' => $pendingOrdersGrowth,
            'revenue_mtd' => $revenueMTD,
            'revenue_growth' => $revenueGrowth,
            'on_time_rate' => $onTimeRate,
            'avg_delivery_time' => round($avgDeliveryTime, 1),
            'delayed_shipments' => $delayedShipments,
            'critical_delays' => $criticalDelays,
            'active_drivers' => $activeDrivers,
            'busy_drivers' => $busyDrivers,
            
            // Fleet stats
            'total_vehicles' => $totalVehicles,
            'active_vehicles' => $activeVehicles,
            'maintenance_vehicles' => $maintenanceVehicles,
            'available_vehicles' => $availableVehicles,
            'fleet_efficiency' => $fleetEfficiency,
        ];
        
        $chartData = [
            'monthly_trends' => $monthlyTrends,
        ];
        
        return view('admin.index', compact(
            'stats',
            'chartData',
            'recentActivities',
            'activeDeliveriesByLocation'
        ));
    }
}

 

    

