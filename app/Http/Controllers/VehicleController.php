<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\User;
use App\Models\Branch;
use App\Models\Hub;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
  
    public function index(Request $request)
{
    $query = Vehicle::with(['assignedDriver:id,first_name,last_name', 'warehouse:id,name']);

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('vehicle_number', 'like', "%{$search}%")
                ->orWhere('vehicle_name', 'like', "%{$search}%")
                ->orWhere('make', 'like', "%{$search}%")
                ->orWhere('model', 'like', "%{$search}%")
                ->orWhere('license_plate', 'like', "%{$search}%")
                ->orWhereHas('assignedDriver', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
        });
    }

    // Type filter
    if ($request->filled('type') && $request->type !== 'all') {
        $query->where('vehicle_type', $request->type);
    }

    // Status filter
    if ($request->filled('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

 

    // warehouse filter
    if ($request->filled('warehouse_id')) {
        $query->where('warehouse_id', $request->warehouse_id);
    }


    // Sorting
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $query->orderBy($sortBy, $sortOrder);

    $vehicles = $query->paginate($request->get('per_page', 15));

    // Calculate statistics - Use more efficient queries
    $stats = Cache::remember('vehicle_stats', 300, function () {
        return [
            'total' => Vehicle::count(),
            'active' => Vehicle::where('status', 'active')->count(),
            'maintenance' => Vehicle::where('status', 'maintenance')->count(),
            'inactive' => Vehicle::where('status', 'inactive')->count(),
            'repair' => Vehicle::where('status', 'repair')->count(),
            'avg_utilization' => Vehicle::where('status', 'active')->avg('utilization_percentage') ?? 0,
            'total_alerts' => Vehicle::sum('alert_count'),
            'maintenance_due' => Vehicle::whereNotNull('next_service_date')
                ->where('next_service_date', '<=', now()->addDays(30))
                ->count(),
        ];
    });

    // Get filter options - Cache these and only select needed columns
    $drivers = Cache::remember('active_drivers', 600, function () {
        return User::where('role', 'driver')
            ->where('status', 'active')
            ->select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get();
    });

   

    $warehouse = Cache::remember('all_warehouses', 600, function () {
        return Warehouse::select('id', 'name')->orderBy('name')->get();
    });

  

    $vehicleTypes = Cache::remember('vehicle_types', 600, function () {
        return DB::table('vehicles')
            ->select('vehicle_type')
            ->distinct()
            ->whereNotNull('vehicle_type')
            ->pluck('vehicle_type')
            ->toArray();
    });

    $statuses = Cache::remember('vehicle_statuses', 600, function () {
        return DB::table('vehicles')
            ->select('status')
            ->distinct()
            ->whereNotNull('status')
            ->pluck('status')
            ->toArray();
    });

    if ($request->ajax()) {
        return response()->json([
            'vehicles' => $vehicles,
            'stats' => $stats
        ]);
    }

    return view('backend.fleet.vehicles', compact('vehicles', 'stats', 'drivers', 'warehouse', 'vehicleTypes', 'statuses'));
}


public function create()
{
    // Get filter options for the form - Only select needed columns
    $drivers = User::where('role', 'driver')
        ->where('status', 'active')
        ->select('id', 'first_name', 'last_name')
        ->orderBy('first_name')
        ->get();

   
    $warehouse = Warehouse::select('id', 'name')->orderBy('name')->get();

    return view('backend.fleet.vehicles-create', compact('drivers',  'warehouse'));
}

public function store(Request $request)
{
    // Get all request data except _token and _method
    $data = $request->except(['_token', '_method']);
    
    $vehicle = Vehicle::create($data);

    // Log activity - Use queued job for better performance
    dispatch(function () use ($vehicle, $data, $request) {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'model_type' => 'Vehicle',
            'model_id' => $vehicle->id,
            'description' => "Created vehicle: {$vehicle->vehicle_number}",
            'new_values' => json_encode($data),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    });

    // Create notification for assigned driver - Use queue
    if ($vehicle->assigned_driver_id) {
        dispatch(function () use ($vehicle) {
            Notification::create([
                'user_id' => $vehicle->assigned_driver_id,
                'title' => 'Vehicle Assigned',
                'message' => "You have been assigned to vehicle {$vehicle->vehicle_number}",
                'type' => 'info',
                'channel' => 'system',
            ]);
        });
    }

    // Clear relevant caches
    Cache::forget('vehicle_stats');
    Cache::forget('active_drivers');

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Vehicle created successfully',
            'vehicle' => $vehicle->load(['assignedDriver:id,first_name,last_name', 'warehouse:id,name'])
        ]);
    }

    return redirect()->route('admin.vehicles.index')
        ->with('success', 'Vehicle created successfully');
}



public function update(Request $request, $id)
{
    $vehicle = Vehicle::findOrFail($id);
    
    $oldDriverId = $vehicle->assigned_driver_id;
    $data = $request->except(['_token', '_method']);
    $newDriverId = $data['assigned_driver_id'] ?? null;
    $driverChanged = $oldDriverId != $newDriverId;
    
    // DEBUG: Log before update
    Log::info('Vehicle Update Debug', [
        'old_driver_id' => $oldDriverId,
        'new_driver_id' => $newDriverId,
        'driver_changed' => $driverChanged,
    ]);
    
    $vehicle->update($data);
    
    // Handle driver notifications WITHOUT QUEUE (for testing)
    if ($driverChanged) {
        if ($oldDriverId) {
            try {
                $notification = Notification::create([
                    'user_id' => $oldDriverId,
                    'title' => 'Vehicle Unassigned',
                    'message' => "You have been unassigned from vehicle {$vehicle->vehicle_number}",
                    'type' => 'warning',
                    'channel' => 'system',
                ]);
                Log::info('Old driver notification created', ['notification_id' => $notification->id]);
            } catch (\Exception $e) {
                Log::error('Failed to create old driver notification: ' . $e->getMessage());
            }
        }
        
        if ($newDriverId) {
            try {
                $notification = Notification::create([
                    'user_id' => $newDriverId,
                    'title' => 'Vehicle Assigned',
                    'message' => "You have been assigned to vehicle {$vehicle->vehicle_number}",
                    'type' => 'info',
                    'channel' => 'system',
                ]);
                Log::info('New driver notification created', ['notification_id' => $notification->id]);
            } catch (\Exception $e) {
                Log::error('Failed to create new driver notification: ' . $e->getMessage());
            }
        }
    }
    
    return redirect()->route('admin.vehicles.index')
        ->with('success', 'Vehicle updated successfully');
}
// Apply the same fix to edit() and show() methods:

public function edit($id)
{
    // Only load what's needed
    $vehicle = Vehicle::with(['assignedDriver:id,first_name,last_name', 'warehouse:id,name'])
        ->findOrFail($id);
    
    // Get filter options for the form - Only select needed columns
    $drivers = User::where('role', 'driver')
        ->where('status', 'active')
        ->select('id', 'first_name', 'last_name')
        ->orderBy('first_name')
        ->get();

    $warehouse = Warehouse::select('id', 'name')->orderBy('name')->get();

    // Extract primitive values
    $vehicleId = $id;
    $vehicleNumber = $vehicle->vehicle_number;
    $userId = Auth::id();
    $ipAddress = request()->ip();
    $userAgent = request()->userAgent();

    // Log activity - Use queue
    dispatch(function () use ($vehicleId, $vehicleNumber, $userId, $ipAddress, $userAgent) {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'accessed_edit',
            'model_type' => 'Vehicle',
            'model_id' => $vehicleId,
            'description' => "Accessed edit form for vehicle: {$vehicleNumber}",
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    });

    return view('backend.fleet.vehicles-edit', compact('vehicle', 'drivers', 'warehouse'));
}

public function show($id)
{
    // Only load maintenance logs with limit
    $vehicle = Vehicle::with([
        'assignedDriver:id,first_name,last_name',
        'warehouse:id,name',
        'maintenanceLogs' => function($query) {
            $query->orderBy('maintenance_date', 'desc')->limit(20);
        }
    ])->findOrFail($id);

    // Extract primitive values
    $vehicleId = $id;
    $vehicleNumber = $vehicle->vehicle_number;
    $userId = Auth::id();
    $ipAddress = request()->ip();
    $userAgent = request()->userAgent();

    // Log activity - Use queue
    dispatch(function () use ($vehicleId, $vehicleNumber, $userId, $ipAddress, $userAgent) {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'viewed',
            'model_type' => 'Vehicle',
            'model_id' => $vehicleId,
            'description' => "Viewed vehicle details: {$vehicleNumber}",
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    });

    return view('backend.fleet.vehicles-show', compact('vehicle'));
}





    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicleNumber = $vehicle->vehicle_number;

        // Log activity before deletion
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'model_type' => 'Vehicle',
            'model_id' => $vehicle->id,
            'description' => "Deleted vehicle: {$vehicleNumber}",
            'old_values' => json_encode($vehicle->toArray()),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Notify assigned driver
        if ($vehicle->assigned_driver_id) {
            Notification::create([
                'user_id' => $vehicle->assigned_driver_id,
                'title' => 'Vehicle Removed',
                'message' => "Vehicle {$vehicleNumber} has been removed from the fleet",
                'type' => 'warning',
                'channel' => 'system',
            ]);
        }

        $vehicle->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ]);
        }

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle deleted successfully');
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $vehicles = Vehicle::with(['assignedDriver', 'warehouse'])->get();

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'exported',
            'model_type' => 'Vehicle',
            'description' => "Exported vehicles as {$format}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($format === 'csv') {
            return $this->exportCsv($vehicles);
        } elseif ($format === 'excel') {
            return $this->exportExcel($vehicles);
        } elseif ($format === 'pdf') {
            return $this->exportPdf($vehicles);
        }
    }

    private function exportCsv($vehicles)
    {
        $filename = 'vehicles_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($vehicles) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Vehicle Number', 'Type', 'Make', 'Model', 'Status', 'Driver', 'Location', 'Mileage']);

            foreach ($vehicles as $vehicle) {
                fputcsv($file, [
                    $vehicle->vehicle_number,
                    $vehicle->vehicle_type,
                    $vehicle->make,
                    $vehicle->model,
                    $vehicle->status,
                    $vehicle->assignedDriver ? $vehicle->assignedDriver->first_name . ' ' . $vehicle->assignedDriver->last_name : 'N/A',
                    $vehicle->current_location ?? 'N/A',
                    $vehicle->mileage,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



















    public function assignDriver(Request $request, $id)
{
    $vehicle = Vehicle::findOrFail($id);
    
    $validated = $request->validate([
        'driver_id' => 'required|exists:users,id',
    ]);

    $vehicle->assignDriver($validated['driver_id']);

    // Log activity
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'assigned_driver',
        'model_type' => 'Vehicle',
        'model_id' => $vehicle->id,
        'description' => "Assigned driver to vehicle: {$vehicle->vehicle_number}",
        'new_values' => json_encode(['driver_id' => $validated['driver_id']]),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Driver assigned successfully',
            'vehicle' => $vehicle->load('assignedDriver')
        ]);
    }

    return redirect()->route('admin.vehicles.index')
        ->with('success', 'Driver assigned successfully');
}

public function track($id)
{
    $vehicle = Vehicle::with('assignedDriver')->findOrFail($id);

    // Log activity
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'tracked',
        'model_type' => 'Vehicle',
        'model_id' => $vehicle->id,
        'description' => "Tracked location of vehicle: {$vehicle->vehicle_number}",
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    if (request()->ajax()) {
        return response()->json([
            'vehicle' => $vehicle,
            'location' => [
                'latitude' => $vehicle->current_latitude,
                'longitude' => $vehicle->current_longitude,
                'address' => $vehicle->current_location,
                'last_update' => $vehicle->last_location_update,
            ]
        ]);
    }

    return view('admin.vehicles.track', compact('vehicle'));
}

public function updateLocation(Request $request, $id)
{
    $vehicle = Vehicle::findOrFail($id);
    
    $validated = $request->validate([
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'address' => 'nullable|string',
    ]);

    $vehicle->updateLocation(
        $validated['latitude'],
        $validated['longitude'],
        $validated['address'] ?? null
    );

    // Log activity
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'location_updated',
        'model_type' => 'Vehicle',
        'model_id' => $vehicle->id,
        'description' => "Updated location for vehicle: {$vehicle->vehicle_number}",
        'new_values' => json_encode($validated),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Location updated successfully',
        'vehicle' => $vehicle
    ]);
}

public function scheduleMaintenanceRoute(Request $request, $id)
{
    $vehicle = Vehicle::findOrFail($id);
    
    $validated = $request->validate([
        'maintenance_date' => 'required|date|after:today',
        'notes' => 'nullable|string',
    ]);

    $vehicle->scheduleMaintenance(
        $validated['maintenance_date'],
        $validated['notes'] ?? null
    );

    // Log activity
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'maintenance_scheduled',
        'model_type' => 'Vehicle',
        'model_id' => $vehicle->id,
        'description' => "Scheduled maintenance for vehicle: {$vehicle->vehicle_number}",
        'new_values' => json_encode($validated),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    // Notify admins
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        Notification::create([
            'user_id' => $admin->id,
            'title' => 'Maintenance Scheduled',
            'message' => "Maintenance scheduled for vehicle {$vehicle->vehicle_number} on {$validated['maintenance_date']}",
            'type' => 'info',
            'channel' => 'system',
        ]);
    }

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Maintenance scheduled successfully',
            'vehicle' => $vehicle
        ]);
    }

    return redirect()->route('admin.vehicles.index')
        ->with('success', 'Maintenance scheduled successfully');
}

public function dashboardStats()
{
    $stats = [
        'total_vehicles' => Vehicle::count(),
        'active_vehicles' => Vehicle::where('status', 'active')->count(),
        'maintenance_vehicles' => Vehicle::where('status', 'maintenance')->count(),
        'inactive_vehicles' => Vehicle::where('status', 'inactive')->count(),
        'repair_vehicles' => Vehicle::where('status', 'repair')->count(),
        'available_vehicles' => Vehicle::available()->count(),
        'avg_utilization' => Vehicle::where('status', 'active')->avg('utilization_percentage') ?? 0,
        'total_alerts' => Vehicle::sum('alert_count'),
        'maintenance_due' => Vehicle::maintenanceDue()->count(),
        'low_fuel_vehicles' => Vehicle::lowFuel()->count(),
        'vehicles_by_type' => Vehicle::select('vehicle_type', DB::raw('count(*) as count'))
            ->groupBy('vehicle_type')
            ->get(),
        'vehicles_by_status' => Vehicle::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get(),
        'total_mileage' => Vehicle::sum('mileage'),
        'avg_mileage' => Vehicle::avg('mileage'),
    ];

    // Recent activity
    $recentActivity = ActivityLog::where('model_type', 'Vehicle')
        ->with('user')
        ->latest()
        ->take(10)
        ->get();

    return response()->json([
        'stats' => $stats,
        'recent_activity' => $recentActivity
    ]);
}

public function bulkDelete(Request $request)
{
    $validated = $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:vehicles,id',
    ]);

    $vehicles = Vehicle::whereIn('id', $validated['ids'])->get();
    $vehicleNumbers = $vehicles->pluck('vehicle_number')->toArray();

    // Log activity
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'bulk_deleted',
        'model_type' => 'Vehicle',
        'description' => "Bulk deleted " . count($validated['ids']) . " vehicles",
        'old_values' => json_encode($vehicleNumbers),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    // Notify affected drivers
    foreach ($vehicles as $vehicle) {
        if ($vehicle->assigned_driver_id) {
            Notification::create([
                'user_id' => $vehicle->assigned_driver_id,
                'title' => 'Vehicle Removed',
                'message' => "Vehicle {$vehicle->vehicle_number} has been removed from the fleet",
                'type' => 'warning',
                'channel' => 'system',
            ]);
        }
    }

    Vehicle::whereIn('id', $validated['ids'])->delete();

    return response()->json([
        'success' => true,
        'message' => count($validated['ids']) . ' vehicles deleted successfully'
    ]);
}

public function bulkUpdateStatus(Request $request)
{
    $validated = $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:vehicles,id',
        'status' => 'required|in:active,inactive,maintenance,repair',
    ]);

    $vehicles = Vehicle::whereIn('id', $validated['ids'])->get();

    // Log activity
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'bulk_status_update',
        'model_type' => 'Vehicle',
        'description' => "Bulk updated status for " . count($validated['ids']) . " vehicles to {$validated['status']}",
        'new_values' => json_encode($validated),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    // Update and notify
    foreach ($vehicles as $vehicle) {
        $vehicle->update(['status' => $validated['status']]);

        if ($vehicle->assigned_driver_id) {
            Notification::create([
                'user_id' => $vehicle->assigned_driver_id,
                'title' => 'Vehicle Status Updated',
                'message' => "Vehicle {$vehicle->vehicle_number} status changed to {$validated['status']}",
                'type' => in_array($validated['status'], ['maintenance', 'repair']) ? 'warning' : 'info',
                'channel' => 'system',
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => count($validated['ids']) . ' vehicles updated successfully'
    ]);
}

private function exportExcel($vehicles)
{
    // Implement Excel export using Laravel Excel package
    // For now, return CSV format
    return $this->exportCsv($vehicles);
}

private function exportPdf($vehicles)
{
    // Implement PDF export using a package like DomPDF or TCPDF
    // For basic implementation:
    $html = view('admin.vehicles.pdf', compact('vehicles'))->render();
    
    // If you have DomPDF installed:
    // $pdf = PDF::loadHTML($html);
    // return $pdf->download('vehicles_' . date('Y-m-d_His') . '.pdf');
    
    // Fallback: return HTML for printing
    return response($html)->header('Content-Type', 'text/html');
}
}