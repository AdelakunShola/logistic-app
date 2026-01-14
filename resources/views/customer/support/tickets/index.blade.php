<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Support Tickets</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </a>
                        <h1 class="text-xl font-bold text-gray-900">My Support Tickets</h1>
                    </div>
                    <a href="{{ route('user.support-tickets.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        New Ticket
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <p class="text-sm text-gray-600">Open</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['open'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <p class="text-sm text-gray-600">In Progress</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <p class="text-sm text-gray-600">Resolved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['resolved'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <p class="text-sm text-gray-600">Closed</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['closed'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <form method="GET" action="{{ route('user.support-tickets.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search tickets..." 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <select name="status" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('user.support-tickets.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Tickets List -->
            <div class="bg-white rounded-lg shadow-sm border divide-y">
                @forelse($tickets as $ticket)
                <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location='{{ route('user.support-tickets.show', $ticket->id) }}'">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-sm font-mono text-gray-500">{{ $ticket->ticket_number }}</span>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase 
                                    {{ $ticket->status == 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $ticket->status == 'in_progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $ticket->status == 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $ticket->status == 'closed' ? 'bg-gray-100 text-gray-700' : '' }}
                                ">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase 
                                    {{ $ticket->priority == 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $ticket->priority == 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $ticket->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $ticket->priority == 'low' ? 'bg-blue-100 text-blue-700' : '' }}
                                ">
                                    {{ $ticket->priority }}
                                </span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $ticket->subject }}</h3>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $ticket->description }}</p>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                @if($ticket->shipment)
                                <span>•</span>
                                <span>Shipment: {{ $ticket->shipment->tracking_number }}</span>
                                @endif
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No tickets found</h3>
                    <p class="text-sm text-gray-500 mb-4">You haven't created any support tickets yet.</p>
                    <a href="{{ route('user.support-tickets.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Create Your First Ticket
                    </a>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tickets->hasPages())
            <div class="mt-6">
                {{ $tickets->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>