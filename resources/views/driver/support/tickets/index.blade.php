@extends('driver.driver_dashboard')
@section('driver')

<main class="flex-1 overflow-y-auto p-4 md:p-6">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-wrap gap-3 items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight">My Support Tickets</h1>
                <p class="text-muted-foreground">Track your support requests and inquiries</p>
            </div>
            <a href="{{ route('driver.support-tickets.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                New Ticket
            </a>
        </div>

        <!-- Stats -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-4">
                <p class="text-sm text-muted-foreground">Total</p>
                <p class="text-2xl font-bold mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-4">
                <p class="text-sm text-muted-foreground">Open</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['open'] }}</p>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-4">
                <p class="text-sm text-muted-foreground">In Progress</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['in_progress'] }}</p>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-4">
                <p class="text-sm text-muted-foreground">Resolved</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['resolved'] }}</p>
            </div>
        </div>

        <!-- Tickets List -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tickets as $ticket)
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
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium">{{ $ticket->ticket_number }}</p>
                                    <p class="text-sm text-gray-500 max-w-xs truncate">{{ $ticket->subject }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $priorityColors[$ticket->priority] ?? '' }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$ticket->status] ?? '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $ticket->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('driver.support-tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <p class="font-medium">No support tickets yet</p>
                                    <p class="text-sm mt-1">Create a ticket if you need help</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tickets->hasPages())
                <div class="px-6 py-4 border-t">{{ $tickets->links() }}</div>
            @endif
        </div>
    </div>
</main>

@if(session('success'))
<div id="success-toast" class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
<script>setTimeout(() => document.getElementById('success-toast')?.remove(), 3000);</script>
@endif

@endsection
