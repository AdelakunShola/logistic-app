<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // User Filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Action Filter
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Model Type Filter
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Date Range Filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $logs = $query->paginate(50);

        // Get filter options
        $users = User::select('id', 'first_name', 'last_name', 'email')
                     ->orderBy('first_name')
                     ->get();
        
        $actions = ActivityLog::select('action')
                              ->distinct()
                              ->orderBy('action')
                              ->pluck('action');
        
        $modelTypes = ActivityLog::select('model_type')
                                 ->distinct()
                                 ->whereNotNull('model_type')
                                 ->orderBy('model_type')
                                 ->pluck('model_type');

        // Statistics
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => ActivityLog::whereMonth('created_at', now()->month)->count(),
            'unique_users' => ActivityLog::distinct('user_id')->count('user_id'),
            'by_action' => ActivityLog::select('action', DB::raw('count(*) as count'))
                                      ->groupBy('action')
                                      ->pluck('count', 'action')
                                      ->toArray(),
        ];

        return view('backend.activity-logs.index', compact('logs', 'users', 'actions', 'modelTypes', 'stats'));
    }

    /**
     * Display single activity log
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'log' => [
                    'id' => $log->id,
                    'user' => $log->user ? [
                        'name' => $log->user->first_name . ' ' . $log->user->last_name,
                        'email' => $log->user->email,
                        'role' => $log->user->role,
                    ] : null,
                    'action' => $log->action,
                    'model_type' => $log->model_type,
                    'model_id' => $log->model_id,
                    'description' => $log->description,
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'created_at' => $log->created_at->format('M d, Y H:i:s'),
                ]
            ]);
        }

        return view('backend.activity-logs.show', compact('log'));
    }

    /**
     * Delete old activity logs
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        try {
            $date = now()->subDays($request->days);
            $count = ActivityLog::where('created_at', '<', $date)->delete();

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$count} activity logs older than {$request->days} days"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->get();

        $filename = 'activity_logs_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Date/Time', 'User', 'Action', 'Model Type', 'Model ID', 
                'Description', 'IP Address', 'User Agent'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'System',
                    $log->action,
                    $log->model_type,
                    $log->model_id,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}