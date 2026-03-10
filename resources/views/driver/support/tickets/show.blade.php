@extends('driver.driver_dashboard')
@section('driver')

<main class="flex-1 overflow-y-auto p-4 md:p-6">
    <div class="space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap gap-3 items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('driver.support-tickets.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight">{{ $ticket->ticket_number }}</h1>
                    <p class="text-muted-foreground">{{ $ticket->subject }}</p>
                </div>
            </div>
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
            <div class="flex gap-2">
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$ticket->status] ?? '' }}">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$ticket->priority] ?? '' }}">
                    {{ ucfirst($ticket->priority) }}
                </span>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <!-- Conversation Thread -->
            <div class="md:col-span-2 space-y-4">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-4 md:p-6 border-b">
                        <h3 class="text-lg font-semibold">Conversation</h3>
                    </div>
                    <div class="p-4 md:p-6 space-y-4 max-h-[500px] overflow-y-auto" id="messages-container">
                        @forelse($ticket->messages as $message)
                            <div class="flex gap-3 {{ $message->sender_type === 'customer' ? '' : '' }}">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                        {{ $message->sender_type === 'customer' ? 'bg-blue-100 text-blue-700' : ($message->sender_type === 'system' ? 'bg-gray-100 text-gray-500' : 'bg-green-100 text-green-700') }}">
                                        @if($message->sender_type === 'customer')
                                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                        @elseif($message->sender_type === 'system')
                                            S
                                        @else
                                            {{ $message->user ? strtoupper(substr($message->user->first_name, 0, 1)) : 'A' }}
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-medium text-sm">
                                            @if($message->sender_type === 'customer')
                                                You
                                            @elseif($message->sender_type === 'system')
                                                System
                                            @else
                                                Support Agent
                                            @endif
                                        </span>
                                        <span class="text-xs text-muted-foreground">{{ $message->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="p-3 rounded-lg text-sm {{ $message->sender_type === 'customer' ? 'bg-blue-50' : ($message->sender_type === 'system' ? 'bg-gray-50 italic text-gray-500' : 'bg-green-50') }}">
                                        {{ $message->message }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-muted-foreground">
                                <p>No messages yet</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Reply Form -->
                    @if(!in_array($ticket->status, ['closed', 'resolved']))
                        <div class="p-4 md:p-6 border-t">
                            <form action="{{ route('driver.support-tickets.add-message', $ticket->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <textarea name="message" rows="3" required placeholder="Type your reply..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                                        Send Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="p-4 md:p-6 border-t text-center text-sm text-muted-foreground">
                            This ticket has been {{ $ticket->status }}. <a href="{{ route('driver.support-tickets.create') }}" class="text-blue-600 hover:underline">Create a new ticket</a> if you need further help.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ticket Details Sidebar -->
            <div class="space-y-4">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-4 md:p-6 border-b">
                        <h3 class="text-lg font-semibold">Ticket Details</h3>
                    </div>
                    <div class="p-4 md:p-6 space-y-4">
                        <div>
                            <p class="text-xs text-muted-foreground">Category</p>
                            <p class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted-foreground">Priority</p>
                            <p class="text-sm font-medium">{{ ucfirst($ticket->priority) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted-foreground">Status</p>
                            <p class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
                        </div>
                        @if($ticket->assignedTo)
                            <div>
                                <p class="text-xs text-muted-foreground">Assigned To</p>
                                <p class="text-sm font-medium">{{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}</p>
                            </div>
                        @endif
                        @if($ticket->shipment)
                            <div>
                                <p class="text-xs text-muted-foreground">Related Shipment</p>
                                <p class="text-sm font-medium">{{ $ticket->shipment->tracking_number }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-muted-foreground">Created</p>
                            <p class="text-sm font-medium">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($ticket->resolved_at)
                            <div>
                                <p class="text-xs text-muted-foreground">Resolved</p>
                                <p class="text-sm font-medium">{{ $ticket->resolved_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                        @if($ticket->resolution)
                            <div>
                                <p class="text-xs text-muted-foreground">Resolution</p>
                                <p class="text-sm">{{ $ticket->resolution }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@if(session('success'))
<div id="success-toast" class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
<script>setTimeout(() => document.getElementById('success-toast')?.remove(), 3000);</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messages-container');
    if (container) container.scrollTop = container.scrollHeight;
});
</script>

@endsection
