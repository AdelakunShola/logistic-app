<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Models\Shipment;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupportTicketController extends Controller
{
    /**
     * Display support tickets list (admin)
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'shipment', 'assignedTo']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by assigned
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
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('shipment', function ($sq) use ($search) {
                      $sq->where('tracking_number', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'escalated' => SupportTicket::where('status', 'escalated')->count(),
            'avg_resolution_hours' => $this->calculateAvgResolutionTime(),
        ];

        $supportUsers = User::whereIn('role', ['admin'])->get();
        $categories = ['delivery_issue', 'payment_issue', 'tracking', 'complaint', 'inquiry', 'technical', 'other'];

        return view('backend.support.tickets.index', compact('tickets', 'stats', 'supportUsers', 'categories'));
    }

    /**
     * Show ticket detail (admin)
     */
    public function show($id)
    {
        $ticket = SupportTicket::with([
            'user',
            'shipment',
            'assignedTo',
            'messages' => function ($q) {
                $q->with('user')->orderBy('created_at', 'asc');
            }
        ])->findOrFail($id);

        $supportUsers = User::whereIn('role', ['admin'])->get();

        return view('backend.support.tickets.show', compact('ticket', 'supportUsers'));
    }

    /**
     * Show create ticket form (admin)
     */
    public function create()
    {
        $customers = User::where('role', 'customer')->where('status', 'active')->get();
        $shipments = Shipment::orderBy('created_at', 'desc')->limit(100)->get();

        return view('backend.support.tickets.create', compact('customers', 'shipments'));
    }

    /**
     * Store new ticket (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'shipment_id' => 'nullable|exists:shipments,id',
            'category' => 'required|in:delivery_issue,payment_issue,tracking,complaint,inquiry,technical,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['ticket_number'] = 'TKT-' . str_pad(SupportTicket::withTrashed()->count() + 1, 6, '0', STR_PAD_LEFT);
            $validated['status'] = 'open';

            $ticket = SupportTicket::create($validated);

            // Create initial message from description
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $validated['user_id'] ?? auth()->id(),
                'message' => $validated['description'],
                'sender_type' => 'customer',
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'model_type' => 'SupportTicket',
                'model_id' => $ticket->id,
                'description' => "Support ticket {$ticket->ticket_number} created: {$ticket->subject}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.support-tickets.show', $ticket->id)
                ->with('success', 'Support ticket created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create ticket: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update ticket
     */
    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'priority' => 'nullable|in:low,medium,high,urgent',
            'category' => 'nullable|in:delivery_issue,payment_issue,tracking,complaint,inquiry,technical,other',
            'subject' => 'nullable|string|max:255',
        ]);

        $ticket->update(array_filter($validated));

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }

    /**
     * Delete ticket
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.support-tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Assign ticket to support agent
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        $updateData = [
            'assigned_to' => $request->assigned_to,
            'status' => $ticket->status === 'open' ? 'in_progress' : $ticket->status,
        ];

        if ($request->filled('priority')) {
            $updateData['priority'] = $request->priority;
        }

        $ticket->update($updateData);

        // Notify assigned user
        Notification::create([
            'user_id' => $request->assigned_to,
            'shipment_id' => $ticket->shipment_id,
            'title' => 'Support Ticket Assigned',
            'message' => "You have been assigned ticket {$ticket->ticket_number}: {$ticket->subject}",
            'type' => 'info',
            'channel' => 'system',
            'data' => json_encode(['ticket_id' => $ticket->id]),
        ]);

        // Add system message
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => 'Ticket assigned to ' . User::find($request->assigned_to)->first_name . ' ' . User::find($request->assigned_to)->last_name,
            'sender_type' => 'system',
            'is_internal' => true,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'ticket_assigned',
            'model_type' => 'SupportTicket',
            'model_id' => $ticket->id,
            'description' => "Ticket {$ticket->ticket_number} assigned to " . User::find($request->assigned_to)->first_name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Ticket assigned successfully.');
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed,escalated',
            'resolution' => 'required_if:status,resolved|nullable|string|max:2000',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $oldStatus = $ticket->status;

        $updateData = ['status' => $request->status];

        if ($request->status === 'resolved') {
            $updateData['resolution'] = $request->resolution;
            $updateData['resolved_at'] = now();

            if (!$ticket->assigned_to) {
                $updateData['assigned_to'] = auth()->id();
            }

            // Notify customer
            if ($ticket->user_id) {
                Notification::create([
                    'user_id' => $ticket->user_id,
                    'shipment_id' => $ticket->shipment_id,
                    'title' => 'Ticket Resolved',
                    'message' => "Your support ticket {$ticket->ticket_number} has been resolved: {$request->resolution}",
                    'type' => 'success',
                    'channel' => 'system',
                ]);
            }
        }

        if ($request->status === 'escalated') {
            // Notify all admins about escalation
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'shipment_id' => $ticket->shipment_id,
                    'title' => 'Ticket Escalated',
                    'message' => "Ticket {$ticket->ticket_number} has been escalated: {$ticket->subject}",
                    'type' => 'error',
                    'channel' => 'system',
                ]);
            }
        }

        if ($request->status === 'in_progress' && !$ticket->assigned_to) {
            $updateData['assigned_to'] = auth()->id();
        }

        $ticket->update($updateData);

        // Add system message for status change
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => "Status changed from " . ucfirst(str_replace('_', ' ', $oldStatus)) . " to " . ucfirst(str_replace('_', ' ', $request->status)) .
                ($request->resolution ? ". Resolution: {$request->resolution}" : ''),
            'sender_type' => 'system',
            'is_internal' => true,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'ticket_status_updated',
            'model_type' => 'SupportTicket',
            'model_id' => $ticket->id,
            'description' => "Ticket {$ticket->ticket_number} status changed to {$request->status}",
            'old_values' => json_encode(['status' => $oldStatus]),
            'new_values' => json_encode(['status' => $request->status, 'resolution' => $request->resolution]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Ticket status updated successfully.');
    }

    /**
     * Add message to ticket thread
     */
    public function addMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'is_internal' => 'boolean',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'sender_type' => 'admin',
            'is_internal' => $request->boolean('is_internal', false),
        ]);

        // If ticket was open, set to in_progress
        if ($ticket->status === 'open') {
            $ticket->update([
                'status' => 'in_progress',
                'assigned_to' => $ticket->assigned_to ?? auth()->id(),
            ]);
        }

        // Notify customer (only for non-internal messages)
        if (!$request->boolean('is_internal') && $ticket->user_id) {
            Notification::create([
                'user_id' => $ticket->user_id,
                'shipment_id' => $ticket->shipment_id,
                'title' => 'New Reply on Your Ticket',
                'message' => "A new reply was added to your ticket {$ticket->ticket_number}",
                'type' => 'info',
                'channel' => 'system',
            ]);
        }

        return redirect()->back()->with('success', 'Message added successfully.');
    }

    /**
     * Export tickets to CSV
     */
    public function export()
    {
        $tickets = SupportTicket::with(['user', 'shipment', 'assignedTo'])->get();

        $filename = 'support_tickets_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Ticket #', 'Subject', 'Category', 'Priority', 'Status',
                'Customer', 'Email', 'Shipment', 'Assigned To',
                'Created', 'Resolved', 'Resolution',
            ]);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->subject,
                    ucfirst(str_replace('_', ' ', $ticket->category)),
                    ucfirst($ticket->priority),
                    ucfirst(str_replace('_', ' ', $ticket->status)),
                    $ticket->customer_name ?? ($ticket->user ? $ticket->user->first_name . ' ' . $ticket->user->last_name : 'N/A'),
                    $ticket->customer_email ?? $ticket->user?->email ?? 'N/A',
                    $ticket->shipment?->tracking_number ?? 'N/A',
                    $ticket->assignedTo ? $ticket->assignedTo->first_name . ' ' . $ticket->assignedTo->last_name : 'Unassigned',
                    $ticket->created_at->format('Y-m-d H:i:s'),
                    $ticket->resolved_at?->format('Y-m-d H:i:s') ?? '',
                    $ticket->resolution ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // =============================================
    // CUSTOMER / DRIVER FACING METHODS
    // =============================================

    /**
     * Customer/Driver: List their tickets
     */
    public function customerIndex(Request $request)
    {
        $user = auth()->user();

        $query = SupportTicket::with(['shipment', 'assignedTo'])
            ->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => SupportTicket::where('user_id', $user->id)->count(),
            'open' => SupportTicket::where('user_id', $user->id)->where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('user_id', $user->id)->where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('user_id', $user->id)->where('status', 'resolved')->count(),
        ];

        $viewPrefix = $user->role === 'driver' ? 'driver.support.tickets' : 'customer.support.tickets';

        return view($viewPrefix . '.index', compact('tickets', 'stats'));
    }

    /**
     * Customer/Driver: Show create ticket form
     */
    public function customerCreate()
    {
        $user = auth()->user();

        // Get shipments belonging to this user (customer) or assigned to them (driver)
        if ($user->role === 'driver') {
            $shipments = Shipment::where('assigned_driver_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();
        } else {
            $shipments = Shipment::where('customer_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();
        }

        $viewPrefix = $user->role === 'driver' ? 'driver.support.tickets' : 'customer.support.tickets';

        return view($viewPrefix . '.create', compact('shipments'));
    }

    /**
     * Customer/Driver: Store a new ticket
     */
    public function customerStore(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'shipment_id' => 'nullable|exists:shipments,id',
            'category' => 'required|in:delivery_issue,payment_issue,tracking,complaint,inquiry,technical,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['user_id'] = $user->id;
            $validated['customer_name'] = $user->first_name . ' ' . $user->last_name;
            $validated['customer_email'] = $user->email;
            $validated['customer_phone'] = $user->phone;
            $validated['ticket_number'] = 'TKT-' . str_pad(SupportTicket::withTrashed()->count() + 1, 6, '0', STR_PAD_LEFT);
            $validated['status'] = 'open';

            $ticket = SupportTicket::create($validated);

            // Create initial message
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'message' => $validated['description'],
                'sender_type' => 'customer',
            ]);

            // Notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'shipment_id' => $validated['shipment_id'] ?? null,
                    'title' => 'New Support Ticket',
                    'message' => "{$user->first_name} {$user->last_name} submitted ticket {$ticket->ticket_number}: {$ticket->subject}",
                    'type' => $validated['priority'] === 'urgent' ? 'error' : 'info',
                    'channel' => 'system',
                ]);
            }

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'created',
                'model_type' => 'SupportTicket',
                'model_id' => $ticket->id,
                'description' => "Support ticket {$ticket->ticket_number} created by {$user->role}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            $routePrefix = $user->role === 'driver' ? 'driver' : 'user';

            return redirect()->route("{$routePrefix}.support-tickets.show", $ticket->id)
                ->with('success', 'Support ticket submitted successfully. We\'ll get back to you soon.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create ticket: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Customer/Driver: View their ticket detail
     */
    public function customerShow($id)
    {
        $user = auth()->user();

        $ticket = SupportTicket::with([
            'shipment',
            'assignedTo',
            'messages' => function ($q) {
                $q->where('is_internal', false)->with('user')->orderBy('created_at', 'asc');
            }
        ])->where('user_id', $user->id)->findOrFail($id);

        $viewPrefix = $user->role === 'driver' ? 'driver.support.tickets' : 'customer.support.tickets';

        return view($viewPrefix . '.show', compact('ticket'));
    }

    /**
     * Customer/Driver: Add a message to their ticket
     */
    public function customerAddMessage(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $ticket = SupportTicket::where('user_id', $user->id)->findOrFail($id);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'sender_type' => 'customer',
        ]);

        // Notify assigned agent
        if ($ticket->assigned_to) {
            Notification::create([
                'user_id' => $ticket->assigned_to,
                'shipment_id' => $ticket->shipment_id,
                'title' => 'New Reply on Ticket',
                'message' => "New reply on ticket {$ticket->ticket_number} from {$user->first_name}",
                'type' => 'info',
                'channel' => 'system',
            ]);
        }

        return redirect()->back()->with('success', 'Message sent successfully.');
    }

    /**
     * Calculate average resolution time in hours
     */
    private function calculateAvgResolutionTime()
    {
        $avg = SupportTicket::whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
            ->value('avg_hours');

        return round($avg ?? 0, 1);
    }
}
