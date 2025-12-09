@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .info-section {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }
    .info-row {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #6b7280;
        width: 200px;
        flex-shrink: 0;
    }
    .info-value {
        color: #1f2937;
        flex: 1;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-active { background-color: #d1fae5; color: #065f46; }
    .badge-inactive { background-color: #fee2e2; color: #991b1b; }
    .badge-maintenance { background-color: #fef3c7; color: #92400e; }
    .badge-closed { background-color: #e5e7eb; color: #374151; }
    
    .feature-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: #1e40af;
    }
    
    .table-container {
        overflow-x: auto;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        background-color: #f9fafb;
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .data-table td {
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
        font-size: 0.875rem;
        color: #374151;
    }
    
    .data-table tr:hover {
        background-color: #f9fafb;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.warehouses.index') }}" class="p-2 hover:bg-white rounded-lg transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            {{ $warehouse->name }}
                        </h1>
                        <span class="badge badge-{{ $warehouse->status }}">
                            {{ ucfirst($warehouse->status) }}
                        </span>
                    </div>
                    <p class="text-gray-600 mt-1">{{ $warehouse->warehouse_code }}</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.warehouses.edit', $warehouse->id) }}" 
                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <button onclick="deleteWarehouse({{ $warehouse->id }})" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Current Inventory</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($warehouse->current_occupancy ?? 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ number_format($warehouse->utilization_percentage ?? 0, 1) }}% Utilized
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Storage Capacity</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($warehouse->storage_capacity) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Max packages</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Shipments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $warehouse->active_shipments_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">In progress</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Staff Count</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $warehouse->staff_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Employees</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer Statistics -->
    @if(isset($transferStats))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Incoming Transfers</p>
                    <p class="text-xl font-bold text-gray-900">{{ $transferStats['incoming'] }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Outgoing Transfers</p>
                    <p class="text-xl font-bold text-gray-900">{{ $transferStats['outgoing'] }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Completed Today</p>
                    <p class="text-xl font-bold text-gray-900">{{ $transferStats['completed_today'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Basic Information
                </h3>
                <div>
                    <div class="info-row">
                        <span class="info-label">Warehouse Code</span>
                        <span class="info-value font-mono">{{ $warehouse->warehouse_code }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $warehouse->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Type</span>
                        <span class="info-value">
                            <span class="badge" style="background-color: #dbeafe; color: #1e40af;">
                                {{ ucfirst($warehouse->type) }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="badge badge-{{ $warehouse->status }}">
                                {{ ucfirst($warehouse->status) }}
                            </span>
                        </span>
                    </div>
                    @if($warehouse->description)
                    <div class="info-row">
                        <span class="info-label">Description</span>
                        <span class="info-value">{{ $warehouse->description }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Address Information -->
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Address Information
                </h3>
                <div>
                    <div class="info-row">
                        <span class="info-label">Full Address</span>
                        <span class="info-value">{{ $warehouse->address }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">City</span>
                        <span class="info-value">{{ $warehouse->city }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">State</span>
                        <span class="info-value">{{ $warehouse->state }}</span>
                    </div>
                    @if($warehouse->country)
                    <div class="info-row">
                        <span class="info-label">Country</span>
                        <span class="info-value">{{ $warehouse->country }}</span>
                    </div>
                    @endif
                    @if($warehouse->postal_code)
                    <div class="info-row">
                        <span class="info-label">Postal Code</span>
                        <span class="info-value">{{ $warehouse->postal_code }}</span>
                    </div>
                    @endif
                    @if($warehouse->latitude && $warehouse->longitude)
                    <div class="info-row">
                        <span class="info-label">Coordinates</span>
                        <span class="info-value font-mono">{{ $warehouse->latitude }}, {{ $warehouse->longitude }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Contact Information
                </h3>
                <div>
                    @if($warehouse->phone)
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value">
                            <a href="tel:{{ $warehouse->phone }}" class="text-blue-600 hover:underline">
                                {{ $warehouse->phone }}
                            </a>
                        </span>
                    </div>
                    @endif
                    @if($warehouse->email)
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">
                            <a href="mailto:{{ $warehouse->email }}" class="text-blue-600 hover:underline">
                                {{ $warehouse->email }}
                            </a>
                        </span>
                    </div>
                    @endif
                    @if($warehouse->manager_name)
                    <div class="info-row">
                        <span class="info-label">Manager Name</span>
                        <span class="info-value">{{ $warehouse->manager_name }}</span>
                    </div>
                    @endif
                    @if($warehouse->manager_phone)
                    <div class="info-row">
                        <span class="info-label">Manager Phone</span>
                        <span class="info-value">
                            <a href="tel:{{ $warehouse->manager_phone }}" class="text-blue-600 hover:underline">
                                {{ $warehouse->manager_phone }}
                            </a>
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Capacity & Operations -->
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Capacity & Operations
                </h3>
                <div>
                    <div class="info-row">
                        <span class="info-label">Storage Capacity</span>
                        <span class="info-value">{{ number_format($warehouse->storage_capacity) }} packages</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Current Occupancy</span>
                        <span class="info-value">{{ number_format($warehouse->current_occupancy ?? 0) }} packages</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Utilization</span>
                        <span class="info-value">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-xs">
                                    <div class="bg-blue-600 h-2 rounded-full" 
                                         style="width: {{ min($warehouse->utilization_percentage ?? 0, 100) }}%"></div>
                                </div>
                                <span class="text-sm font-semibold">{{ number_format($warehouse->utilization_percentage ?? 0, 1) }}%</span>
                            </div>
                        </span>
                    </div>
                    @if($warehouse->area_sqm)
                    <div class="info-row">
                        <span class="info-label">Area</span>
                        <span class="info-value">{{ number_format($warehouse->area_sqm) }} sqm</span>
                    </div>
                    @endif
                    @if($warehouse->loading_docks)
                    <div class="info-row">
                        <span class="info-label">Loading Docks</span>
                        <span class="info-value">{{ $warehouse->loading_docks }}</span>
                    </div>
                    @endif
                    @if($warehouse->staff_count)
                    <div class="info-row">
                        <span class="info-label">Staff Count</span>
                        <span class="info-value">{{ $warehouse->staff_count }} employees</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Operating Hours -->
            @if($warehouse->opening_time || $warehouse->closing_time || $warehouse->operating_days)
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Operating Hours
                </h3>
                <div>
                    @if($warehouse->opening_time || $warehouse->closing_time)
                    <div class="info-row">
                        <span class="info-label">Hours</span>
                        <span class="info-value">
                            {{ $warehouse->opening_time ? \Carbon\Carbon::parse($warehouse->opening_time)->format('g:i A') : 'N/A' }} - 
                            {{ $warehouse->closing_time ? \Carbon\Carbon::parse($warehouse->closing_time)->format('g:i A') : 'N/A' }}
                        </span>
                    </div>
                    @endif
                    @if($warehouse->operating_days && is_array($warehouse->operating_days))
                    <div class="info-row">
                        <span class="info-label">Operating Days</span>
                        <span class="info-value">
                            <div class="flex flex-wrap gap-2">
                                @foreach($warehouse->operating_days as $day)
                                    <span class="badge" style="background-color: #dbeafe; color: #1e40af;">{{ $day }}</span>
                                @endforeach
                            </div>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Recent Inventory -->
            @if($warehouse->inventories && $warehouse->inventories->count() > 0)
            <div class="info-section">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Recent Inventory
                    </h3>
                    <span class="text-sm text-gray-600">Showing latest 10 items</span>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tracking No.</th>
                                <th>Status</th>
                                <th>Weight</th>
                                <th>Checked In</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouse->inventories->take(10) as $inventory)
                            <tr>
                                <td class="font-mono text-blue-600">{{ $inventory->tracking_number ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge" style="background-color: #d1fae5; color: #065f46;">
                                        Active
                                    </span>
                                </td>
                                <td>{{ $inventory->weight ?? 'N/A' }} kg</td>
                                <td class="text-gray-600">{{ $inventory->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Recent Shipments -->
            @if($warehouse->shipments && $warehouse->shipments->count() > 0)
            <div class="info-section">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Recent Shipments
                    </h3>
                    <span class="text-sm text-gray-600">Showing latest 10 shipments</span>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Shipment ID</th>
                                <th>Destination</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouse->shipments->take(10) as $shipment)
                            <tr>
                                <td class="font-mono text-blue-600">{{ $shipment->shipment_code ?? 'N/A' }}</td>
                                <td>{{ $shipment->destination ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower(str_replace(' ', '-', $shipment->status ?? 'pending')) }}">
                                        {{ ucfirst($shipment->status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td class="text-gray-600">{{ $shipment->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Features & Capabilities -->
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Features
                </h3>
                <div class="space-y-3">
                    @if($warehouse->is_pickup_point)
                    <div class="feature-badge">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Pickup Point
                    </div>
                    @endif
                    @if($warehouse->is_delivery_point)
                    <div class="feature-badge">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Delivery Point
                    </div>
                    @endif
                    @if($warehouse->accepts_cod)
                    <div class="feature-badge">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Accepts COD
                    </div>
                    @endif
                    @if($warehouse->has_cold_storage)
                    <div class="feature-badge">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Cold Storage
                    </div>
                    @endif
                    @if($warehouse->has_24h_security)
                    <div class="feature-badge">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        24/7 Security
                    </div>
                    @endif
                    @if(!$warehouse->is_pickup_point && !$warehouse->is_delivery_point && !$warehouse->accepts_cod && !$warehouse->has_cold_storage && !$warehouse->has_24h_security)
                    <p class="text-sm text-gray-500">No features enabled</p>
                    @endif
                </div>
            </div>

            <!-- Financial Information -->
            @if($warehouse->monthly_rent || $warehouse->utility_cost)
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Financial
                </h3>
                <div>
                    @if($warehouse->monthly_rent)
                    <div class="info-row">
                        <span class="info-label">Monthly Rent</span>
                        <span class="info-value font-semibold">₦{{ number_format($warehouse->monthly_rent, 2) }}</span>
                    </div>
                    @endif
                    @if($warehouse->utility_cost)
                    <div class="info-row">
                        <span class="info-label">Utility Cost</span>
                        <span class="info-value font-semibold">₦{{ number_format($warehouse->utility_cost, 2) }}</span>
                    </div>
                    @endif
                    @if($warehouse->monthly_rent && $warehouse->utility_cost)
                    <div class="info-row">
                        <span class="info-label">Total Monthly</span>
                        <span class="info-value font-bold text-blue-600">₦{{ number_format($warehouse->monthly_rent + $warehouse->utility_cost, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Compliance & Safety -->
            @if($warehouse->license_number || $warehouse->license_expiry || $warehouse->last_inspection_date || $warehouse->safety_rating)
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Compliance & Safety
                </h3>
                <div>
                    @if($warehouse->license_number)
                    <div class="info-row">
                        <span class="info-label">License No.</span>
                        <span class="info-value font-mono">{{ $warehouse->license_number }}</span>
                    </div>
                    @endif
                    @if($warehouse->license_expiry)
                    <div class="info-row">
                        <span class="info-label">License Expiry</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($warehouse->license_expiry)->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($warehouse->last_inspection_date)
                    <div class="info-row">
                        <span class="info-label">Last Inspection</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($warehouse->last_inspection_date)->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($warehouse->safety_rating)
                    <div class="info-row">
                        <span class="info-label">Safety Rating</span>
                        <span class="info-value">
                            <span class="px-3 py-1 rounded-full text-sm font-bold
                                {{ $warehouse->safety_rating == 'A' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $warehouse->safety_rating == 'B' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $warehouse->safety_rating == 'C' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $warehouse->safety_rating == 'D' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $warehouse->safety_rating == 'F' ? 'bg-red-100 text-red-800' : '' }}">
                                Grade {{ $warehouse->safety_rating }}
                            </span>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Special Instructions -->
            @if($warehouse->special_instructions)
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Special Instructions
                </h3>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $warehouse->special_instructions }}</p>
            </div>
            @endif

            <!-- Activity Timeline -->
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Activity
                </h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Warehouse Created</p>
                            <p class="text-xs text-gray-500">{{ $warehouse->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @if($warehouse->updated_at != $warehouse->created_at)
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Last Updated</p>
                            <p class="text-xs text-gray-500">{{ $warehouse->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="info-section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Quick Actions
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.warehouses.edit', $warehouse->id) }}" 
                       class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Warehouse
                    </a>
                    
                    <button onclick="updateStatus('{{ $warehouse->id }}')" 
                            class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Update Status
                    </button>
                    
                    <button onclick="deleteWarehouse('{{ $warehouse->id }}')" 
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Warehouse
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Delete Warehouse</h3>
                <p class="text-sm text-gray-600">This action cannot be undone</p>
            </div>
        </div>
        <p class="text-gray-700 mb-6">Are you sure you want to delete this warehouse? All associated data will be permanently removed.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                Cancel
            </button>
            <button onclick="confirmDelete()" 
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Warehouse Status</h3>
        <select id="statusSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4">
            <option value="active" {{ $warehouse->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $warehouse->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="maintenance" {{ $warehouse->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            <option value="closed" {{ $warehouse->status == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        <div class="flex gap-3">
            <button onclick="closeStatusModal()" 
                    class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                Cancel
            </button>
            <button onclick="confirmStatusUpdate()" 
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200">
                Update
            </button>
        </div>
    </div>
</div>

<script>
    let warehouseIdToDelete = null;
    let warehouseIdToUpdate = null;

    function deleteWarehouse(id) {
        warehouseIdToDelete = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        warehouseIdToDelete = null;
    }

    function confirmDelete() {
        if (!warehouseIdToDelete) return;

        fetch(`/admin/warehouses/${warehouseIdToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.warehouses.index") }}';
            } else {
                alert(data.message || 'Error deleting warehouse');
                closeDeleteModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting warehouse');
            closeDeleteModal();
        });
    }

    function updateStatus(id) {
        warehouseIdToUpdate = id;
        document.getElementById('statusModal').classList.remove('hidden');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
        warehouseIdToUpdate = null;
    }

    function confirmStatusUpdate() {
        if (!warehouseIdToUpdate) return;

        const status = document.getElementById('statusSelect').value;

        fetch(`/admin/warehouses/${warehouseIdToUpdate}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error updating status');
                closeStatusModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status');
            closeStatusModal();
        });
    }

    // Close modals when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStatusModal();
        }
    });
</script>

@endsection
