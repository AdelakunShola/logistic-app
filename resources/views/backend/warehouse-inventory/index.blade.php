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
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-r-lg shadow-sm flex items-center gap-3" role="alert">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-r-lg shadow-sm flex items-center gap-3" role="alert">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-3 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                            Warehouse Inventory
                        </h1>
                        <p class="text-gray-600 mt-1">Track and manage packages across all warehouses</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button id="refreshBtn" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
                
                <!-- Export Dropdown -->
                <div class="relative">
                    <button id="exportBtn" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="exportDropdown" class="dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                        <div class="py-2">
                            <a href="{{ route('admin.warehouse-inventory.export', 'csv') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div>
                                    <div class="font-medium">Export as CSV</div>
                                    <div class="text-xs text-gray-500">Download inventory data</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('admin.warehouse-inventory.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-lg hover:from-emerald-700 hover:to-teal-700 shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Check In Package
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5 mb-8">
        <!-- Total Items -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">In Storage</h3>
                <p class="text-4xl font-bold text-gray-900">{{ number_format($stats['total_items']) }}</p>
                <p class="text-xs text-gray-500 mt-1">packages</p>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
        </div>

        <!-- Priority Items -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Priority</h3>
                <p class="text-4xl font-bold text-gray-900">{{ number_format($stats['priority_items']) }}</p>
                <p class="text-xs text-gray-500 mt-1">urgent packages</p>
            </div>
            <div class="h-1 bg-gradient-to-r from-amber-500 to-orange-600"></div>
        </div>

        <!-- Damaged Items -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Damaged</h3>
                <p class="text-4xl font-bold text-gray-900">{{ number_format($stats['damaged_items']) }}</p>
                <p class="text-xs text-gray-500 mt-1">need attention</p>
            </div>
            <div class="h-1 bg-gradient-to-r from-red-500 to-red-600"></div>
        </div>

        <!-- Special Handling -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Special Handling</h3>
                <p class="text-4xl font-bold text-gray-900">{{ number_format($stats['special_handling']) }}</p>
                <p class="text-xs text-gray-500 mt-1">require care</p>
            </div>
            <div class="h-1 bg-gradient-to-r from-purple-500 to-purple-600"></div>
        </div>

        <!-- Overdue Items -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Overdue</h3>
                <p class="text-4xl font-bold text-gray-900">{{ number_format($stats['overdue_items']) }}</p>
                <p class="text-xs text-gray-500 mt-1">&gt;7 days</p>
            </div>
            <div class="h-1 bg-gradient-to-r from-rose-500 to-pink-600"></div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
        <form id="filterForm" method="GET" action="{{ route('admin.warehouse-inventory.index') }}">
            <div class="flex flex-col xl:flex-row gap-4">
                <!-- Search Bar -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="search" name="search" id="searchInput" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200" 
                           placeholder="Search by tracking number, warehouse, location..."/>
                </div>

                <!-- Filter Dropdowns -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <select name="warehouse" id="warehouseFilter" class="px-4 py-3 border border-gray-300 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 w-full sm:w-44">
                        <option value="all">All Warehouses</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('warehouse') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                        @endforeach
                    </select>

                    <select name="status" id="statusFilter" class="px-4 py-3 border border-gray-300 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 w-full sm:w-44">
                        <option value="">All Status</option>
                        <option value="in_storage" {{ request('status') == 'in_storage' ? 'selected' : '' }}>In Storage</option>
                        <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                    </select>

                    <select name="condition" id="conditionFilter" class="px-4 py-3 border border-gray-300 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 w-full sm:w-44">
                        <option value="all">All Conditions</option>
                        @foreach($conditions as $condition)
                        <option value="{{ $condition }}" {{ request('condition') == $condition ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $condition)) }}
                        </option>
                        @endforeach
                    </select>

                    <select name="priority" id="priorityFilter" class="px-4 py-3 border border-gray-300 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 w-full sm:w-44">
                        <option value="">All Priorities</option>
                        <option value="priority" {{ request('priority') == 'priority' ? 'selected' : '' }}>Priority Only</option>
                        <option value="special_handling" {{ request('priority') == 'special_handling' ? 'selected' : '' }}>Special Handling</option>
                    </select>

                    <button type="button" id="clearFilters" class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200">
                        Clear
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Current Inventory</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $inventories->total() }} items found</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Warehouse</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Condition</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden xl:table-cell">Checked In</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden xl:table-cell">Duration</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($inventories as $inventory)
                    <tr class="hover:bg-emerald-50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $inventory->shipment->tracking_number ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $inventory->shipment->recipient_name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $inventory->warehouse->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $inventory->warehouse->warehouse_code ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            <div class="text-sm text-gray-900">{{ $inventory->storage_location ?? '-' }}</div>
                            @if($inventory->bin_number)
                            <div class="text-xs text-gray-500">Bin: {{ $inventory->bin_number }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $conditionConfig = [
                                    'good' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200'],
                                    'damaged' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200'],
                                    'requires_attention' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-200'],
                                ];
                                $config = $conditionConfig[$inventory->package_condition] ?? $conditionConfig['good'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                                {{ ucfirst(str_replace('_', ' ', $inventory->package_condition)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 hidden xl:table-cell">
                            <div class="text-sm text-gray-900">{{ $inventory->checked_in_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $inventory->checked_in_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 hidden xl:table-cell">
                            <div class="text-sm font-semibold text-gray-900">{{ number_format($inventory->storage_duration) }}h</div>
                            @if($inventory->is_overdue)
                            <span class="text-xs text-red-600 font-medium">Overdue</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                @if($inventory->is_in_storage)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    In Storage
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                    Checked Out
                                </span>
                                @endif
                                @if($inventory->is_priority)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Priority
                                </span>
                                @endif
                                @if($inventory->requires_special_handling)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                    Special
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <div class="relative">
                                    <button class="action-menu-btn p-2 hover:bg-gray-100 rounded-lg transition-colors duration-150" data-inventory-id="{{ $inventory->id }}">
                                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>
                                    <div class="action-dropdown dropdown-menu absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                                        <div class="py-2">
                                            <a href="{{ route('admin.warehouse-inventory.show', $inventory->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <div>
                                                    <div class="font-medium">View Details</div>
                                                    <div class="text-xs text-gray-500">Full information</div>
                                                </div>
                                            </a>
                                            @if($inventory->is_in_storage)
                                            <form action="{{ route('admin.warehouse-inventory.check-out', $inventory->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150 w-full text-left">
                                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">Check Out</div>
                                                        <div class="text-xs text-gray-500">Release package</div>
                                                    </div>
                                                </button>
                                            </form>
                                            @endif
                                            <div class="border-t border-gray-200 my-2"></div>
                                            <button class="delete-inventory-btn flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors duration-150" data-inventory-id="{{ $inventory->id }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <div>
                                                    <div class="font-medium">Delete Entry</div>
                                                    <div class="text-xs text-gray-500">Remove from system</div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-20 h-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium mb-2">No inventory items found</p>
                                <p class="text-gray-400 text-sm">Try adjusting your filters or check in a new package</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($inventories->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $inventories->links() }}
        </div>
        @endif
    </div>
</div>

<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Dropdown handlers
function setupDropdown(buttonId, dropdownId) {
    const button = document.getElementById(buttonId);
    const dropdown = document.getElementById(dropdownId);
    
    if (button && dropdown) {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.dropdown-menu').forEach(d => {
                if (d !== dropdown) d.classList.remove('show');
            });
            dropdown.classList.toggle('show');
        });
    }
}

setupDropdown('exportBtn', 'exportDropdown');

// Close dropdowns when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
        document.querySelectorAll('.dropdown-menu').forEach(d => {
            d.classList.remove('show');
        });
    }
});

// Action menu dropdowns
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

// Delete Inventory
document.querySelectorAll('.delete-inventory-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const inventoryId = this.dataset.inventoryId;
        
        if (confirm('Are you sure you want to delete this inventory entry? This action cannot be undone.')) {
            fetch(`/admin/warehouse-inventory/${inventoryId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete inventory entry'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the inventory entry');
            });
        }
        
        document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
    });
});

// Refresh button
const refreshBtn = document.getElementById('refreshBtn');
if (refreshBtn) {
    refreshBtn.addEventListener('click', () => {
        location.reload();
    });
}

// Filter form auto-submit
document.querySelectorAll('#warehouseFilter, #statusFilter, #conditionFilter, #priorityFilter').forEach(select => {
    select.addEventListener('change', function() {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) filterForm.submit();
    });
});

// Search with debounce
let searchTimeout;
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const filterForm = document.getElementById('filterForm');
            if (filterForm) filterForm.submit();
        }, 500);
    });
}

// Clear filters
const clearFiltersBtn = document.getElementById('clearFilters');
if (clearFiltersBtn) {
    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = '{{ route("admin.warehouse-inventory.index") }}';
    });
}
</script>

@endsection