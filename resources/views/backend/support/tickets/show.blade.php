@extends('admin.admin_dashboard')
@section('admin')

<style>
    .priority-urgent { border-left: 4px solid #dc2626; }
    .priority-high { border-left: 4px solid #ea580c; }
    .priority-medium { border-left: 4px solid #f59e0b; }
    .priority-low { border-left: 4px solid #3b82f6; }
    
    .status-badge-open { background: #dbeafe; color: #1e40af; }
    .status-badge-in_progress { background: #fef3c7; color: #92400e; }
    .status-badge-resolved { background: #d1fae5; color: #065f46; }
    .status-badge-closed { background: #e5e7eb; color: #374151; }
    .status-badge-escalated { background: #fee2e2; color: #991b1b; }
    
    .priority-badge-urgent { background: #fee2e2; color: #991b1b; }
    .priority-badge-high { background: #ffedd5; color: #9a3412; }
    .priority-badge-medium { background: #fef3c7; color: #92400e; }
    .priority-badge-low { background: #dbeafe; color: #1e40af; }
</style>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.support-tickets.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Back to Tickets
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Ticket Header -->
                <div class="bg-white rounded-lg shadow-sm border priority-{{ $ticket->priority }}">
                    <div class="p-6 border-b">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <span class="priority-badge-{{ $ticket->priority }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                    {{ $ticket->priority }}
                                </span>
                                <span class="status-badge-{{ $ticket->status }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $ticket->ticket_number }}</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.support-tickets.edit', $ticket->id) }}" class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50 transition-colors">
                                    Edit
                                </a>
                            </div>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $ticket->subject }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                            <span>Created {{ $ticket->created_at->diffForHumans() }}</span>
                            <span>•</span>
                            <span>Category: {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="font-semibold mb-2">Description</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>
                </div>

                <!-- Resolution Section -->
                @if($ticket->status === 'resolved' && $ticket->resolution)
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-start gap-3">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-green-900 mb-2">Ticket Resolved</h3>
                            <p class="text-sm text-green-800 mb-2">{{ $ticket->resolution }}</p>
                            @if($ticket->resolved_at)
                            <p class="text-xs text-green-700">Resolved on {{ $ticket->resolved_at->format('M d, Y \a\t g:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Messages/Conversation -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h3 class="font-semibold">Conversation</h3>
                    </div>
                    <div class="divide-y max-h-96 overflow-y-auto">
                        @forelse($ticket->messages as $message)
                        <div class="p-4 {{ $message->is_internal ? 'bg-yellow-50' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-sm font-semibold text-blue-700">
                                    @if($message->user)
                                        {{ strtoupper(substr($message->user->first_name ?? 'U', 0, 1) . substr($message->user->last_name ?? '', 0, 1)) }}
                                    @else
                                        U
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-medium text-gray-900">
                                            @if($message->user)
                                                {{ $message->user->first_name }} {{ $message->user->last_name }}
                                            @else
                                                Unknown User
                                            @endif
                                        </span>
                                        @if($message->is_internal)
                                        <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded">Internal Note</span>
                                        @endif
                                        <span class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center text-gray-500">
                            <p>No messages yet</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Add Message Form -->
                    <div class="p-6 border-t bg-gray-50">
                        <form action="{{ route('admin.support-tickets.add-message', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <textarea 
                                    name="message" 
                                    rows="3" 
                                    placeholder="Add a reply or internal note..."
                                    required
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                ></textarea>
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" name="is_internal" value="1" class="rounded">
                                        <span>Internal note (visible only to staff)</span>
                                    </label>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Update Status Form -->
                @if(!in_array($ticket->status, ['closed']))
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Update Status</h3>
                    
                    <form action="{{ route('admin.support-tickets.update-status', $ticket->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Status</label>
                            <select name="status" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="escalated" {{ $ticket->status == 'escalated' ? 'selected' : '' }}>Escalated</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        
                        <div id="resolutionField" class="{{ $ticket->status == 'resolved' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium mb-2">Resolution Details <span class="text-red-500">*</span></label>
                            <textarea 
                                name="resolution" 
                                rows="4" 
                                placeholder="Describe how the ticket was resolved..."
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            >{{ $ticket->resolution }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">This will be sent to the customer (if email notifications are enabled)</p>
                        </div>
                        
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Update Status
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                        @if(!$ticket->assignedTo)
                        <button onclick="openAssignModal()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                            Assign Ticket
                        </button>
                        @elseif($ticket->assigned_to == auth()->id())
                        <p class="text-sm text-gray-600">✅ You are assigned to this ticket.</p>
                        @else
                        <p class="text-sm text-gray-600">👤 Assigned to: {{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}</p>
                        <button onclick="openAssignModal()" class="w-full px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                            Reassign
                        </button>
                        @endif
                        
                        @if($ticket->shipment)
                        <a href="{{ route('admin.shipments.show', $ticket->shipment->id) }}" class="block w-full px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-center">
                            View Shipment
                        </a>
                        @endif
                        
                        @if($ticket->user)
                        <a href="mailto:{{ $ticket->customer_email ?? $ticket->user->email }}" class="block w-full px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-center">
                            Contact Customer
                        </a>
                        @endif

                        <form action="{{ route('admin.support-tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                Delete Ticket
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Ticket Details</h3>
                    
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Ticket Number</p>
                            <p class="font-mono font-medium">{{ $ticket->ticket_number }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Priority</p>
                            <span class="priority-badge-{{ $ticket->priority }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                {{ $ticket->priority }}
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Status</p>
                            <span class="status-badge-{{ $ticket->status }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Category</p>
                            <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Assigned To</p>
                            <p class="font-medium">
                                @if($ticket->assignedTo)
                                    {{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}
                                @else
                                    <span class="text-gray-500 italic">Unassigned</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Customer</p>
                            <p class="font-medium">{{ $ticket->customer_name ?? ($ticket->user ? $ticket->user->first_name . ' ' . $ticket->user->last_name : 'Guest') }}</p>
                            @if($ticket->customer_email ?? $ticket->user?->email)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->customer_email ?? $ticket->user->email }}</p>
                            @endif
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Created</p>
                            <p class="font-medium">{{ $ticket->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->created_at->format('g:i A') }}</p>
                        </div>
                        
                        @if($ticket->resolved_at)
                        <div>
                            <p class="text-gray-600 mb-1">Resolved</p>
                            <p class="font-medium">{{ $ticket->resolved_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->resolved_at->format('g:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Shipment Info -->
                @if($ticket->shipment)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Related Shipment</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Tracking Number</p>
                            <p class="font-mono font-medium">{{ $ticket->shipment->tracking_number }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Status</p>
                            <p class="font-medium capitalize">{{ str_replace('_', ' ', $ticket->shipment->status) }}</p>
                        </div>
                        
                        <a href="{{ route('admin.shipments.show', $ticket->shipment->id) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
                            View Shipment Details
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-semibold">Assign Ticket</h3>
            <button onclick="closeAssignModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.support-tickets.assign', $ticket->id) }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Assign to</label>
                    <select name="assigned_to" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select staff member</option>
                        @foreach($supportUsers as $user)
                            <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Priority (optional)</label>
                    <select name="priority" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Keep current</option>
                        <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAssignModal()" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Assign
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openAssignModal() {
        document.getElementById('assignModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAssignModal() {
        document.getElementById('assignModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('assignModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAssignModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAssignModal();
        }
    });

    // Show/hide resolution field based on status
    document.querySelector('select[name="status"]')?.addEventListener('change', function() {
        const resolutionField = document.getElementById('resolutionField');
        const resolutionTextarea = resolutionField?.querySelector('textarea');
        
        if (this.value === 'resolved') {
            resolutionField?.classList.remove('hidden');
            if (resolutionTextarea) resolutionTextarea.required = true;
        } else {
            resolutionField?.classList.add('hidden');
            if (resolutionTextarea) resolutionTextarea.required = false;
        }
    });
</script>

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif

@if(session('error'))
    <script>
        alert('{{ session('error') }}');
    </script>
@endif

@endsection