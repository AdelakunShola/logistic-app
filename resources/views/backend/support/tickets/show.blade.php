@extends('admin.admin_dashboard')
@section('admin')

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.support-tickets.index') }}" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $ticket->ticket_number }}</h1>
                        <p class="text-sm text-gray-500">{{ $ticket->subject }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @php
                        $statusColors = [
                            'open' => 'bg-blue-100 text-blue-700',
                            'in_progress' => 'bg-yellow-100 text-yellow-700',
                            'resolved' => 'bg-green-100 text-green-700',
                            'closed' => 'bg-gray-100 text-gray-700',
                            'escalated' => 'bg-red-100 text-red-700',
                        ];
                        $priorityColors = [
                            'low' => 'bg-gray-100 text-gray-700',
                            'medium' => 'bg-blue-100 text-blue-700',
                            'high' => 'bg-orange-100 text-orange-700',
                            'urgent' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $priorityColors[$ticket->priority] ?? '' }}">
                        {{ ucfirst($ticket->priority) }} Priority
                    </span>
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$ticket->status] ?? '' }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content - Conversation Thread -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Conversation -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold">Conversation</h2>
                    </div>
                    <div class="p-6 space-y-6 max-h-[600px] overflow-y-auto" id="messages-container">
                        @forelse($ticket->messages as $message)
                            @if($message->is_internal)
                                <!-- Internal Note -->
                                <div class="flex items-start gap-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="bg-yellow-100 rounded-full p-2 flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-medium text-yellow-700">Internal Note</span>
                                            <span class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-yellow-800 mt-1">{{ $message->message }}</p>
                                    </div>
                                </div>
                            @elseif($message->sender_type === 'system')
                                <!-- System Message -->
                                <div class="text-center">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        {{ $message->message }} - {{ $message->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            @elseif($message->sender_type === 'admin')
                                <!-- Admin Reply -->
                                <div class="flex items-start gap-3">
                                    <div class="bg-blue-500 rounded-full p-2 flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                    <div class="flex-1 bg-blue-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-blue-800">
                                                {{ $message->user ? $message->user->first_name . ' ' . $message->user->last_name : 'Support Agent' }}
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 mt-2 whitespace-pre-wrap">{{ $message->message }}</p>
                                    </div>
                                </div>
                            @else
                                <!-- Customer Message -->
                                <div class="flex items-start gap-3">
                                    <div class="bg-gray-200 rounded-full p-2 flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-600">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-800">
                                                {{ $ticket->customer_name ?? ($message->user ? $message->user->first_name . ' ' . $message->user->last_name : 'Customer') }}
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 mt-2 whitespace-pre-wrap">{{ $message->message }}</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="text-center py-8 text-gray-400">
                                <p>No messages yet</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Reply Form -->
                    @if(!in_array($ticket->status, ['closed']))
                        <div class="p-6 border-t bg-gray-50">
                            <form action="{{ route('admin.support-tickets.add-message', $ticket->id) }}" method="POST">
                                @csrf
                                <div class="space-y-3">
                                    <textarea name="message" rows="3" required placeholder="Type your reply..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    <div class="flex items-center justify-between">
                                        <label class="flex items-center gap-2 text-sm text-gray-600">
                                            <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-500">
                                            Internal note (not visible to customer)
                                        </label>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                            </svg>
                                            Send Reply
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar - Ticket Details -->
            <div class="space-y-6">
                <!-- Ticket Info -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold">Ticket Details</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Category</label>
                            <p class="text-sm font-medium mt-1">{{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Created</label>
                            <p class="text-sm mt-1">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($ticket->resolved_at)
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">Resolved</label>
                                <p class="text-sm mt-1">{{ $ticket->resolved_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                        @if($ticket->resolution)
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">Resolution</label>
                                <p class="text-sm mt-1 text-green-700 bg-green-50 p-3 rounded-lg">{{ $ticket->resolution }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold">Customer</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="bg-gray-200 rounded-full p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-600">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">{{ $ticket->customer_name ?? ($ticket->user ? $ticket->user->first_name . ' ' . $ticket->user->last_name : 'N/A') }}</p>
                                <p class="text-sm text-gray-500">{{ $ticket->customer_email ?? $ticket->user?->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($ticket->customer_phone)
                            <p class="text-sm text-gray-600">Phone: {{ $ticket->customer_phone }}</p>
                        @endif
                    </div>
                </div>

                <!-- Linked Shipment -->
                @if($ticket->shipment)
                    <div class="bg-white rounded-lg shadow-sm border">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-semibold">Linked Shipment</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.shipments.show', $ticket->shipment->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $ticket->shipment->tracking_number }}
                            </a>
                            <p class="text-sm text-gray-600">Status: {{ ucfirst(str_replace('_', ' ', $ticket->shipment->status)) }}</p>
                            @if($ticket->shipment->pickup_city && $ticket->shipment->delivery_city)
                                <p class="text-sm text-gray-600">{{ $ticket->shipment->pickup_city }} → {{ $ticket->shipment->delivery_city }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold">Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Assign -->
                        <form action="{{ route('admin.support-tickets.assign', $ticket->id) }}" method="POST">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                            <div class="flex gap-2">
                                @if(in_array(auth()->user()->role, ['admin', 'super_admin', 'manager']))
                                <select name="assigned_to" required class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Select...</option>
                                    @foreach($supportUsers as $user)
                                        <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @else
                                <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                                <span class="flex-1 flex items-center text-sm text-gray-700 px-3 py-2 rounded-lg border border-gray-300 bg-gray-50">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} (Me)</span>
                                @endif
                                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">{{ in_array(auth()->user()->role, ['admin', 'super_admin', 'manager']) ? 'Assign' : 'Assign to Me' }}</button>
                            </div>
                        </form>

                        <!-- Update Status -->
                        <form action="{{ route('admin.support-tickets.update-status', $ticket->id) }}" method="POST" class="mt-4">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
                            <select name="status" id="sidebar-status" onchange="toggleSidebarResolution()" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm mb-2">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="escalated" {{ $ticket->status == 'escalated' ? 'selected' : '' }}>Escalated</option>
                            </select>
                            <div id="sidebar-resolution" class="{{ $ticket->status === 'resolved' ? '' : 'hidden' }}">
                                <textarea name="resolution" rows="2" placeholder="Resolution details..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm mb-2">{{ $ticket->resolution }}</textarea>
                            </div>
                            <button type="submit" class="w-full px-3 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 text-sm">Update Status</button>
                        </form>

                        @if(!in_array($ticket->status, ['closed', 'resolved']))
                            <hr class="my-3">
                            <form action="{{ route('admin.support-tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-3 py-2 text-red-600 border border-red-300 rounded-lg hover:bg-red-50 text-sm">Delete Ticket</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div id="success-toast" class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
<script>setTimeout(() => document.getElementById('success-toast')?.remove(), 3000);</script>
@endif

<script>
function toggleSidebarResolution() {
    const status = document.getElementById('sidebar-status').value;
    const field = document.getElementById('sidebar-resolution');
    field.classList.toggle('hidden', status !== 'resolved');
}

// Scroll to bottom of messages
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messages-container');
    if (container) container.scrollTop = container.scrollHeight;
});
</script>

@endsection
