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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SupportTicketController extends Controller
{
    /**
     * Display support tickets list
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo', 'shipment']);

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
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhereHas('shipment', function($sq) use ($search) {
                      $sq->where('tracking_number', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->except('page'));

        // Get statistics
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'escalated' => SupportTicket::where('status', 'escalated')->count(),
        ];

        // Get support users for assignment dropdown
        $supportUsers = User::whereIn('role', ['admin', 'manager', 'dispatcher'])->get();

        return view('backend.support.tickets.index', compact('tickets', 'stats', 'supportUsers'));
    }

    /**
     * Show create ticket form
     */
    public function create()
    {
        $users = User::where('role', '!=', 'driver')->get();
        $shipments = Shipment::latest()->limit(100)->get(['id', 'tracking_number', 'customer_id']);
        
        return view('backend.support.tickets.create', compact('users', 'shipments'));
    }

    /**
     * Store new ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'shipment_id' => 'nullable|exists:shipments,id',
            'category' => 'required|in:delivery_issue,payment_issue,tracking,complaint,inquiry,technical,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            // Generate ticket number (handled by model boot)
            $ticket = SupportTicket::create($validated);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'model_type' => 'SupportTicket',
                'model_id' => $ticket->id,
                'description' => "Created support ticket {$ticket->ticket_number}",
                'new_values' => json_encode($validated),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Notify assigned user if exists
            if ($ticket->assigned_to) {
                Notification::create([
                    'user_id' => $ticket->assigned_to,
                    'title' => 'New Ticket Assigned',
                    'message' => "You have been assigned support ticket {$ticket->ticket_number}: {$ticket->subject}",
                    'type' => 'info',
                    'channel' => 'system',
                ]);
            }

            // Notify customer if user_id exists
            if ($ticket->user_id) {
                Notification::create([
                    'user_id' => $ticket->user_id,
                    'title' => 'Support Ticket Created',
                    'message' => "Your support ticket {$ticket->ticket_number} has been created. We'll get back to you soon.",
                    'type' => 'info',
                    'channel' => 'system',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.support-tickets.show', $ticket->id)
                ->with('success', "Support ticket {$ticket->ticket_number} created successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Support ticket creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to create support ticket: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display ticket details
     */
    public function show($id)
    {
        $ticket = SupportTicket::with([
            'user',
            'assignedTo',
            'shipment.customer',
            'messages.user'
        ])->findOrFail($id);

        $supportUsers = User::whereIn('role', ['admin', 'manager', 'dispatcher'])->get();

        return view('backend.support.tickets.show', compact('ticket', 'supportUsers'));
    }

    /**
     * Show edit ticket form
     */
    public function edit($id)
    {
        $ticket = SupportTicket::with(['user', 'assignedTo', 'shipment'])->findOrFail($id);
        $users = User::where('role', '!=', 'driver')->get();
        $shipments = Shipment::latest()->limit(100)->get(['id', 'tracking_number', 'customer_id']);
        $supportUsers = User::whereIn('role', ['admin', 'manager', 'dispatcher'])->get();

        return view('backend.support.tickets.edit', compact('ticket', 'users', 'shipments', 'supportUsers'));
    }

    /**
     * Update ticket
     */
    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'shipment_id' => 'nullable|exists:shipments,id',
            'category' => 'required|in:delivery_issue,payment_issue,tracking,complaint,inquiry,technical,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,resolved,closed,escalated',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $ticket->toArray();
            
            // Handle resolution
            if ($validated['status'] === 'resolved' && !empty($validated['resolution'])) {
                $validated['resolved_at'] = now();
            }

            // If status changed from resolved/closed to something else, clear resolved_at
            if (!in_array($validated['status'], ['resolved', 'closed']) && $ticket->status !== $validated['status']) {
                $validated['resolved_at'] = null;
            }

            $ticket->update($validated);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'model_type' => 'SupportTicket',
                'model_id' => $ticket->id,
                'description' => "Updated support ticket {$ticket->ticket_number}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($validated),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Notify if assigned to changed
            if (isset($validated['assigned_to']) && $validated['assigned_to'] != $oldValues['assigned_to'] && $validated['assigned_to']) {
                Notification::create([
                    'user_id' => $validated['assigned_to'],
                    'title' => 'Ticket Assigned',
                    'message' => "You have been assigned support ticket {$ticket->ticket_number}: {$ticket->subject}",
                    'type' => 'info',
                    'channel' => 'system',
                ]);
            }

            // Notify customer if resolved
            if ($validated['status'] === 'resolved' && $ticket->user_id) {
                Notification::create([
                    'user_id' => $ticket->user_id,
                    'title' => 'Ticket Resolved',
                    'message' => "Your support ticket {$ticket->ticket_number} has been resolved.",
                    'type' => 'success',
                    'channel' => 'system',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.support-tickets.show', $ticket->id)
                ->with('success', 'Support ticket updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Support ticket update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to update support ticket: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete ticket
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);

        DB::beginTransaction();
        try {
            $ticketNumber = $ticket->ticket_number;
            $ticket->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'model_type' => 'SupportTicket',
                'model_id' => $id,
                'description' => "Deleted support ticket {$ticketNumber}",
                'old_values' => json_encode($ticket->toArray()),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.support-tickets.index')
                ->with('success', 'Support ticket deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Support ticket deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete support ticket: ' . $e->getMessage());
        }
    }

    /**
     * Assign ticket to user
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
            'title' => 'Ticket Assigned to You',
            'message' => "You have been assigned support ticket {$ticket->ticket_number}: {$ticket->subject}",
            'type' => 'info',
            'channel' => 'system',
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'assigned',
            'model_type' => 'SupportTicket',
            'model_id' => $ticket->id,
            'description' => "Assigned ticket {$ticket->ticket_number} to " . User::find($request->assigned_to)->first_name . ' ' . User::find($request->assigned_to)->last_name,
            'new_values' => json_encode(['assigned_to' => $request->assigned_to]),
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
            'resolution' => 'required_if:status,resolved|nullable|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        
        $updateData = [
            'status' => $request->status,
        ];

        // If marking as resolved
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
                    'title' => 'Ticket Resolved',
                    'message' => "Your support ticket {$ticket->ticket_number} has been resolved: {$request->resolution}",
                    'type' => 'success',
                    'channel' => 'system',
                ]);
            }
        } else {
            // Clear resolved_at if not resolved
            $updateData['resolved_at'] = null;
        }

        // If starting work
        if ($request->status === 'in_progress' && !$ticket->assigned_to) {
            $updateData['assigned_to'] = auth()->id();
        }

        $ticket->update($updateData);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'status_updated',
            'model_type' => 'SupportTicket',
            'model_id' => $ticket->id,
            'description' => "Updated ticket {$ticket->ticket_number} status to {$request->status}",
            'old_values' => json_encode(['status' => $ticket->getOriginal('status')]),
            'new_values' => json_encode($updateData),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Ticket status updated successfully.');
    }

    /**
     * Add message/reply to ticket
     */
    public function addMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'is_internal' => 'nullable|boolean',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        DB::beginTransaction();
        try {
            $ticketMessage = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $request->message,
                'sender_type' => auth()->user()->isAdmin() || auth()->user()->role === 'manager' ? 'admin' : 'customer',
                'is_internal' => $request->is_internal ?? false,
            ]);

            // Update ticket status if it was closed/resolved
            if (in_array($ticket->status, ['closed', 'resolved'])) {
                $ticket->update(['status' => 'in_progress', 'resolved_at' => null]);
            }

            // Notify assigned user if message is not from them
            if ($ticket->assigned_to && $ticket->assigned_to != auth()->id() && !$request->is_internal) {
                Notification::create([
                    'user_id' => $ticket->assigned_to,
                    'title' => 'New Message on Ticket',
                    'message' => "New message on ticket {$ticket->ticket_number}: {$ticket->subject}",
                    'type' => 'info',
                    'channel' => 'system',
                ]);
            }

            // Notify customer if admin replied
            if (auth()->user()->isAdmin() && $ticket->user_id && !$request->is_internal) {
                Notification::create([
                    'user_id' => $ticket->user_id,
                    'title' => 'Response to Your Ticket',
                    'message' => "You have a new response on ticket {$ticket->ticket_number}",
                    'type' => 'info',
                    'channel' => 'system',
                ]);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'message_added',
                'model_type' => 'SupportTicket',
                'model_id' => $ticket->id,
                'description' => "Added message to ticket {$ticket->ticket_number}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Message added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add message failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to add message: ' . $e->getMessage());
        }
    }

    /**
     * Export tickets to CSV
     */
    public function export()
    {
        $tickets = SupportTicket::with(['user', 'assignedTo', 'shipment'])->get();

        $filename = 'support_tickets_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Ticket Number',
                'Subject',
                'Category',
                'Priority',
                'Status',
                'Customer Name',
                'Customer Email',
                'Assigned To',
                'Created At',
                'Resolved At',
                'Resolution'
            ]);

            // Data
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->subject,
                    $ticket->category,
                    $ticket->priority,
                    $ticket->status,
                    $ticket->customer_name ?? ($ticket->user ? $ticket->user->first_name . ' ' . $ticket->user->last_name : 'N/A'),
                    $ticket->customer_email ?? ($ticket->user ? $ticket->user->email : 'N/A'),
                    $ticket->assignedTo ? $ticket->assignedTo->first_name . ' ' . $ticket->assignedTo->last_name : 'Unassigned',
                    $ticket->created_at->format('Y-m-d H:i:s'),
                    $ticket->resolved_at?->format('Y-m-d H:i:s') ?? '',
                    $ticket->resolution ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Customer-facing methods
     */

    /**
     * Display customer's tickets
     */
    public function customerIndex(Request $request)
    {
        $query = SupportTicket::with(['assignedTo', 'shipment'])
            ->where('user_id', auth()->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics for customer
        $stats = [
            'total' => SupportTicket::where('user_id', auth()->id())->count(),
            'open' => SupportTicket::where('user_id', auth()->id())->where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('user_id', auth()->id())->where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('user_id', auth()->id())->where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('user_id', auth()->id())->where('status', 'closed')->count(),
        ];

        return view('customer.support.tickets.index', compact('tickets', 'stats'));
    }

    /**
     * Show create ticket form for customer
     */
    public function customerCreate()
    {
        // Get customer's shipments
        $shipments = Shipment::where('customer_id', auth()->id())
            ->latest()
            ->limit(50)
            ->get(['id', 'tracking_number', 'status']);

        return view('customer.support.tickets.create', compact('shipments'));
    }

    /**
     * Store customer's ticket
     */
    public function customerStore(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'nullable|exists:shipments,id',
            'category' => 'required|in:delivery_issue,payment_issue,tracking,complaint,inquiry,technical,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Ensure shipment belongs to customer if provided
        if ($request->filled('shipment_id')) {
            $shipment = Shipment::findOrFail($request->shipment_id);
            if ($shipment->customer_id !== auth()->id()) {
                return redirect()->back()->with('error', 'Unauthorized access to shipment.')->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $ticketData = array_merge($validated, [
                'user_id' => auth()->id(),
                'customer_name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                'customer_email' => auth()->user()->email,
                'customer_phone' => auth()->user()->phone,
                'status' => 'open',
            ]);

            $ticket = SupportTicket::create($ticketData);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'model_type' => 'SupportTicket',
                'model_id' => $ticket->id,
                'description' => "Customer created support ticket {$ticket->ticket_number}",
                'new_values' => json_encode($validated),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Notify customer
            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Support Ticket Created',
                'message' => "Your support ticket {$ticket->ticket_number} has been created. We'll get back to you soon.",
                'type' => 'info',
                'channel' => 'system',
            ]);

            DB::commit();

            return redirect()->route('user.support-tickets.show', $ticket->id)
                ->with('success', "Support ticket {$ticket->ticket_number} created successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer support ticket creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to create support ticket: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display customer's ticket details
     */
    public function customerShow($id)
    {
        $ticket = SupportTicket::with([
            'user',
            'assignedTo',
            'shipment',
            'messages.user'
        ])->where('user_id', auth()->id())->findOrFail($id);

        return view('customer.support.tickets.show', compact('ticket'));
    }

    /**
     * Add message/reply to ticket (customer)
     */
    public function customerAddMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::where('user_id', auth()->id())->findOrFail($id);

        DB::beginTransaction();
        try {
            $ticketMessage = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $request->message,
                'sender_type' => 'customer',
                'is_internal' => false,
            ]);

            // Update ticket status if it was closed/resolved
            if (in_array($ticket->status, ['closed', 'resolved'])) {
                $ticket->update(['status' => 'in_progress', 'resolved_at' => null]);
            }

            // Notify assigned staff if exists
            if ($ticket->assigned_to) {
                Notification::create([
                    'user_id' => $ticket->assigned_to,
                    'title' => 'New Message on Ticket',
                    'message' => "New message on ticket {$ticket->ticket_number}: {$ticket->subject}",
                    'type' => 'info',
                    'channel' => 'system',
                ]);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'message_added',
                'model_type' => 'SupportTicket',
                'model_id' => $ticket->id,
                'description' => "Customer added message to ticket {$ticket->ticket_number}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Message sent successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer message add failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to send message: ' . $e->getMessage());
        }
    }
}