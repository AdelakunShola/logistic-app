@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .timeline-item {
        position: relative;
        padding-left: 2rem;
        padding-bottom: 2rem;
    }
    .timeline-item:not(:last-child):before {
        content: '';
        position: absolute;
        left: 0.4375rem;
        top: 1.75rem;
        height: calc(100% - 1rem);
        width: 2px;
        background-color: #e5e7eb;
    }
    .timeline-item.completed:not(:last-child):before {
        background-color: #10b981;
    }
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 0.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background-color: #e5e7eb;
        border: 2px solid white;
    }
    .timeline-item.completed .timeline-dot {
        background-color: #10b981;
    }
    .timeline-item.active .timeline-dot {
        background-color: #3b82f6;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .info-card {
        transition: transform 0.2s;
    }
    .info-card:hover {
        transform: translateY(-2px);
    }
    .modal {
        display: none;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .modal.show {
        display: flex;
        opacity: 1;
    }
    .modal-content {
        transform: scale(0.95);
        transition: transform 0.3s;
    }
    .modal.show .modal-content {
        transform: scale(1);
    }
</style>

<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.warehouse.transfers.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold tracking-tight">Transfer Details</h1>
            </div>
            <p class="text-gray-600">Transfer Code: <span class="font-semibold text-blue-600">{{ $transfer->transfer_code }}</span></p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Status Badge -->
            <span class="rounded-full px-3 py-1.5 text-sm font-semibold {{ $transfer->status_badge }}">
                {{ ucwords(str_replace('_', ' ', $transfer->status)) }}
            </span>

            <!-- Action Buttons -->
            @if($transfer->status === 'pending' && !$transfer->driver_id)
            <button id="assignDriverBtn" class="inline-flex items-center justify-center text-sm font-medium border border-blue-600 text-blue-600 hover:bg-blue-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Assign Driver
            </button>
            @endif

            @if($transfer->status === 'pending' && $transfer->driver_id)
            <button id="markDepartedBtn" class="inline-flex items-center justify-center text-sm font-medium border border-green-600 text-green-600 hover:bg-green-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <path d="M15 18H9"></path>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
                Mark as Departed
            </button>
            @endif

            @if($transfer->status === 'in_transit')
            <button id="markArrivedBtn" class="inline-flex items-center justify-center text-sm font-medium border border-green-600 text-green-600 hover:bg-green-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                    <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                </svg>
                Mark as Arrived
            </button>
            @endif

            <a href="{{ route('admin.warehouse.transfers.print', $transfer->id) }}" target="_blank" class="inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print
            </a>

            <a href="{{ route('admin.warehouse.transfers.manifest', $transfer->id) }}" class="inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                Manifest
            </a>

            @if(in_array($transfer->status, ['pending', 'in_transit']))
            <button id="cancelTransferBtn" class="inline-flex items-center justify-center text-sm font-medium border border-red-600 text-red-600 hover:bg-red-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="m4.9 4.9 14.2 14.2"></path>
                </svg>
                Cancel
            </button>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transfer Route Card -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-6">Transfer Route</h2>
                
                <div class="relative">
                    <!-- From Warehouse -->
                    <div class="flex items-start gap-4 mb-8">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 mb-1">Origin</p>
                            <h3 class="text-lg font-semibold">{{ $transfer->fromWarehouse->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-600">{{ $transfer->fromWarehouse->warehouse_code ?? '' }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $transfer->fromWarehouse->address ?? '' }}</p>
                        </div>
                    </div>

                    <!-- Arrow Indicator -->
                    <div class="absolute left-6 top-16 bottom-16 w-0.5 bg-gray-300 -z-10"></div>
                    <div class="flex justify-center -my-4 relative z-10">
                        <div class="bg-white px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-400">
                                <path d="M12 5v14"></path>
                                <path d="m19 12-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- To Warehouse -->
                    <div class="flex items-start gap-4 mt-8">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600">
                                <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                                <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 mb-1">Destination</p>
                            <h3 class="text-lg font-semibold">{{ $transfer->toWarehouse->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-600">{{ $transfer->toWarehouse->warehouse_code ?? '' }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $transfer->toWarehouse->address ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipment Information -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-6">Shipment Information</h2>
                
                @if($transfer->shipment)
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Tracking Number</p>
                            <p class="font-semibold text-blue-600">{{ $transfer->shipment->tracking_number }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Package Type</p>
                            <p class="font-semibold">{{ ucwords($transfer->shipment->package_type ?? 'Standard') }}</p>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <h3 class="font-semibold mb-3">Sender Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium">
                                    {{ $transfer->shipment->sender->first_name ?? '' }} 
                                    {{ $transfer->shipment->sender->last_name ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium">{{ $transfer->shipment->sender->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <h3 class="font-semibold mb-3">Receiver Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium">
                                    {{ $transfer->shipment->receiver->first_name ?? '' }} 
                                    {{ $transfer->shipment->receiver->last_name ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium">{{ $transfer->shipment->receiver->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <p class="text-gray-500">No shipment information available</p>
                @endif
            </div>

            <!-- Transfer Notes -->
            @if($transfer->transfer_notes)
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4">Transfer Notes</h2>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-gray-700">{{ $transfer->transfer_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Cancellation Reason -->
            @if($transfer->status === 'cancelled' && $transfer->reason)
            <div class="rounded-lg border border-red-200 bg-red-50 shadow-sm p-6">
                <h2 class="text-xl font-semibold text-red-800 mb-4">Cancellation Reason</h2>
                <p class="text-red-700">{{ $transfer->reason }}</p>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Transfer Information -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4">Transfer Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Transfer Code</p>
                        <p class="font-semibold">{{ $transfer->transfer_code }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Transfer Type</p>
                        <p class="font-semibold">{{ ucwords(str_replace('_', ' ', $transfer->transfer_type)) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $transfer->status_badge }}">
                            {{ ucwords(str_replace('_', ' ', $transfer->status)) }}
                        </span>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Created At</p>
                        <p class="font-semibold">{{ $transfer->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Driver Information -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4">Driver Information</h2>
                
                @if($transfer->driver)
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-lg">
                            {{ $transfer->driver->first_name }} {{ $transfer->driver->last_name }}
                        </p>
                        <p class="text-sm text-gray-600">Driver ID: {{ $transfer->driver->id }}</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @if($transfer->driver->phone)
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <span class="text-sm">{{ $transfer->driver->phone }}</span>
                    </div>
                    @endif
                    
                    @if($transfer->driver->email)
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <span class="text-sm">{{ $transfer->driver->email }}</span>
                    </div>
                    @endif
                    
                    @if($transfer->vehicle_number)
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                            <path d="M15 18H9"></path>
                            <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                            <circle cx="17" cy="18" r="2"></circle>
                            <circle cx="7" cy="18" r="2"></circle>
                        </svg>
                        <span class="text-sm">Vehicle: {{ $transfer->vehicle_number }}</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto text-gray-400 mb-2">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <p class="text-gray-500 text-sm">No driver assigned</p>
                    @if($transfer->status === 'pending')
                    <button id="assignDriverBtn2" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Assign Driver Now
                    </button>
                    @endif
                </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-6">Timeline</h2>
                
                <div class="space-y-0">
                    <!-- Initiated -->
                    <div class="timeline-item completed">
                        <div class="timeline-dot"></div>
                        <div>
                            <p class="font-semibold">Transfer Initiated</p>
                            <p class="text-sm text-gray-600">
                                {{ $transfer->initiated_at ? $transfer->initiated_at->format('M d, Y H:i') : 'N/A' }}
                            </p>
                            @if($transfer->initiatedBy)
                            <p class="text-xs text-gray-500 mt-1">
                                By: {{ $transfer->initiatedBy->first_name }} {{ $transfer->initiatedBy->last_name }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <!-- Departed -->
                    <div class="timeline-item {{ $transfer->departed_at ? 'completed' : ($transfer->status === 'in_transit' ? 'active' : '') }}">
                        <div class="timeline-dot"></div>
                        <div>
                            <p class="font-semibold">Departed from Origin</p>
                            <p class="text-sm text-gray-600">
                                {{ $transfer->departed_at ? $transfer->departed_at->format('M d, Y H:i') : 'Pending' }}
                            </p>
                        </div>
                    </div>

                    <!-- Arrived -->
                    <div class="timeline-item {{ $transfer->arrived_at ? 'completed' : '' }}">
                        <div class="timeline-dot"></div>
                        <div>
                            <p class="font-semibold">Arrived at Destination</p>
                            <p class="text-sm text-gray-600">
                                {{ $transfer->arrived_at ? $transfer->arrived_at->format('M d, Y H:i') : 'Pending' }}
                            </p>
                        </div>
                    </div>

                    <!-- Completed -->
                    <div class="timeline-item {{ $transfer->completed_at ? 'completed' : '' }}">
                        <div class="timeline-dot"></div>
                        <div>
                            <p class="font-semibold">Transfer Completed</p>
                            <p class="text-sm text-gray-600">
                                {{ $transfer->completed_at ? $transfer->completed_at->format('M d, Y H:i') : 'Pending' }}
                            </p>
                            @if($transfer->receivedBy)
                            <p class="text-xs text-gray-500 mt-1">
                                Received by: {{ $transfer->receivedBy->first_name }} {{ $transfer->receivedBy->last_name }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Driver Modal -->
<div id="assignDriverModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Assign Driver</h2>
            <p class="text-sm text-gray-600 mt-1">Select a driver for this transfer</p>
        </div>
        <form id="assignDriverForm">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Select Driver *</label>
                    <select name="driver_id" id="driver_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose driver</option>
                        @foreach(\App\Models\User::where('role', 'driver')->where('status', 'active')->get() as $driver)
                        <option value="{{ $driver->id }}" {{ $driver->is_available ? '' : 'disabled' }}>
                            {{ $driver->first_name }} {{ $driver->last_name }}
                            {{ $driver->is_available ? '' : '(Unavailable)' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" id="cancelAssignBtn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Assign Driver</button>
            </div>
        </form>
    </div>
</div>

<!-- Cancel Transfer Modal -->
<div id="cancelTransferModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-red-600">Cancel Transfer</h2>
            <p class="text-sm text-gray-600 mt-1">Are you sure you want to cancel this transfer?</p>
        </div>
        <form id="cancelTransferForm">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Reason for Cancellation *</label>
                    <textarea name="reason" rows="4" required placeholder="Please provide a reason for cancelling this transfer..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-800">
                        <strong>Warning:</strong> This action will cancel the transfer and revert the shipment status.
                    </p>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" id="cancelCancelBtn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Go Back</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Cancel Transfer</button>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// Close modals when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    });
});

// Assign Driver Modal
const assignDriverBtns = document.querySelectorAll('#assignDriverBtn, #assignDriverBtn2');
assignDriverBtns.forEach(btn => {
    btn?.addEventListener('click', () => {
        openModal('assignDriverModal');
    });
});

document.getElementById('cancelAssignBtn')?.addEventListener('click', () => {
    closeModal('assignDriverModal');
});

document.getElementById('assignDriverForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    fetch('{{ route("admin.warehouse.transfers.assign.driver", $transfer->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while assigning the driver');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Mark as Departed
document.getElementById('markDepartedBtn')?.addEventListener('click', function() {
    if (confirm('Mark this transfer as departed?')) {
        updateTransferStatus('in_transit');
    }
});

// Mark as Arrived
document.getElementById('markArrivedBtn')?.addEventListener('click', function() {
    if (confirm('Mark this transfer as arrived at destination?')) {
        updateTransferStatus('completed');
    }
});

// Cancel Transfer Modal
document.getElementById('cancelTransferBtn')?.addEventListener('click', () => {
    openModal('cancelTransferModal');
});

document.getElementById('cancelCancelBtn')?.addEventListener('click', () => {
    closeModal('cancelTransferModal');
});

document.getElementById('cancelTransferForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    fetch('{{ route("admin.warehouse.transfers.update.status", $transfer->id) }}', {
        method: 'POST',
        body: JSON.stringify({
            status: 'cancelled',
            reason: formData.get('reason')
        }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while cancelling the transfer');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Update Transfer Status Function
function updateTransferStatus(status, reason = null) {
    const data = { status };
    if (reason) data.reason = reason;
    
    fetch('{{ route("admin.warehouse.transfers.update.status", $transfer->id) }}', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status');
    });
}

// Auto-hide success/error messages after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>

@endsection