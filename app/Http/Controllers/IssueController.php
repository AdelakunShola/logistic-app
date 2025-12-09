<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ShipmentIssue;
use App\Models\User;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display issues dashboard
     */
    public function issuesindex(Request $request)
    {
        $query = ShipmentIssue::with(['shipment', 'reportedBy', 'assignedTo']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by assignee
        if ($request->filled('assigned')) {
            if ($request->assigned === 'unassigned') {
                $query->whereNull('assigned_to');
            } elseif ($request->assigned === 'me') {
                $query->where('assigned_to', auth()->id());
            } else {
                $query->where('assigned_to', $request->assigned);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('issue_type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('shipment', function($sq) use ($search) {
                      $sq->where('tracking_number', 'like', "%{$search}%");
                  });
            });
        }

        $issues = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics
        $stats = [
            'total' => ShipmentIssue::count(),
            'pending' => ShipmentIssue::where('status', 'pending')->count(),
            'investigating' => ShipmentIssue::where('status', 'investigating')->count(),
            'resolved' => ShipmentIssue::where('status', 'resolved')->count(),
        ];

        // Get support users for assignment dropdown
         $supportUsers = User::get();
    

        return view('backend.shippingissues.index', compact('issues', 'stats', 'supportUsers'));
    }

    /**
     * Display issue details
     */
    public function issuesshow($id)
    {
        $issue = ShipmentIssue::with([
            'shipment.customer', 
            'reportedBy', 
            'assignedTo'
        ])->findOrFail($id);

        return view('backend.shippingissues.show', compact('issue'));
    }

    /**
     * Assign issue to user
     */
    public function issuesassign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'nullable|in:critical,high,medium,low',
        ]);

        $issue = ShipmentIssue::findOrFail($id);
        
        $updateData = [
            'assigned_to' => $request->assigned_to,
            'status' => $issue->status === 'pending' ? 'investigating' : $issue->status,
        ];

        if ($request->filled('priority')) {
            $updateData['priority'] = $request->priority;
        }

        $issue->update($updateData);

        // Notify assigned user 
        Notification::create([
            'user_id' => $request->assigned_to,
            'shipment_id' => $issue->shipment_id,
            'title' => 'Issue Assigned to You',
            'message' => "You have been assigned issue #ISS-" . str_pad($issue->id, 5, '0', STR_PAD_LEFT) . " for shipment {$issue->shipment->tracking_number}",
            'type' => 'info',
            'channel' => 'system',
            'data' => json_encode([
                'issue_id' => $issue->id,
            ]),
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'issue_assigned',
            'model_type' => 'ShipmentIssue',
            'model_id' => $issue->id,
            'description' => "Issue assigned to " . User::find($request->assigned_to)->name,
            'new_values' => json_encode([
                'assigned_to' => $request->assigned_to,
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Issue assigned successfully.');
    }

    /**
     * Update issue status
     */
    public function issuesupdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,investigating,resolved,closed',
            'resolution' => 'required_if:status,resolved|nullable|string|max:1000',
        ]);

        $issue = ShipmentIssue::findOrFail($id);
        
        $updateData = [
            'status' => $request->status,
        ];

        // If marking as resolved
        if ($request->status === 'resolved') {
            $updateData['resolution'] = $request->resolution;
            $updateData['resolved_at'] = now();
            
            if (!$issue->assigned_to) {
                $updateData['assigned_to'] = auth()->id();
            }

            // Notify customer
            if ($issue->shipment->customer_id) {
                Notification::create([
                    'user_id' => $issue->shipment->customer_id,
                    'shipment_id' => $issue->shipment_id,
                    'title' => 'Issue Resolved',
                    'message' => "Your reported issue for shipment {$issue->shipment->tracking_number} has been resolved: {$request->resolution}",
                    'type' => 'success',
                    'channel' => 'system',
                ]);
            }
        }

        // If starting investigation
        if ($request->status === 'investigating' && !$issue->assigned_to) {
            $updateData['assigned_to'] = auth()->id();
        }

        $issue->update($updateData);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'issue_status_updated',
            'model_type' => 'ShipmentIssue',
            'model_id' => $issue->id,
            'description' => "Issue status changed to {$request->status}",
            'old_values' => json_encode(['status' => $issue->getOriginal('status')]),
            'new_values' => json_encode([
                'status' => $request->status,
                'resolution' => $request->resolution,
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Issue status updated successfully.');
    }

    /**
     * Export issues to CSV
     */
    public function issuesexport()
    {
        $issues = ShipmentIssue::with(['shipment', 'reportedBy', 'assignedTo'])->get();

        $filename = 'issues_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($issues) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Issue ID',
                'Tracking Number',
                'Issue Type',
                'Priority',
                'Status',
                'Reported By',
                'Assigned To',
                'Created At',
                'Resolved At',
                'Description',
                'Resolution'
            ]);

            // Data
            foreach ($issues as $issue) {
                fputcsv($file, [
                    'ISS-' . str_pad($issue->id, 5, '0', STR_PAD_LEFT),
                    $issue->shipment->tracking_number,
                    $issue->issue_type,
                    $issue->priority,
                    $issue->status,
                    $issue->reportedBy?->name ?? 'Guest',
                    $issue->assignedTo?->name ?? 'Unassigned',
                    $issue->created_at->format('Y-m-d H:i:s'),
                    $issue->resolved_at?->format('Y-m-d H:i:s') ?? '',
                    $issue->description,
                    $issue->resolution ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}