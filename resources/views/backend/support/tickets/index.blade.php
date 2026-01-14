@extends('admin.admin_dashboard')
@section('admin')

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                            <path d="M13 5v2"></path>
                            <path d="M13 17v2"></path>
                            <path d="M13 11v2"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Support Tickets</h1>
                        <p class="text-sm text-gray-500">Manage and track customer support requests</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.support-tickets.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export
                    </a>
                    <a href="{{ route('admin.support-tickets.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        New Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tickets</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Open</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['open'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="6"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">In Progress</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['in_progress'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-600">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Resolved</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['resolved'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Closed</p>
                        <p class="text-3xl font-bold text-gray-600 mt-2">{{ $stats['closed'] }}</p>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Escalated</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['escalated'] }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Tickets List -->
        <div class="bg-white rounded-lg shadow-sm border">
            <!-- Tabs -->
            <div class="border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex gap-6">
                        <a href="{{ route('admin.support-tickets.index') }}" class="py-2 text-sm {{ !request('status') ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            All ({{ $stats['total'] }})
                        </a>
                        <a href="{{ route('admin.support-tickets.index', ['status' => 'open']) }}" class="py-2 text-sm {{ request('status') == 'open' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            Open ({{ $stats['open'] }})
                        </a>
                        <a href="{{ route('admin.support-tickets.index', ['status' => 'in_progress']) }}" class="py-2 text-sm {{ request('status') == 'in_progress' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            In Progress ({{ $stats['in_progress'] }})
                        </a>
                        <a href="{{ route('admin.support-tickets.index', ['status' => 'resolved']) }}" class="py-2 text-sm {{ request('status') == 'resolved' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            Resolved ({{ $stats['resolved'] }})
                        </a>
                        <a href="{{ route('admin.support-tickets.index', ['status' => 'closed']) }}" class="py-2 text-sm {{ request('status') == 'closed' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            Closed ({{ $stats['closed'] }})
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="p-6 border-b bg-gray-50">
                <form method="GET" action="{{ route('admin.support-tickets.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by ticket number, subject, customer..." 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <select name="priority" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Priorities</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                    <select name="category" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        <option value="delivery_issue" {{ request('category') == 'delivery_issue' ? 'selected' : '' }}>Delivery Issue</option>
                        <option value="payment_issue" {{ request('category') == 'payment_issue' ? 'selected' : '' }}>Payment Issue</option>
                        <option value="tracking" {{ request('category') == 'tracking' ? 'selected' : '' }}>Tracking</option>
                        <option value="complaint" {{ request('category') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                        <option value="inquiry" {{ request('category') == 'inquiry' ? 'selected' : '' }}>Inquiry</option>
                        <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <select name="assigned" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Assignees</option>
                        <option value="unassigned" {{ request('assigned') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                        <option value="me" {{ request('assigned') == 'me' ? 'selected' : '' }}>Assigned to Me</option>
                        @foreach($supportUsers as $user)
                            <option value="{{ $user->id }}" {{ request('assigned') == $user->id ? 'selected' : '' }}>{{ $user->first_name }} {{ $user->last_name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['search', 'priority', 'category', 'assigned', 'status']))
                        <a href="{{ route('admin.support-tickets.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Tickets List -->
            <div class="divide-y">
                @forelse($tickets as $ticket)
                <div class="p-6 hover:bg-gray-50 transition-colors priority-{{ $ticket->priority }} cursor-pointer" onclick="window.location='{{ route('admin.support-tickets.show', $ticket->id) }}'">
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="priority-badge-{{ $ticket->priority }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                    {{ $ticket->priority }}
                                </span>
                                <span class="status-badge-{{ $ticket->status }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $ticket->ticket_number }}</span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $ticket->subject }}</h3>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $ticket->description }}</p>
                            
                            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span>Customer: <span class="font-medium text-gray-700">{{ $ticket->customer_name ?? ($ticket->user ? $ticket->user->first_name . ' ' . $ticket->user->last_name : 'Guest') }}</span></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                                @if($ticket->shipment)
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span class="font-mono">{{ $ticket->shipment->tracking_number }}</span>
                                </div>
                                @endif
                                @if($ticket->assignedTo)
                                <div class="flex items-center gap-1.5 bg-blue-50 px-2 py-0.5 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span class="font-medium text-blue-700">Assigned: {{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            @if(!$ticket->assignedTo)
                            <button onclick="event.stopPropagation(); openAssignModal({{ $ticket->id }})" class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50 transition-colors">
                                Assign
                            </button>
                            @endif
                            <button onclick="event.stopPropagation(); window.location='{{ route('admin.support-tickets.show', $ticket->id) }}'" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                            <path d="M13 5v2"></path>
                            <path d="M13 17v2"></path>
                            <path d="M13 11v2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No tickets found</h3>
                    <p class="text-sm text-gray-500">There are no support tickets matching your criteria.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tickets->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ $tickets->firstItem() }}</span> to <span class="font-medium">{{ $tickets->lastItem() }}</span> of <span class="font-medium">{{ $tickets->total() }}</span> tickets
                    </p>
                    <div class="flex gap-2">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
            @endif
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
        
        <form id="assignForm" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Assign to</label>
                    <select name="assigned_to" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select staff member</option>
                        @foreach($supportUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Priority (optional)</label>
                    <select name="priority" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Keep current</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAssignModal()" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Assign Ticket
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

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
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function openAssignModal(ticketId) {
        const modal = document.getElementById('assignModal');
        const form = document.getElementById('assignForm');
        form.action = `/admin/support-tickets/${ticketId}/assign`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAssignModal() {
        const modal = document.getElementById('assignModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('assignForm').reset();
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