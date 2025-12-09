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
    .capacity-ring {
        transition: stroke-dashoffset 0.5s ease;
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

    <!-- Header Section with Gradient -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-3 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Warehouse Management
                        </h1>
                        <p class="text-gray-600 mt-1">Monitor and manage your warehouse network with real-time insights</p>
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
                            <a href="{{ route('admin.warehouses.export', 'csv') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div>
                                    <div class="font-medium">Export as CSV</div>
                                    <div class="text-xs text-gray-500">Comma-separated values</div>
                                </div>
                            </a>
                            <a href="{{ route('admin.warehouses.export', 'excel') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div>
                                    <div class="font-medium">Export as Excel</div>
                                    <div class="text-xs text-gray-500">Microsoft Excel format</div>
                                </div>
                            </a>
                            <a href="{{ route('admin.warehouses.export', 'pdf') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <div class="font-medium">Export as PDF</div>
                                    <div class="text-xs text-gray-500">Printable document</div>
                                </div>
                            </a>
                            <div class="border-t border-gray-200 my-2"></div>
                            <a href="javascript:window.print()" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                <div>
                                    <div class="font-medium">Print Page</div>
                                    <div class="text-xs text-gray-500">Print current view</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('admin.warehouses.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Warehouse
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Warehouses Card -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-3 py-1 rounded-full">Total</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Total Warehouses</h3>
                <p class="text-4xl font-bold text-gray-900 mb-3">{{ $stats['total'] }}</p>
                <div class="flex items-center justify-between text-sm pt-3 border-t border-gray-100">
                    <span class="flex items-center text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $stats['active'] }} Active
                    </span>
                    <span class="text-gray-500">{{ $stats['inactive'] }} Inactive</span>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
        </div>

        <!-- Fleet Capacity Card -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full">Capacity</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Total Capacity</h3>
                <p class="text-4xl font-bold text-gray-900 mb-1">{{ number_format($stats['total_capacity']) }}</p>
                <p class="text-xs text-gray-500 mb-3">packages</p>
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <span class="text-xs font-semibold text-indigo-600">{{ number_format($stats['avg_utilization'], 1) }}% Utilized</span>
                    </div>
                    <div class="overflow-hidden h-2 text-xs flex rounded-full bg-indigo-100">
                        <div style="width:{{ $stats['avg_utilization'] }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-500"></div>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
        </div>

        <!-- Current Occupancy Card -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-100 px-3 py-1 rounded-full">Live</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Current Occupancy</h3>
                <p class="text-4xl font-bold text-gray-900 mb-1">{{ number_format($stats['total_occupancy']) }}</p>
                <p class="text-xs text-gray-500 mb-3">packages in storage</p>
                <div class="flex items-center text-sm">
                    <span class="flex items-center text-emerald-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ number_format($stats['total_capacity'] - $stats['total_occupancy']) }}
                    </span>
                    <span class="text-gray-500 ml-1">available</span>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
        </div>

        <!-- Near Capacity Alert Card -->
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-100 px-3 py-1 rounded-full">Alert</span>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Near Capacity</h3>
                <p class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['near_capacity'] }}</p>
                <p class="text-xs text-gray-500 mb-3">warehouses ≥80% full</p>
                <div class="flex items-center text-sm">
                    @if($stats['near_capacity'] > 0)
                        <span class="flex items-center text-amber-600 animate-pulse-slow">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Requires attention
                        </span>
                    @else
                        <span class="flex items-center text-green-600">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            All good
                        </span>
                    @endif
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-amber-500 to-orange-600"></div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
        <form id="filterForm" method="GET" action="{{ route('admin.warehouses.index') }}">
            <div class="flex flex-col xl:flex-row gap-4">
                <!-- Search Bar -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="search" name="search" id="searchInput" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                           placeholder="Search by code, name, city, or manager..."/>
                </div>

                <!-- Filter Dropdowns -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <select name="type" id="typeFilter" class="px-4 py-3 border border-gray-300 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 w-full sm:w-44">
                        <option value="all">All Types</option>
                        @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>

                    <select name="status" id="statusFilter" class="px-4 py-3 border border-gray-300 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 w-full sm:w-44">
                        <option value="all">All Status</option>
                        @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>

                    <select name="state" id="stateFilter" class="px-4 py-3 border border-gray-300 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 w-full sm:w-44">
                        <option value="">All States</option>
                        @foreach($states as $state)
                        <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                        @endforeach
                    </select>

                    <button type="button" id="clearFilters" class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200">
                        Clear
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Warehouses Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Warehouse Network</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $warehouses->total() }} warehouses found</p>
                </div>
                <div class="flex items-center gap-3">
                    <button id="bulkDeleteBtn" class="hidden inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Selected
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"/>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Warehouse</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Capacity</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden xl:table-cell">Utilization</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden md:table-cell">Manager</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($warehouses as $warehouse)
                    <tr class="hover:bg-blue-50 transition-colors duration-150" data-warehouse-id="{{ $warehouse->id }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="warehouse-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2" value="{{ $warehouse->id }}"/>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $warehouse->name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $warehouse->warehouse_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $typeColors = [
                                    'main' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'regional' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'distribution' => 'bg-green-100 text-green-800 border-green-200',
                                    'sortation' => 'bg-orange-100 text-orange-800 border-orange-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $typeColors[$warehouse->type] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                {{ ucfirst($warehouse->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $warehouse->city }}</div>
                                    <div class="text-xs text-gray-500">{{ $warehouse->state }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            <div class="text-sm">
                                <div class="font-semibold text-gray-900">{{ number_format($warehouse->current_inventory ?? 0) }} / {{ number_format($warehouse->storage_capacity) }}</div>
                                <div class="text-xs text-gray-500">packages</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden xl:table-cell">
                            @php
                                $utilization = $warehouse->storage_capacity > 0 
                                    ? round(($warehouse->current_inventory / $warehouse->storage_capacity) * 100, 1) 
                                    : 0;
                                $utilizationColor = $utilization >= 80 ? 'text-red-600' : ($utilization >= 60 ? 'text-amber-600' : 'text-green-600');
                                $barColor = $utilization >= 80 ? 'bg-red-500' : ($utilization >= 60 ? 'bg-amber-500' : 'bg-green-500');
                            @endphp
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold {{ $utilizationColor }}">{{ $utilization }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $utilization }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'inactive' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'maintenance' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-200', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                                    'closed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'],
                                ];
                                $config = $statusConfig[$warehouse->status] ?? $statusConfig['inactive'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="{{ $config['icon'] }}" clip-rule="evenodd"/>
                                </svg>
                                {{ ucfirst($warehouse->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $warehouse->manager_name ?? 'N/A' }}</div>
                                @if($warehouse->manager_phone)
                                <div class="text-xs text-gray-500">{{ $warehouse->manager_phone }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(($warehouse->current_inventory ?? 0) >= ($warehouse->storage_capacity * 0.8))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 animate-pulse-slow">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Near Full
                                </span>
                                @endif
                                <div class="relative">
                                    <button class="action-menu-btn p-2 hover:bg-gray-100 rounded-lg transition-colors duration-150" data-warehouse-id="{{ $warehouse->id }}">
                                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>
                                    <div class="action-dropdown dropdown-menu absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                                        <div class="py-2">
                                            <a href="{{ route('admin.warehouses.show', $warehouse->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <div>
                                                    <div class="font-medium">View Details</div>
                                                    <div class="text-xs text-gray-500">See full information</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.warehouses.edit', $warehouse->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                <div>
                                                    <div class="font-medium">Edit Warehouse</div>
                                                    <div class="text-xs text-gray-500">Update information</div>
                                                </div>
                                            </a>
                                            <div class="border-t border-gray-200 my-2"></div>
                                            <button class="delete-warehouse-btn flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors duration-150" data-warehouse-id="{{ $warehouse->id }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <div>
                                                    <div class="font-medium">Delete Warehouse</div>
                                                    <div class="text-xs text-gray-500">Permanent removal</div>
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
                        <td colspan="9" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-20 h-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium mb-2">No warehouses found</p>
                                <p class="text-gray-400 text-sm">Try adjusting your filters or add a new warehouse</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($warehouses->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $warehouses->links() }}
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

// Checkbox functionality
const selectAllCheckbox = document.getElementById('selectAll');
const warehouseCheckboxes = document.querySelectorAll('.warehouse-checkbox');
const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        warehouseCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });
}

warehouseCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allChecked = Array.from(warehouseCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(warehouseCheckboxes).some(cb => cb.checked);
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        }
        toggleBulkActions();
    });
});

function toggleBulkActions() {
    const anyChecked = Array.from(warehouseCheckboxes).some(cb => cb.checked);
    if (bulkDeleteBtn) {
        if (anyChecked) {
            bulkDeleteBtn.classList.remove('hidden');
        } else {
            bulkDeleteBtn.classList.add('hidden');
        }
    }
}

// Bulk delete
if (bulkDeleteBtn) {
    bulkDeleteBtn.addEventListener('click', function() {
        const selectedIds = Array.from(warehouseCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) return;
        
        if (confirm(`Are you sure you want to delete ${selectedIds.length} warehouse(s)? Warehouses with active inventory cannot be deleted.`)) {
            fetch('{{ route("admin.warehouses.bulk.delete") }}', {
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
                    alert(data.message || 'Error deleting warehouses');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting warehouses');
            });
        }
    });
}

// Delete Warehouse
document.querySelectorAll('.delete-warehouse-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const warehouseId = this.dataset.warehouseId;
        
        if (confirm('Are you sure you want to delete this warehouse? This action cannot be undone. Warehouses with active inventory cannot be deleted.')) {
            fetch(`/admin/warehouses/${warehouseId}`, {
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
                    alert('Error: ' + (data.message || 'Failed to delete warehouse'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the warehouse');
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
document.querySelectorAll('#typeFilter, #statusFilter, #stateFilter').forEach(select => {
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
        window.location.href = '{{ route("admin.warehouses.index") }}';
    });
}
</script>

@endsection