@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .dropdown-menu {
        display: none;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.2s, transform 0.2s;
    }
    .dropdown-menu.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
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
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Warehouse Transfers</h1>
            <p class="text-gray-600">Manage inter-warehouse transfers and delivery dispatches</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button id="refreshBtn" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M21 3v5h-5"></path>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                    <path d="M8 16H3v5"></path>
                </svg>
                Refresh
            </button>
            
            <button id="initiateTransferBtn" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                Initiate Transfer
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Transfers</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-600">
                        <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                        <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                        <path d="M12 3v6"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">Today: {{ $stats['today'] }}</div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold">{{ $stats['pending'] }}</p>
                </div>
                <div class="p-2 bg-yellow-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-yellow-600">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">Unassigned: {{ $stats['unassigned'] }}</div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">In Transit</p>
                    <p class="text-2xl font-bold">{{ $stats['in_transit'] }}</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-600">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">Active deliveries</div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold">{{ $stats['completed'] }}</p>
                </div>
                <div class="p-2 bg-green-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-green-600">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">Successfully transferred</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border bg-white shadow-sm p-4 md:p-6">
        <form id="filterForm" method="GET" action="{{ route('admin.warehouse.transfers.index') }}">
            <div class="flex flex-col gap-4">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500">
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                    <input type="search" name="search" id="searchInput" value="{{ request('search') }}" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by tracking number, warehouse, driver..."/>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-2">
                    <!-- Status Filter -->
                    <select name="status" id="statusFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                        <option value="all">All Status</option>
                        @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Transfer Type Filter -->
                    <select name="transfer_type" id="transferTypeFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                        <option value="all">All Types</option>
                        @foreach($transferTypes as $type)
                        <option value="{{ $type }}" {{ request('transfer_type') == $type ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $type)) }}
                        </option>
                        @endforeach
                    </select>

                    <!-- From Warehouse Filter -->
                    <select name="from_warehouse_id" id="fromWarehouseFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                        <option value="">From Warehouse</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- To Warehouse Filter -->
                    <select name="to_warehouse_id" id="toWarehouseFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                        <option value="">To Warehouse</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Driver Filter -->
                    <select name="driver_id" id="driverFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                        <option value="">All Drivers</option>
                        @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                            {{ $driver->first_name }} {{ $driver->last_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Transfers Table -->
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-4 md:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h3 class="text-xl sm:text-2xl font-semibold">Transfer List</h3>
                <div class="text-sm text-gray-600">{{ $transfers->total() }} transfers found</div>
            </div>
            <div class="flex items-center gap-2">
                <button id="bulkDeleteBtn" class="hidden inline-flex items-center justify-center text-sm font-medium border border-red-300 text-red-600 hover:bg-red-50 h-9 rounded-md px-3">
                    Delete Selected
                </button>
            </div>
        </div>
        
        <div class="p-4 md:p-6 pt-0">
            <div class="rounded-md border overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="h-12 px-4 text-left w-[40px]">
                                <input type="checkbox" id="selectAll" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                            </th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Transfer ID</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Tracking Number</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">From → To</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Type</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Status</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 hidden lg:table-cell">Driver</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 hidden xl:table-cell">Initiated</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 w-[60px]"></th>
                        </tr>
                    </thead>
                    <tbody id="transferTableBody">
                        @forelse($transfers as $transfer)
                        <tr class="border-b hover:bg-gray-50" data-transfer-id="{{ $transfer->id }}">
                            <td class="p-4">
                                <input type="checkbox" class="transfer-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $transfer->id }}"/>
                            </td>
                            <td class="p-4 font-medium">
                                {{ $transfer->transfer_code }}
                            </td>
                            <td class="p-4">
                                <span class="font-medium text-blue-600">{{ $transfer->shipment->tracking_number ?? 'N/A' }}</span>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col space-y-1">
                                    <span class="text-xs text-gray-500">From:</span>
                                    <span class="font-medium">{{ $transfer->fromWarehouse->name ?? 'N/A' }}</span>
                                    <span class="text-xs text-gray-500">To:</span>
                                    <span class="font-medium">{{ $transfer->toWarehouse->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="text-xs text-gray-600">
                                    {{ ucwords(str_replace('_', ' ', $transfer->transfer_type)) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $transfer->status_badge }} w-fit">
                                    {{ ucwords(str_replace('_', ' ', $transfer->status)) }}
                                </span>
                            </td>
                            <td class="p-4 hidden lg:table-cell">
                                @if($transfer->driver)
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span>{{ $transfer->driver->first_name }} {{ $transfer->driver->last_name }}</span>
                                </div>
                                @else
                                <span class="text-gray-400 text-xs">Not assigned</span>
                                @endif
                            </td>
                            <td class="p-4 hidden xl:table-cell">
                                <div class="text-xs text-gray-600">
                                    {{ $transfer->initiated_at ? $transfer->initiated_at->format('M d, Y H:i') : 'N/A' }}
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="relative">
                                    <button class="action-menu-btn hover:bg-gray-100 rounded-md p-2" data-transfer-id="{{ $transfer->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg>
                                    </button>
                                    <div class="action-dropdown dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                        <div class="py-1">
                                            <button class="view-details-btn flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-transfer-id="{{ $transfer->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                View Details
                                            </button>
                                            
                                            @if($transfer->canAssignDriver())
                                            <button class="assign-driver-btn flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-transfer-id="{{ $transfer->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                Assign Driver
                                            </button>
                                            @endif

                                            @if($transfer->canBeEdited())
                                            <button class="edit-transfer-btn flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-transfer-id="{{ $transfer->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                </svg>
                                                Edit Transfer
                                            </button>
                                            @endif

                                            <a href="{{ route('admin.warehouse.transfers.print', $transfer->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                                    <rect x="6" y="14" width="12" height="8"></rect>
                                                </svg>
                                                Print Document
                                            </a>

                                            <a href="{{ route('admin.warehouse.transfers.manifest', $transfer->id) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                </svg>
                                                Download Manifest
                                            </a>
                                            
                                            @if($transfer->canBeCancelled())
                                            <div class="border-t border-gray-200 my-1"></div>
                                            <button class="cancel-transfer-btn flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left" data-transfer-id="{{ $transfer->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="m4.9 4.9 14.2 14.2"></path>
                                                </svg>
                                                Cancel Transfer
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-gray-500">
                                No transfers found. Try adjusting your filters or initiate a new transfer.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($transfers->hasPages())
            <div class="mt-4">
                {{ $transfers->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Initiate Transfer Modal -->
<div id="initiateTransferModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Initiate Warehouse Transfer</h2>
                <p class="text-sm text-gray-600 mt-1">Select shipments and destination warehouse to initiate transfer</p>
            </div>
            <button id="closeInitiateModal" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="initiateTransferForm" method="POST" action="{{ route('admin.warehouse.transfers.store') }}">
            @csrf
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">From Warehouse *</label>
                        <select name="from_warehouse_id" id="from_warehouse_select" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select warehouse</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->warehouse_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">To Warehouse *</label>
                        <select name="to_warehouse_id" id="to_warehouse_select" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select warehouse</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->warehouse_code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Transfer Type *</label>
                        <select name="transfer_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($transferTypes as $type)
                            <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Assign Driver (Optional)</label>
                        <select name="driver_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Assign later</option>
                            @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->first_name }} {{ $driver->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Transfer Notes</label>
                    <textarea name="transfer_notes" rows="3" placeholder="Add any special instructions or notes..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="border-t pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium">Select Shipments *</label>
                        <button type="button" id="loadShipmentsBtn" class="text-sm text-blue-600 hover:text-blue-800">
                            Load Available Shipments
                        </button>
                    </div>
                    
                    <div id="shipmentsLoadingState" class="hidden text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p class="mt-2 text-sm text-gray-600">Loading shipments...</p>
                    </div>

                    <div id="shipmentsContainer" class="hidden max-h-64 overflow-y-auto border border-gray-200 rounded-md">
                        <div id="shipmentsList" class="divide-y"></div>
                    </div>

                    <div id="noShipmentsMessage" class="hidden text-center py-8 text-gray-500">
                        <p>No shipments available for transfer from selected warehouse</p>
                    </div>

                    <div id="selectedShipmentsCount" class="hidden mt-2 text-sm text-gray-600">
                        <span id="selectedCount">0</span> shipment(s) selected
                    </div>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" id="cancelInitiateBtn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Initiate Transfer</button>
            </div>
        </form>
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
            <input type="hidden" id="assign_transfer_id" name="transfer_id">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Select Driver *</label>
                    <select name="driver_id" id="assign_driver_select" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose driver</option>
                        @foreach($drivers as $driver)
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

<!-- View Details Modal -->
<div id="viewDetailsModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Transfer Details - <span id="detailsTransferCode"></span></h2>
                <p class="text-sm text-gray-600 mt-1" id="detailsTransferInfo"></p>
            </div>
            <button id="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="p-6" id="transferDetailsContent">
            <!-- Content loaded dynamically -->
        </div>
        <div class="p-6 border-t flex justify-end gap-3">
            <button id="closeDetailsBtn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Close</button>
        </div>
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
            <input type="hidden" id="cancel_transfer_id" name="transfer_id">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Reason for Cancellation *</label>
                    <textarea name="reason" rows="4" required placeholder="Please provide a reason for cancelling..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
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
    // CSRF Token
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

    // Dropdown handlers
    document.querySelectorAll('.action-menu-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const dropdown = btn.nextElementSibling;
            
            document.querySelectorAll('.action-dropdown').forEach(d => {
                if (d !== dropdown) d.classList.remove('show');
            });
            
            dropdown.classList.toggle('show');
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('.dropdown-menu').forEach(d => {
                d.classList.remove('show');
            });
        }
    });

    // Checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const transferCheckboxes = document.querySelectorAll('.transfer-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    selectAllCheckbox?.addEventListener('change', function() {
        transferCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });

    transferCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(transferCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(transferCheckboxes).some(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
            toggleBulkActions();
        });
    });

    function toggleBulkActions() {
        const anyChecked = Array.from(transferCheckboxes).some(cb => cb.checked);
        if (anyChecked) {
            bulkDeleteBtn.classList.remove('hidden');
        } else {
            bulkDeleteBtn.classList.add('hidden');
        }
    }

    // Bulk delete
    bulkDeleteBtn?.addEventListener('click', function() {
        const selectedIds = Array.from(transferCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) return;
        
        if (confirm(`Are you sure you want to delete ${selectedIds.length} transfer(s)?`)) {
            fetch('{{ route("admin.warehouse.transfers.bulk.delete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
    });

    // Refresh button
    document.getElementById('refreshBtn')?.addEventListener('click', () => {
        location.reload();
    });

    // Filter form auto-submit
    document.querySelectorAll('#statusFilter, #transferTypeFilter, #fromWarehouseFilter, #toWarehouseFilter, #driverFilter').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Search with debounce
    let searchTimeout;
    document.getElementById('searchInput')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });

    // Initiate Transfer Modal
    document.getElementById('initiateTransferBtn')?.addEventListener('click', () => {
        openModal('initiateTransferModal');
    });

    document.getElementById('closeInitiateModal')?.addEventListener('click', () => {
        closeModal('initiateTransferModal');
    });

    document.getElementById('cancelInitiateBtn')?.addEventListener('click', () => {
        closeModal('initiateTransferModal');
    });

    // Load shipments based on selected warehouse
    document.getElementById('loadShipmentsBtn')?.addEventListener('click', function() {
        const fromWarehouseId = document.getElementById('from_warehouse_select').value;
        
        if (!fromWarehouseId) {
            alert('Please select a warehouse first');
            return;
        }

        document.getElementById('shipmentsLoadingState').classList.remove('hidden');
        document.getElementById('shipmentsContainer').classList.add('hidden');
        document.getElementById('noShipmentsMessage').classList.add('hidden');

        fetch(`/admin/warehouse-transfers/warehouse/${fromWarehouseId}/shipments`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('shipmentsLoadingState').classList.add('hidden');
            
            if (data.success && data.shipments.length > 0) {
                displayShipments(data.shipments);
                document.getElementById('shipmentsContainer').classList.remove('hidden');
                document.getElementById('selectedShipmentsCount').classList.remove('hidden');
            } else {
                document.getElementById('noShipmentsMessage').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('shipmentsLoadingState').classList.add('hidden');
            alert('Error loading shipments');
        });
    });

    function displayShipments(shipments) {
        const shipmentsList = document.getElementById('shipmentsList');
        shipmentsList.innerHTML = '';

        shipments.forEach(shipment => {
            const shipmentItem = document.createElement('div');
            shipmentItem.className = 'flex items-center gap-3 p-3 hover:bg-gray-50';
            shipmentItem.innerHTML = `
                <input type="checkbox" name="shipment_ids[]" value="${shipment.id}" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 shipment-checkbox"/>
                <div class="flex-1">
                    <div class="font-medium text-sm">${shipment.tracking_number}</div>
                    <div class="text-xs text-gray-500">
                        ${shipment.sender ? shipment.sender.first_name + ' ' + shipment.sender.last_name : 'N/A'} → 
                        ${shipment.receiver ? shipment.receiver.first_name + ' ' + shipment.receiver.last_name : 'N/A'}
                    </div>
                </div>
                <div class="text-xs text-gray-600">${shipment.package_type || 'Package'}</div>
            `;
            shipmentsList.appendChild(shipmentItem);
        });

        // Update count on checkbox change
        document.querySelectorAll('.shipment-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });
    }

    function updateSelectedCount() {
        const count = document.querySelectorAll('.shipment-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = count;
    }

    // Submit Initiate Transfer Form
    document.getElementById('initiateTransferForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(this.action, {
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
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while initiating the transfer');
        });
    });

    // View Details
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const transferId = this.dataset.transferId;
            loadTransferDetails(transferId);
            openModal('viewDetailsModal');
            document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
        });
    });

    function loadTransferDetails(transferId) {
        fetch(`/admin/warehouse-transfers/${transferId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const transfer = data.transfer;
            document.getElementById('detailsTransferCode').textContent = transfer.transfer_code;
            document.getElementById('detailsTransferInfo').textContent = 
                `${transfer.from_warehouse?.name || 'N/A'} → ${transfer.to_warehouse?.name || 'N/A'}`;
            
            const content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Transfer Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transfer ID:</span>
                                <span class="font-medium">${transfer.transfer_code}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tracking Number:</span>
                                <span class="font-medium text-blue-600">${transfer.shipment?.tracking_number || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Type:</span>
                                <span class="font-medium">${transfer.transfer_type_label}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold ${transfer.status_badge}">
                                    ${transfer.status_label}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Route Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">From:</span>
                                <span class="font-medium">${transfer.from_warehouse?.name || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">To:</span>
                                <span class="font-medium">${transfer.to_warehouse?.name || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Driver:</span>
                                <span class="font-medium">
                                    ${transfer.driver ? transfer.driver.first_name + ' ' + transfer.driver.last_name : 'Not assigned'}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Vehicle:</span>
                                <span class="font-medium">${transfer.vehicle_number || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Timeline</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Initiated:</span>
                                <span class="font-medium">${transfer.initiated_at || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Departed:</span>
                                <span class="font-medium">${transfer.departed_at || 'Pending'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Arrived:</span>
                                <span class="font-medium">${transfer.arrived_at || 'Pending'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Completed:</span>
                                <span class="font-medium">${transfer.completed_at || 'Pending'}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Personnel</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Initiated By:</span>
                                <span class="font-medium">
                                    ${transfer.initiated_by ? transfer.initiated_by.first_name + ' ' + transfer.initiated_by.last_name : 'N/A'}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Received By:</span>
                                <span class="font-medium">
                                    ${transfer.received_by ? transfer.received_by.first_name + ' ' + transfer.received_by.last_name : 'Pending'}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                ${transfer.transfer_notes ? `
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold mb-3">Transfer Notes</h3>
                    <div class="bg-gray-50 rounded-md p-4">
                        <p class="text-gray-700">${transfer.transfer_notes}</p>
                    </div>
                </div>
                ` : ''}

                ${transfer.reason ? `
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold mb-3 text-red-600">Cancellation Reason</h3>
                    <div class="bg-red-50 rounded-md p-4">
                        <p class="text-red-700">${transfer.reason}</p>
                    </div>
                </div>
                ` : ''}
            `;
            
            document.getElementById('transferDetailsContent').innerHTML = content;
        });
    }

    document.getElementById('closeDetailsModal')?.addEventListener('click', () => {
        closeModal('viewDetailsModal');
    });

    document.getElementById('closeDetailsBtn')?.addEventListener('click', () => {
        closeModal('viewDetailsModal');
    });

    // Assign Driver
    document.querySelectorAll('.assign-driver-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const transferId = this.dataset.transferId;
            document.getElementById('assign_transfer_id').value = transferId;
            openModal('assignDriverModal');
            document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
        });
    });

    document.getElementById('cancelAssignBtn')?.addEventListener('click', () => {
        closeModal('assignDriverModal');
    });

    document.getElementById('assignDriverForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const transferId = document.getElementById('assign_transfer_id').value;
        const formData = new FormData(this);
        
        fetch(`/admin/warehouse-transfers/${transferId}/assign-driver`, {
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
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    });

    // Cancel Transfer
    document.querySelectorAll('.cancel-transfer-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const transferId = this.dataset.transferId;
            document.getElementById('cancel_transfer_id').value = transferId;
            openModal('cancelTransferModal');
            document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
        });
    });

    document.getElementById('cancelCancelBtn')?.addEventListener('click', () => {
        closeModal('cancelTransferModal');
    });

    document.getElementById('cancelTransferForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const transferId = document.getElementById('cancel_transfer_id').value;
        const formData = new FormData(this);
        
        fetch(`/admin/warehouse-transfers/${transferId}/update-status`, {
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
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    });

    // Close modals when clicking outside
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    });
</script>

@endsection