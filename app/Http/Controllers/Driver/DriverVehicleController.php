<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\MaintenanceLog;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DriverVehicleController extends Controller
{
    /**
     * Display the driver's assigned vehicle details
     */
    public function vehicleDetails()
    {
        $driver = Auth::user();
        
        // Get the vehicle assigned to the logged-in driver
        $vehicle = Vehicle::with(['branch', 'hub', 'warehouse'])
            ->where('assigned_driver_id', $driver->id)
            ->first();

        // Log activity
        if ($vehicle) {
            ActivityLog::create([
                'user_id' => $driver->id,
                'action' => 'viewed',
                'model_type' => 'Vehicle',
                'model_id' => $vehicle->id,
                'description' => "Driver viewed vehicle details: {$vehicle->vehicle_number}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        return view('driver.vehicle.vehicle_details', compact('vehicle'));
    }

    /**
     * Display maintenance reports for the driver's vehicle
     */
    public function maintenanceIndex(Request $request)
    {
        $driver = Auth::user();
        
        // Get the vehicle assigned to the driver
        $vehicle = Vehicle::where('assigned_driver_id', $driver->id)->first();

        if (!$vehicle) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'No vehicle assigned to you.');
        }

        $query = MaintenanceLog::with(['vehicle', 'performedBy', 'approvedBy'])
            ->where('vehicle_id', $vehicle->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('log_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%")
                  ->orWhere('technician_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by maintenance type
        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('maintenance_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('maintenance_date', '<=', $request->date_to);
        }

        $maintenanceLogs = $query->orderBy('maintenance_date', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_records' => $maintenanceLogs->count(),
            'scheduled' => $maintenanceLogs->where('status', 'scheduled')->count(),
            'in_progress' => $maintenanceLogs->where('status', 'in_progress')->count(),
            'completed' => $maintenanceLogs->where('status', 'completed')->count(),
            'cancelled' => $maintenanceLogs->where('status', 'cancelled')->count(),
        ];

        // Get filter options
        $statuses = ['scheduled', 'in_progress', 'completed', 'cancelled'];
        $maintenanceTypes = ['scheduled', 'breakdown', 'inspection', 'repair', 'service'];
        $priorities = ['low', 'medium', 'high', 'critical'];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $maintenanceLogs,
                'stats' => $stats
            ]);
        }

        return view('driver.vehicle.maintenance_report', compact(
            'maintenanceLogs', 
            'stats', 
            'vehicle',
            'statuses', 
            'maintenanceTypes',
            'priorities'
        ));
    }

    /**
     * Show specific maintenance log details
     */
    public function maintenanceShow($id)
    {
        $driver = Auth::user();
        $vehicle = Vehicle::where('assigned_driver_id', $driver->id)->first();

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'No vehicle assigned'
            ], 403);
        }

        $maintenanceLog = MaintenanceLog::with(['vehicle', 'performedBy', 'approvedBy'])
            ->where('vehicle_id', $vehicle->id)
            ->findOrFail($id);

        // Log activity
        ActivityLog::create([
            'user_id' => $driver->id,
            'action' => 'viewed',
            'model_type' => 'MaintenanceLog',
            'model_id' => $maintenanceLog->id,
            'description' => "Driver viewed maintenance log: {$maintenanceLog->log_number}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $maintenanceLog
        ]);
    }

    /**
     * Report a maintenance issue (driver submits a new maintenance request)
     */
    public function reportMaintenance(Request $request)
    {
        $driver = Auth::user();
        
        // Get the vehicle assigned to the driver
        $vehicle = Vehicle::where('assigned_driver_id', $driver->id)->first();

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'No vehicle assigned to you'
            ], 403);
        }

        $validated = $request->validate([
            'maintenance_type' => 'required|in:scheduled,breakdown,inspection,repair,service',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'category' => 'nullable|string|max:255',
            'mileage_at_maintenance' => 'nullable|integer',
            'notes' => 'nullable|string',
            'invoice_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Auto-fill data
            $validated['vehicle_id'] = $vehicle->id;
            $validated['log_number'] = 'MNT-' . str_pad(MaintenanceLog::count() + 1, 6, '0', STR_PAD_LEFT);
            $validated['maintenance_date'] = now(); // Report date is today
            $validated['status'] = 'scheduled'; // Default status
            $validated['performed_by'] = $driver->id; // Driver reporting
            $validated['cost'] = 0; // Will be updated by admin

            // Handle file upload
            if ($request->hasFile('invoice_document')) {
                $validated['invoice_document'] = $request->file('invoice_document')
                    ->store('maintenance_reports', 'public');
            }

            $maintenanceLog = MaintenanceLog::create($validated);

            // Log activity
            ActivityLog::create([
                'user_id' => $driver->id,
                'action' => 'created',
                'model_type' => 'MaintenanceLog',
                'model_id' => $maintenanceLog->id,
                'description' => "Driver reported maintenance issue: {$maintenanceLog->log_number}",
                'new_values' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Create notification for admins
            $this->notifyAdmins($maintenanceLog, $vehicle, $driver);

            // Update vehicle status if critical
            if ($validated['priority'] === 'critical') {
                $vehicle->update(['status' => 'maintenance']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Maintenance issue reported successfully. Admin will review it shortly.',
                'data' => $maintenanceLog->load(['vehicle', 'performedBy'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to report maintenance issue: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update maintenance report (if driver needs to add more info)
     */
    public function updateMaintenanceReport(Request $request, $id)
    {
        $driver = Auth::user();
        $vehicle = Vehicle::where('assigned_driver_id', $driver->id)->first();

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'No vehicle assigned'
            ], 403);
        }

        $maintenanceLog = MaintenanceLog::where('vehicle_id', $vehicle->id)
            ->where('performed_by', $driver->id)
            ->where('status', 'scheduled') // Only allow updates to scheduled items
            ->findOrFail($id);

        $validated = $request->validate([
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'mileage_at_maintenance' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $maintenanceLog->toArray();
            $maintenanceLog->update($validated);

            // Log activity
            ActivityLog::create([
                'user_id' => $driver->id,
                'action' => 'updated',
                'model_type' => 'MaintenanceLog',
                'model_id' => $maintenanceLog->id,
                'description' => "Driver updated maintenance report: {$maintenanceLog->log_number}",
                'old_values' => $oldValues,
                'new_values' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Maintenance report updated successfully',
                'data' => $maintenanceLog->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get maintenance history for driver's vehicle
     */
    public function maintenanceHistory(Request $request)
    {
        $driver = Auth::user();
        $vehicle = Vehicle::where('assigned_driver_id', $driver->id)->first();

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'No vehicle assigned'
            ], 403);
        }

        $logs = MaintenanceLog::with(['performedBy', 'approvedBy'])
            ->where('vehicle_id', $vehicle->id)
            ->orderBy('maintenance_date', 'desc')
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Export maintenance reports
     */
    public function exportMaintenanceReports(Request $request, $format = 'csv')
    {
        $driver = Auth::user();
        $vehicle = Vehicle::where('assigned_driver_id', $driver->id)->first();

        if (!$vehicle) {
            return redirect()->back()->with('error', 'No vehicle assigned');
        }

        $maintenanceLogs = MaintenanceLog::with(['vehicle', 'performedBy'])
            ->where('vehicle_id', $vehicle->id)
            ->orderBy('maintenance_date', 'desc')
            ->get();

        // Log activity
        ActivityLog::create([
            'user_id' => $driver->id,
            'action' => 'exported',
            'model_type' => 'MaintenanceLog',
            'description' => "Driver exported maintenance reports as {$format}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        switch ($format) {
            case 'csv':
                return $this->exportCsv($maintenanceLogs, $vehicle);
            case 'pdf':
                return $this->exportPdf($maintenanceLogs, $vehicle);
            default:
                return response()->json(['success' => false, 'message' => 'Invalid format'], 400);
        }
    }

    /**
     * Private method to notify admins about new maintenance report
     */
    private function notifyAdmins($maintenanceLog, $vehicle, $driver)
    {
        $adminUsers = \App\Models\User::where('role', 'admin')->get();
        
        $title = 'New Maintenance Report from Driver';
        $message = "Driver {$driver->first_name} {$driver->last_name} reported a {$maintenanceLog->priority} priority {$maintenanceLog->maintenance_type} issue for vehicle {$vehicle->vehicle_number}: {$maintenanceLog->description}";
        
        foreach ($adminUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'type' => $maintenanceLog->priority === 'critical' ? 'error' : 'warning',
                'channel' => 'system',
                'action_url' => route('admin.maintenance.show', $maintenanceLog->id),
                'data' => json_encode([
                    'maintenance_log_id' => $maintenanceLog->id,
                    'vehicle_id' => $vehicle->id,
                    'driver_id' => $driver->id,
                    'priority' => $maintenanceLog->priority,
                ])
            ]);
        }
    }

    /**
     * Export maintenance logs to CSV
     */
    private function exportCsv($data, $vehicle)
    {
        $filename = 'maintenance_reports_' . $vehicle->vehicle_number . '_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data, $vehicle) {
            $file = fopen('php://output', 'w');
            
            // Add header info
            fputcsv($file, ['Maintenance Report for Vehicle: ' . $vehicle->vehicle_number]);
            fputcsv($file, ['Make/Model: ' . ($vehicle->make ?? 'N/A') . ' ' . ($vehicle->model ?? '')]);
            fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []); // Empty row

            // Column headers
            fputcsv($file, [
                'Log Number', 
                'Type', 
                'Category',
                'Description', 
                'Status', 
                'Priority', 
                'Provider', 
                'Cost', 
                'Mileage',
                'Scheduled Date',
                'Reported By'
            ]);

            foreach ($data as $log) {
                fputcsv($file, [
                    $log->log_number,
                    ucfirst($log->maintenance_type),
                    $log->category ?? 'N/A',
                    $log->description,
                    ucfirst($log->status),
                    ucfirst($log->priority ?? 'medium'),
                    $log->vendor_name ?? 'N/A',
                    '$' . number_format($log->cost, 2),
                    $log->mileage_at_maintenance ?? 'N/A',
                    $log->maintenance_date->format('Y-m-d'),
                    $log->performedBy->name ?? 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export maintenance logs to PDF
     */
    private function exportPdf($data, $vehicle)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('driver.maintenance_pdf', [
            'maintenanceLogs' => $data,
            'vehicle' => $vehicle,
            'driver' => Auth::user()
        ]);
        
        return $pdf->download('maintenance_reports_' . $vehicle->vehicle_number . '_' . date('Y-m-d_His') . '.pdf');
    }
}