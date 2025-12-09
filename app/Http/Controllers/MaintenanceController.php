<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
  public function indexMaintenance(Request $request)
{
    $query = MaintenanceLog::with(['vehicle', 'performedBy', 'approvedBy']);

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('log_number', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('vendor_name', 'like', "%{$search}%")
              ->orWhereHas('vehicle', function($q) use ($search) {
                  $q->where('vehicle_number', 'like', "%{$search}%")
                    ->orWhere('vehicle_type', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
              })
              ->orWhereHas('performedBy', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Filter by vehicle
    if ($request->filled('vehicle_id')) {
        $query->where('vehicle_id', $request->vehicle_id);
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by maintenance type
    if ($request->filled('maintenance_type')) {
        $query->where('maintenance_type', $request->maintenance_type);
    }

    // Filter by category (using maintenance_type as category)
    if ($request->filled('category')) {
        $query->where('maintenance_type', $request->category);
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

    // Get paginated results (15 per page, you can adjust this)
    $perPage = $request->get('per_page', 15);
    $maintenanceLogs = $query->orderBy('maintenance_date', 'desc')->paginate($perPage);

    // Calculate statistics (using all records, not just current page)
    $allRecords = MaintenanceLog::query();
    
    // Apply same filters for stats
    if ($request->filled('search')) {
        $search = $request->search;
        $allRecords->where(function($q) use ($search) {
            $q->where('log_number', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('vendor_name', 'like', "%{$search}%")
              ->orWhereHas('vehicle', function($q) use ($search) {
                  $q->where('vehicle_number', 'like', "%{$search}%")
                    ->orWhere('vehicle_type', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
              });
        });
    }
    if ($request->filled('vehicle_id')) $allRecords->where('vehicle_id', $request->vehicle_id);
    if ($request->filled('status')) $allRecords->where('status', $request->status);
    if ($request->filled('maintenance_type')) $allRecords->where('maintenance_type', $request->maintenance_type);
    if ($request->filled('priority')) $allRecords->where('priority', $request->priority);
    if ($request->filled('date_from')) $allRecords->whereDate('maintenance_date', '>=', $request->date_from);
    if ($request->filled('date_to')) $allRecords->whereDate('maintenance_date', '<=', $request->date_to);

    $totalRecords = $allRecords->count();
    $completedCount = $allRecords->where('status', 'completed')->count();
    $totalCost = $allRecords->sum('cost');

    $stats = [
        'total_records' => $totalRecords,
        'completion_rate' => $totalRecords > 0 
            ? round(($completedCount / $totalRecords) * 100, 1) 
            : 0,
        'total_cost' => $totalCost,
        'average_cost' => $totalRecords > 0 
            ? round($totalCost / $totalRecords, 2) 
            : 0,
        'overdue_items' => $allRecords->where('status', 'scheduled')
            ->where('maintenance_date', '<', now())->count(),
    ];

    // Get filter options
    $vehicles = Vehicle::select('id', 'vehicle_number', 'vehicle_type', 'model')->get();
    $statuses = ['scheduled', 'in_progress', 'completed', 'cancelled'];
    $maintenanceTypes = ['scheduled', 'breakdown', 'inspection', 'repair', 'service'];
    $priorities = ['low', 'medium', 'high', 'critical'];

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'data' => $maintenanceLogs->items(),
            'stats' => $stats,
            'pagination' => [
                'current_page' => $maintenanceLogs->currentPage(),
                'last_page' => $maintenanceLogs->lastPage(),
                'per_page' => $maintenanceLogs->perPage(),
                'total' => $maintenanceLogs->total(),
            ]
        ]);
    }

    return view('backend.fleet.maintenance', compact(
        'maintenanceLogs', 
        'stats', 
        'vehicles', 
        'statuses', 
        'maintenanceTypes',
        'priorities'
    ));
}
    public function storeMaintenance(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'maintenance_type' => 'required|in:scheduled,breakdown,inspection,repair,service',
            'maintenance_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'vendor_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'parts_replaced' => 'nullable|string',
            'mileage_at_maintenance' => 'nullable|integer',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'performed_by' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,critical',
            'category' => 'nullable|string',
            'notes' => 'nullable|string',
            'invoice_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Generate unique log number
            $validated['log_number'] = 'MNT-' . str_pad(MaintenanceLog::count() + 1, 3, '0', STR_PAD_LEFT);
            
            // Handle file upload
            if ($request->hasFile('invoice_document')) {
                $validated['invoice_document'] = $request->file('invoice_document')
                    ->store('maintenance_invoices', 'public');
            }

            $maintenanceLog = MaintenanceLog::create($validated);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'model_type' => 'MaintenanceLog',
                'model_id' => $maintenanceLog->id,
                'description' => "Created maintenance log {$maintenanceLog->log_number}",
                'new_values' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Create notification for admin and technician
            $this->createNotification(
                $maintenanceLog,
                'New Maintenance Scheduled',
                "Maintenance {$maintenanceLog->log_number} has been scheduled for " . $maintenanceLog->vehicle->vehicle_number
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Maintenance log created successfully',
                'data' => $maintenanceLog->load(['vehicle', 'performedBy'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create maintenance log: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showMaintenance($id)
    {
        $maintenanceLog = MaintenanceLog::with(['vehicle', 'performedBy', 'approvedBy'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $maintenanceLog
        ]);
    }

    public function updateMaintenance(Request $request, $id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);

        $validated = $request->validate([
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
            'priority' => 'nullable|in:low,medium,high,critical',
            'description' => 'nullable|string',
            'vendor_name' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'mileage_at_maintenance' => 'nullable|integer',
            'notes' => 'nullable|string',
            'performed_by' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $maintenanceLog->toArray();
            $maintenanceLog->update($validated);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'updated',
                'model_type' => 'MaintenanceLog',
                'model_id' => $maintenanceLog->id,
                'description' => "Updated maintenance log {$maintenanceLog->log_number}",
                'old_values' => $oldValues,
                'new_values' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Create notification on status change
            if (isset($validated['status']) && $oldValues['status'] !== $validated['status']) {
                $this->createNotification(
                    $maintenanceLog,
                    'Maintenance Status Updated',
                    "Maintenance {$maintenanceLog->log_number} status changed to {$validated['status']}"
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Maintenance log updated successfully',
                'data' => $maintenanceLog->fresh()->load(['vehicle', 'performedBy'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update maintenance log: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyMaintenance($id)
    {
        DB::beginTransaction();
        try {
            $maintenanceLog = MaintenanceLog::findOrFail($id);
            $logNumber = $maintenanceLog->log_number;

            // Delete associated invoice if exists
            if ($maintenanceLog->invoice_document) {
                Storage::disk('public')->delete($maintenanceLog->invoice_document);
            }

            $maintenanceLog->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'model_type' => 'MaintenanceLog',
                'model_id' => $id,
                'description' => "Deleted maintenance log {$logNumber}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Maintenance log deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete maintenance log: ' . $e->getMessage()
            ], 500);
        }
    }

    public function scheduleFollowUpMaintenance(Request $request, $id)
    {
        $parentLog = MaintenanceLog::findOrFail($id);

        $validated = $request->validate([
            'maintenance_type' => 'required|in:scheduled,breakdown,inspection,repair,service',
            'category' => 'nullable|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'maintenance_date' => 'required|date|after_or_equal:today',
            'vendor_name' => 'nullable|string|max:255',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Auto-assign vehicle from parent record
            $validated['vehicle_id'] = $parentLog->vehicle_id;
            
            // Generate unique log number
            $validated['log_number'] = 'MNT-' . str_pad(MaintenanceLog::count() + 1, 3, '0', STR_PAD_LEFT);
            
            // Set status to scheduled
            $validated['status'] = 'scheduled';
            
            // Set current user as performed_by if authenticated
            if (Auth::check()) {
                $validated['performed_by'] = Auth::id();
            }

            $followUpLog = MaintenanceLog::create($validated);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'model_type' => 'MaintenanceLog',
                'model_id' => $followUpLog->id,
                'description' => "Scheduled follow-up maintenance {$followUpLog->log_number} for {$parentLog->log_number}",
                'new_values' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Create notification
            $this->createNotification(
                $followUpLog,
                'Follow-up Maintenance Scheduled',
                "Follow-up maintenance {$followUpLog->log_number} scheduled for " . $followUpLog->vehicle->vehicle_number . " (related to {$parentLog->log_number})"
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Follow-up maintenance scheduled successfully',
                'data' => $followUpLog->load(['vehicle', 'performedBy'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule follow-up: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportMaintenance(Request $request, $format = 'csv')
    {
        $query = MaintenanceLog::with(['vehicle', 'performedBy', 'approvedBy']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('log_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $maintenanceLogs = $query->orderBy('maintenance_date', 'desc')->get();

        switch ($format) {
            case 'csv':
                return $this->exportCsv($maintenanceLogs);
            case 'excel':
                return $this->exportExcel($maintenanceLogs);
            case 'pdf':
                return $this->exportPdf($maintenanceLogs);
            default:
                return response()->json(['success' => false, 'message' => 'Invalid format'], 400);
        }
    }

    private function exportCsv($data)
    {
        $filename = 'maintenance_logs_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Log Number', 'Vehicle', 'Type', 'Category', 'Description', 'Status', 'Priority', 'Provider', 'Cost', 'Scheduled Date']);

            foreach ($data as $log) {
                fputcsv($file, [
                    $log->log_number,
                    $log->vehicle->vehicle_number . ' - ' . $log->vehicle->vehicle_type . ' ' . $log->vehicle->model,
                    ucfirst($log->maintenance_type),
                    $log->category ?? 'N/A',
                    $log->description,
                    ucfirst($log->status),
                    ucfirst($log->priority ?? 'medium'),
                    $log->vendor_name ?? 'N/A',
                    '$' . number_format($log->cost, 2),
                    $log->maintenance_date->format('Y-m-d'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf($data)
    {
        $pdf = Pdf::loadView('admin.maintenance_logs.pdf', ['maintenanceLogs' => $data]);
        return $pdf->download('maintenance_logs_' . date('Y-m-d_His') . '.pdf');
    }

    private function exportExcel($data)
    {
        // Similar to CSV but with Excel formatting
        return $this->exportCsv($data);
    }

    private function createNotification($maintenanceLog, $title, $message)
    {
        // Notify admin users
        $adminUsers = User::where('role', 'admin')->get();
        
        foreach ($adminUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'type' => 'info',
                'channel' => 'system',
                'action_url' => route('admin.maintenance.show', $maintenanceLog->id),
                'data' => json_encode([
                    'maintenance_log_id' => $maintenanceLog->id,
                    'vehicle_id' => $maintenanceLog->vehicle_id,
                ])
            ]);
        }

        // Notify assigned technician if exists
        if ($maintenanceLog->performed_by) {
            Notification::create([
                'user_id' => $maintenanceLog->performed_by,
                'title' => $title,
                'message' => $message,
                'type' => 'info',
                'channel' => 'system',
                'action_url' => route('admin.maintenance.show', $maintenanceLog->id),
                'data' => json_encode([
                    'maintenance_log_id' => $maintenanceLog->id,
                    'vehicle_id' => $maintenanceLog->vehicle_id,
                ])
            ]);
        }
    }
}