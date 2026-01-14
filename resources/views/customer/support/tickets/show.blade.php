<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Support Ticket: {{ $ticket->ticket_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white border-b">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('user.support-tickets.index') }}" class="text-gray-600 hover:text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Ticket: {{ $ticket->ticket_number }}</h1>
                            <p class="text-sm text-gray-500">{{ $ticket->subject }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

            <!-- Ticket Header -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
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
                    <span class="text-sm text-gray-500">Created {{ $ticket->created_at->diffForHumans() }}</span>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $ticket->subject }}</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                </div>

                @if($ticket->shipment)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Related Shipment:</p>
                    <p class="font-mono font-medium">{{ $ticket->shipment->tracking_number }}</p>
                </div>
                @endif
            </div>

            <!-- Resolution (if resolved) -->
            @if($ticket->status === 'resolved' && $ticket->resolution)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-semibold text-green-900 mb-2">Ticket Resolved</h3>
                        <p class="text-sm text-green-800 whitespace-pre-wrap">{{ $ticket->resolution }}</p>
                        @if($ticket->resolved_at)
                        <p class="text-xs text-green-700 mt-2">Resolved on {{ $ticket->resolved_at->format('M d, Y \a\t g:i A') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Messages/Conversation -->
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="p-6 border-b">
                    <h3 class="font-semibold">Conversation</h3>
                </div>
                <div class="divide-y">
                    @forelse($ticket->messages as $message)
                    <div class="p-4">
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
                                            Support Team
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-500">
                        <p>No messages yet. Support team will respond soon.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Add Reply Form -->
                @if(!in_array($ticket->status, ['closed']))
                <div class="p-6 border-t bg-gray-50">
                    <form action="{{ route('user.support-tickets.add-message', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <textarea 
                                name="message" 
                                rows="3" 
                                placeholder="Type your reply here..."
                                required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            ></textarea>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="p-6 border-t bg-gray-50 text-center text-gray-500">
                    <p>This ticket is closed. Please create a new ticket if you need further assistance.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>