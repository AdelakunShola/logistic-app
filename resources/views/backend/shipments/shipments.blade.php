@extends('admin.admin_dashboard')
@section('admin')

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">All Shipments</h1>
            <p class="text-muted-foreground">Manage and track all shipments across your logistics network</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="refreshPage()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw h-4 w-4 mr-2" aria-hidden="true">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M21 3v5h-5"></path>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                    <path d="M8 16H3v5"></path>
                </svg>
                Refresh
            </button>
            <a href="{{ route('admin.shipments.create') }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-2" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                New Shipment
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        <!-- Total Shipments Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 pt-6">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-muted-foreground">Total Shipments</p>
                        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-2 bg-primary/10 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big h-5 w-5 text-primary" aria-hidden="true">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4 text-blue-500 mr-1" aria-hidden="true">
                                <path d="M12 6v6l4 2"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            In Transit
                        </span>
                        <span class="font-medium">{{ $stats['in_transit'] }}</span>
                    </div>
                    <div aria-valuemax="100" aria-valuemin="0" role="progressbar" class="relative w-full overflow-hidden rounded-full h-1 bg-muted">
                        <div class="h-full w-full flex-1 bg-primary transition-all" style="transform:translateX(-{{ $stats['total'] > 0 ? (100 - ($stats['in_transit'] / $stats['total'] * 100)) : 100 }}%)"></div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big h-4 w-4 text-green-500 mr-1" aria-hidden="true">
                                <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                            Delivered
                        </span>
                        <span class="font-medium">{{ $stats['delivered'] }}</span>
                    </div>
                    <div aria-valuemax="100" aria-valuemin="0" role="progressbar" class="relative w-full overflow-hidden rounded-full h-1 bg-muted">
                        <div class="h-full w-full flex-1 bg-primary transition-all" style="transform:translateX(-{{ $stats['total'] > 0 ? (100 - ($stats['delivered'] / $stats['total'] * 100)) : 100 }}%)"></div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert h-4 w-4 text-yellow-500 mr-1" aria-hidden="true">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>
                            </svg>
                            Delayed/Pending
                        </span>
                        <span class="font-medium">{{ $stats['pending'] }}</span>
                    </div>
                    <div aria-valuemax="100" aria-valuemin="0" role="progressbar" class="relative w-full overflow-hidden rounded-full h-1 bg-muted">
                        <div class="h-full w-full flex-1 bg-primary transition-all" style="transform:translateX(-{{ $stats['total'] > 0 ? (100 - ($stats['pending'] / $stats['total'] * 100)) : 100 }}%)"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Weight Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 pt-6">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-muted-foreground">Total Weight</p>
                        <p class="text-2xl font-bold">{{ number_format($stats['total_weight'], 0) }} kg</p>
                    </div>
                    <div class="p-2 bg-blue-500/10 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package h-5 w-5 text-blue-500" aria-hidden="true">
                            <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                            <path d="M12 22V12"></path>
                            <polyline points="3.29 7 12 12 20.71 7"></polyline>
                            <path d="m7.5 4.27 9 5.15"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-muted-foreground">
                        Average weight per shipment: {{ number_format($stats['avg_weight'], 0) }} kg
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Value Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 pt-6">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-muted-foreground">Total Value</p>
                        <p class="text-2xl font-bold">${{ number_format($stats['total_value'], 0) }}</p>
                    </div>
                    <div class="p-2 bg-green-500/10 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign h-5 w-5 text-green-500" aria-hidden="true">
                            <line x1="12" x2="12" y1="2" y2="22"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-muted-foreground">
                        Average value per shipment: ${{ number_format($stats['avg_value'], 0) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Items Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 pt-6">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-muted-foreground">Total Items</p>
                        <p class="text-2xl font-bold">{{ number_format($stats['total_items'], 0) }}</p>
                    </div>
                    <div class="p-2 bg-purple-500/10 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package h-5 w-5 text-purple-500" aria-hidden="true">
                            <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                            <path d="M12 22V12"></path>
                            <polyline points="3.29 7 12 12 20.71 7"></polyline>
                            <path d="m7.5 4.27 9 5.15"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-muted-foreground">
                        Average items per shipment: {{ number_format($stats['avg_items'], 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="md:p-6 p-4">
            <form action="{{ route('admin.shipments.index') }}" method="GET" id="search-form">
                <div class="flex flex-wrap flex-col md:flex-row gap-2 md:gap-4">
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" aria-hidden="true">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                        <input type="search" name="search" value="{{ request('search') }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8" placeholder="Search shipments by ID, tracking number, customer, or location..."/>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="toggleFilters()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground rounded-md px-3 h-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-funnel h-4 w-4 mr-2" aria-hidden="true">
                                <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"></path>
                            </svg>
                            Filters
                            @if(!empty(array_filter($filters)))
                                <span class="ml-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-primary rounded-full">{{ count(array_filter($filters)) }}</span>
                            @endif
                        </button>
                        <div class="relative">
                            <button type="button" onclick="toggleExportMenu()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground rounded-md px-3 h-10 bg-transparent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download h-4 w-4 mr-2" aria-hidden="true">
                                    <path d="M12 15V3"></path>
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <path d="m7 10 5 5 5-5"></path>
                                </svg>
                                Export
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 ml-2" aria-hidden="true">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </button>
                            <!-- Export Dropdown Menu -->
                            <div id="export-menu" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1" role="menu">
                                    <a href="{{ route('admin.shipments.export', 'csv') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                        Export as CSV
                                    </a>
                                    <a href="{{ route('admin.shipments.export', 'excel') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                        Export as Excel
                                    </a>
                                    <a href="{{ route('admin.shipments.export', 'pdf') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                        Export as PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="setViewMode('list')" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-10 w-10 {{ $viewMode === 'list' ? 'bg-muted' : 'bg-background' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-filter h-4 w-4" aria-hidden="true">
                                    <path d="M3 6h18"></path>
                                    <path d="M7 12h10"></path>
                                    <path d="M10 18h4"></path>
                                </svg>
                            </button>
                            <button type="button" onclick="setViewMode('card')" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-10 w-10 {{ $viewMode === 'card' ? 'bg-muted' : 'bg-background' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard h-4 w-4" aria-hidden="true">
                                    <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                    <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                    <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                    <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                                </svg>
                            </button>
                            <button type="button" onclick="toggleMapView()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4" aria-hidden="true">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filter Panel (Hidden by default) -->
                <div id="filter-panel" class="hidden mt-4 p-4 border rounded-lg bg-muted/50">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="text-sm font-medium mb-2 block">Status</label>
                            <select name="status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                <option value="">All Statuses</option>
                                <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="picked_up" {{ ($filters['status'] ?? '') === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                <option value="in_transit" {{ ($filters['status'] ?? '') === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="out_for_delivery" {{ ($filters['status'] ?? '') === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                <option value="delivered" {{ ($filters['status'] ?? '') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="failed" {{ ($filters['status'] ?? '') === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="cancelled" {{ ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium mb-2 block">Type</label>
                            <select name="type" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                <option value="">All Shipment Types</option>
                                <option value="Standard Package" {{ ($filters['type'] ?? '') === 'Standard Package' ? 'selected' : '' }}>Standard Package</option>
                                <option value="Document Envelope" {{ ($filters['type'] ?? '') === 'Document Envelope' ? 'selected' : '' }}>Document Envelope</option>
                                <option value="Freight/Pallet" {{ ($filters['type'] ?? '') === 'Freight/Pallet' ? 'selected' : '' }}>Freight/Pallet</option>
                                <option value="Bulk Cargo" {{ ($filters['type'] ?? '') === 'Bulk Cargo' ? 'selected' : '' }}>Bulk Cargo</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium mb-2 block">Priority</label>
                            <select name="priority" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                <option value="">All Priorities</option>
                                <option value="standard" {{ ($filters['priority'] ?? '') === 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="express" {{ ($filters['priority'] ?? '') === 'express' ? 'selected' : '' }}>Express</option>
                                <option value="overnight" {{ ($filters['priority'] ?? '') === 'overnight' ? 'selected' : '' }}>Overnight</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium mb-2 block">Carrier</label>
                            <select name="carrier" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                <option value="">All Carriers</option>
                                @foreach($carriers as $carrier)
                                    <option value="{{ $carrier->id }}" {{ ($filters['carrier'] ?? '') == $carrier->id ? 'selected' : '' }}>{{ $carrier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium mb-2 block">Date Range</label>
                            <div class="flex gap-2">
                                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="From">
                                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="To">
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button type="submit" name="filter" value="1" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-4">
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.shipments.index', ['clear_filters' => 1]) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-4">
                            Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- List View -->
    @if($viewMode === 'list')
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 md:p-6 p-4">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Shipment List</h3>
        </div>
        <div class="md:p-6 p-0">
            <div class="rounded-md border overflow-auto">
                <div class="relative w-full overflow-auto">
                    <table class="w-full caption-bottom text-sm whitespace-nowrap min-w-[800px]">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                                    <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" class="h-4 w-4 rounded border-primary"/>
                                </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer">
                                    <div class="flex items-center">
                                        ID
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4">
                                            <path d="m21 16-4 4-4-4"></path>
                                            <path d="M17 20V4"></path>
                                            <path d="m3 8 4-4 4 4"></path>
                                            <path d="M7 4v16"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Customer</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground hidden md:table-cell">Origin</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground hidden md:table-cell">Destination</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground hidden lg:table-cell">Departure</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground hidden lg:table-cell">ETA</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground hidden md:table-cell">Type</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground hidden lg:table-cell">Priority</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground w-[80px]"></th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($shipments as $shipment)
                            <tr class="border-b transition-colors hover:bg-muted/50">
                                <td class="p-4 align-middle">
                                    <input type="checkbox" class="shipment-checkbox h-4 w-4 rounded border-primary" value="{{ $shipment->id }}" onchange="updateBulkActions()"/>
                                </td>
                                <td class="p-4 align-middle font-medium">
                                    <div>{{ $shipment->id }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $shipment->tracking_number }}</div>
                                </td>
                                <td class="p-4 align-middle">{{ $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A' }}</td>
                                <td class="p-4 align-middle hidden md:table-cell">{{ $shipment->pickup_city }}, {{ $shipment->pickup_state }}</td>
                                <td class="p-4 align-middle hidden md:table-cell">{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}</td>
                                <td class="p-4 align-middle hidden lg:table-cell">{{ $shipment->pickup_date ? $shipment->pickup_date->format('M d, Y') : 'N/A' }}</td>
                                <td class="p-4 align-middle hidden lg:table-cell">{{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('M d, Y') : 'N/A' }}</td>
                                <td class="p-4 align-middle">
                                    <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 flex items-center w-fit
                                        @if($shipment->status === 'delivered') border-transparent bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($shipment->status === 'in_transit') border-transparent bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($shipment->status === 'pending') border-transparent bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($shipment->status === 'cancelled') border-transparent bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @else text-foreground
                                        @endif">
                                        @if($shipment->status === 'pending')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                                <path d="M12 6v6l4 2"></path>
                                                <circle cx="12" cy="12" r="10"></circle>
                                            </svg>
                                        @elseif($shipment->status === 'delivered')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="m9 12 2 2 4-4"></path>
                                            </svg>
                                        @endif
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                </td>
                                <td class="p-4 align-middle hidden md:table-cell">
                                    <div class="flex items-center gap-2">
                                        @if($shipment->shipment_type === 'standard' || $shipment->shipment_type === 'freight')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                                <path d="M15 18H9"></path>
                                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                                <circle cx="17" cy="18" r="2"></circle>
                                                <circle cx="7" cy="18" r="2"></circle>
                                            </svg>
                                        @elseif($shipment->service_level === 'air')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                                <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path>
                                            </svg>
                                        @elseif($shipment->service_level === 'ocean')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                                <path d="M12 10.189V14"></path>
                                                <path d="M12 2v3"></path>
                                                <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"></path>
                                                <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-8.188-3.639a2 2 0 0 0-1.624 0L3 14a11.6 11.6 0 0 0 2.81 7.76"></path>
                                                <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1s1.2 1 2.5 1c2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"></path>
                                            </svg>
                                        @endif
                                        <span class="hidden lg:inline capitalize">{{ $shipment->service_level ?? 'road' }}</span>
                                    </div>
                                </td>
                                <td class="p-4 align-middle hidden lg:table-cell">
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors
                                        @if($shipment->delivery_priority === 'express') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @elseif($shipment->delivery_priority === 'overnight') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @endif">
                                        {{ ucfirst($shipment->delivery_priority) }}
                                    </span>
                                </td>
                                <td class="p-4 align-middle">
                                    <div class="relative">
                                        <button onclick="toggleActionMenu({{ $shipment->id }})" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </button>
                                        <!-- Action Menu -->
                                        <div id="action-menu-{{ $shipment->id }}" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                            <div class="py-1">
                                                <button onclick="viewShipment({{ $shipment->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    View Details
                                                </button>
                                                <a href="{{ route('admin.shipments.edit', $shipment->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-2">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                    Edit Shipment
                                                </a>
                                                <button onclick="duplicateShipment({{ $shipment->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect>
                                                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                                                    </svg>
                                                    Duplicate
                                                </button>


@if($shipment->status === 'delivered')
    <button onclick="openReturnModal({{ $shipment->id }})" 
            data-return-url="{{ route('admin.shipments.create-return', $shipment->id) }}"
            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
            <rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect>
            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
        </svg>
        Return
    </button>
@endif

   

                                                @if(in_array($shipment->status, ['draft', 'cancelled']))
                                                <button onclick="deleteShipment({{ $shipment->id }})" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                        <path d="M3 6h18"></path>
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="p-8 text-center text-muted-foreground">
                                    No shipments found. Try adjusting your filters or search criteria.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="flex flex-wrap gap-3 items-center justify-between px-4 py-4">
                <div class="text-sm text-muted-foreground">
                    Showing {{ $shipments->firstItem() ?? 0 }} to {{ $shipments->lastItem() ?? 0 }} of {{ $shipments->total() }} shipments
                </div>
                <div class="flex items-center space-x-2">
                    {{ $shipments->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Card View -->
    @if($viewMode === 'card')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($shipments as $shipment)
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow">
            <div class="p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-lg">{{ $shipment->id }}</h4>
                        <p class="text-xs text-muted-foreground">{{ $shipment->tracking_number }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="shipment-checkbox h-4 w-4 rounded border-primary" value="{{ $shipment->id }}" onchange="updateBulkActions()"/>
                        <div class="relative">
                            <button onclick="toggleActionMenu({{ $shipment->id }})" class="inline-flex items-center justify-center rounded-md text-sm hover:bg-accent h-8 w-8">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="19" cy="12" r="1"></circle>
                                    <circle cx="5" cy="12" r="1"></circle>
                                </svg>
                            </button>
                            <!-- Action Menu for Card -->
                            <div id="action-menu-{{ $shipment->id }}" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <button onclick="viewShipment({{ $shipment->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        View Details
                                    </button>
                                    <a href="{{ route('admin.shipments.edit', $shipment->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit Shipment
                                    </a>
                                    <button onclick="duplicateShipment({{ $shipment->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect>
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                                        </svg>
                                        Duplicate
                                    </button>
                                    @if(in_array($shipment->status, ['draft', 'cancelled']))
                                    <button onclick="deleteShipment({{ $shipment->id }})" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                        Delete
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold
                        @if($shipment->status === 'delivered') bg-green-100 text-green-800
                        @elseif($shipment->status === 'in_transit') bg-blue-100 text-blue-800
                        @elseif($shipment->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                    </span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Customer:</span>
                        <span class="font-medium">{{ $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Origin:</span>
                        <span>{{ $shipment->pickup_city }}, {{ $shipment->pickup_state }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Destination:</span>
                        <span>{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-muted-foreground">Type:</span>
                        <div class="flex items-center gap-1">
                            @if($shipment->service_level === 'air')
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                    <circle cx="17" cy="18" r="2"></circle>
                                    <circle cx="7" cy="18" r="2"></circle>
                                </svg>
                            @endif
                            <span class="capitalize">{{ $shipment->service_level ?? 'road' }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Priority:</span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                            @if($shipment->delivery_priority === 'express') bg-red-100 text-red-800
                            @elseif($shipment->delivery_priority === 'overnight') bg-purple-100 text-purple-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($shipment->delivery_priority) }}
                        </span>
                    </div>
					<div class="flex justify-between">
                        <span class="text-muted-foreground">Carrier:</span>
                        <span>{{ $shipment->carrier->name ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t">
                    <div class="flex justify-between text-xs text-muted-foreground mb-1">
                        <span>Progress</span>
                        <span>{{ $shipment->progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
    $statusProgress = [
        'draft' => 0,
        'pending' => 10,
        'picked_up' => 30,
        'in_transit' => 65,
        'out_for_delivery' => 85,
        'delivered' => 100,
        'failed' => 0,
        'returned' => 0,
        'cancelled' => 0,
    ];
    $progress = $statusProgress[$shipment->status] ?? 0;
@endphp
<div class="bg-primary h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="mt-3 flex justify-between text-xs">
                    <div>
                        <span class="text-muted-foreground">Departure:</span>
                        <span class="font-medium">{{ $shipment->pickup_date ? $shipment->pickup_date->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-muted-foreground">ETA:</span>
                        <span class="font-medium">{{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-muted-foreground mb-4">
                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                <path d="M12 22V12"></path>
                <polyline points="3.29 7 12 12 20.71 7"></polyline>
            </svg>
            <p class="text-muted-foreground">No shipments found. Try adjusting your filters or search criteria.</p>
        </div>
        @endforelse
    </div>

    <!-- Card View Pagination -->
    @if($viewMode === 'card' && $shipments->hasPages())
    <div class="flex flex-wrap gap-3 items-center justify-between mt-6">
        <div class="text-sm text-muted-foreground">
            Showing {{ $shipments->firstItem() ?? 0 }} to {{ $shipments->lastItem() ?? 0 }} of {{ $shipments->total() }} shipments
        </div>
        <div class="flex items-center space-x-2">
            {{ $shipments->links() }}
        </div>
    </div>
    @endif
    @endif
</div>

<!-- Bulk Actions Bar (Hidden by default) -->
<div id="bulk-actions-bar" class="hidden fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span id="selected-count" class="font-semibold">0 shipments selected</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="clearSelection()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
                Clear
            </button>
            <button onclick="bulkPrint()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"></path>
                    <rect x="6" y="14" width="12" height="8" rx="1"></rect>
                </svg>
                Print
            </button>
            <button onclick="bulkExport()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M12 15V3"></path>
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <path d="m7 10 5 5 5-5"></path>
                </svg>
                Export
            </button>
            <button onclick="bulkEdit()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
            </button>
            <button onclick="bulkDelete()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-white hover:bg-red-700 h-9 rounded-md px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                </svg>
                Delete
            </button>
        </div>
    </div>
</div>
<!-- Quick View Modal -->
<div id="quick-view-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-semibold">Shipment Details</h3>
                <button onclick="closeQuickView()" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="quick-view-content" class="space-y-4">
                <!-- Content will be loaded dynamically -->
            </div>

            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button onclick="closeQuickView()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-4">
                    Close
                </button>
                <button onclick="editFromQuickView()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-4">
                    Edit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Map View Modal -->
<div id="map-view-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl h-[80vh]">
        <div class="p-4 border-b flex items-center justify-between">
            <h3 class="text-xl font-semibold">Shipment Map View</h3>
            <button onclick="closeMapView()" class="text-muted-foreground hover:text-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>
        <div id="shipment-map" class="w-full h-full"></div>
    </div>
</div>

<!-- JavaScript -->
<script>
let currentShipmentId = null;
let selectedShipments = new Set();

// Toggle Filters Panel
function toggleFilters() {
    const filterPanel = document.getElementById('filter-panel');
    filterPanel.classList.toggle('hidden');
}

// Toggle Export Menu
function toggleExportMenu() {
    const menu = document.getElementById('export-menu');
    menu.classList.toggle('hidden');
}

// Close export menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('export-menu');
    const button = event.target.closest('button');
    if (!button || !button.getAttribute('onclick')?.includes('toggleExportMenu')) {
        if (menu && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    }
});

// Toggle Action Menu
function toggleActionMenu(shipmentId) {
    // Close all other menus first
    document.querySelectorAll('[id^="action-menu-"]').forEach(menu => {
        if (menu.id !== `action-menu-${shipmentId}`) {
            menu.classList.add('hidden');
        }
    });
    
    const menu = document.getElementById(`action-menu-${shipmentId}`);
    menu.classList.toggle('hidden');
}

// Close action menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleActionMenu"]') && !event.target.closest('[id^="action-menu-"]')) {
        document.querySelectorAll('[id^="action-menu-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Set View Mode
function setViewMode(mode) {
    window.location.href = `{{ route('admin.shipments.index') }}?view_mode=${mode}&${new URLSearchParams(window.location.search).toString().replace(/view_mode=[^&]*&?/, '')}`;
}

// Refresh Page
function refreshPage() {
    window.location.reload();
}

// Select All Checkboxes
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.shipment-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        if (checkbox.checked) {
            selectedShipments.add(parseInt(cb.value));
        } else {
            selectedShipments.delete(parseInt(cb.value));
        }
    });
    updateBulkActions();
}

// Update Bulk Actions Bar
function updateBulkActions() {
    selectedShipments.clear();
    document.querySelectorAll('.shipment-checkbox:checked').forEach(cb => {
        selectedShipments.add(parseInt(cb.value));
    });
    
    const bulkBar = document.getElementById('bulk-actions-bar');
    const countEl = document.getElementById('selected-count');
    
    if (selectedShipments.size > 0) {
        bulkBar.classList.remove('hidden');
        countEl.textContent = `${selectedShipments.size} shipment${selectedShipments.size > 1 ? 's' : ''} selected`;
    } else {
        bulkBar.classList.add('hidden');
    }
    
    // Update select all checkbox
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        const allCheckboxes = document.querySelectorAll('.shipment-checkbox');
        selectAll.checked = allCheckboxes.length > 0 && selectedShipments.size === allCheckboxes.length;
    }
}

// Clear Selection
function clearSelection() {
    selectedShipments.clear();
    document.querySelectorAll('.shipment-checkbox').forEach(cb => cb.checked = false);
    const selectAll = document.getElementById('select-all');
    if (selectAll) selectAll.checked = false;
    updateBulkActions();
}

// View Shipment Details (Quick View)
async function viewShipment(shipmentId) {
    try {
        const response = await fetch(`/admin/shipments/${shipmentId}/quick-view`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        
        if (!response.ok) throw new Error('Failed to load shipment details');
        
        const data = await response.json();
        
        if (data.success) {
            currentShipmentId = shipmentId;
            displayQuickView(data.shipment);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load shipment details. Please try again.');
    }
}

// Display Quick View Modal
function displayQuickView(shipment) {
    const content = document.getElementById('quick-view-content');
    content.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tracking Information -->
            <div>
                <h4 class="font-semibold mb-3 text-lg border-b pb-2">Tracking Information</h4>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Tracking Number:</dt>
                        <dd class="font-medium">${shipment.tracking_number}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Status:</dt>
                        <dd><span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800">${shipment.status}</span></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Type:</dt>
                        <dd class="capitalize">${shipment.type}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Priority:</dt>
                        <dd class="capitalize">${shipment.priority}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Carrier:</dt>
                        <dd>${shipment.carrier}</dd>
                    </div>
                </dl>
            </div>

            <!-- Customer & Dates -->
            <div>
                <h4 class="font-semibold mb-3 text-lg border-b pb-2">Customer & Schedule</h4>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Customer:</dt>
                        <dd class="font-medium">${shipment.customer}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Departure:</dt>
                        <dd>${shipment.departure}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Expected Delivery:</dt>
                        <dd>${shipment.eta}</dd>
                    </div>
                </dl>
            </div>

            <!-- Pickup Details -->
            <div>
                <h4 class="font-semibold mb-3 text-lg border-b pb-2">Pickup Details</h4>
                <dl class="space-y-2 text-sm">
                    ${shipment.pickup_company_name ? `
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Company:</dt>
                        <dd class="font-medium">${shipment.pickup_company_name}</dd>
                    </div>
                    ` : ''}
                    ${shipment.pickup_contact_name ? `
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Contact Name:</dt>
                        <dd>${shipment.pickup_contact_name}</dd>
                    </div>
                    ` : ''}
                    ${shipment.pickup_contact_phone ? `
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Phone:</dt>
                        <dd><a href="tel:${shipment.pickup_contact_phone}" class="text-primary hover:underline">${shipment.pickup_contact_phone}</a></dd>
                    </div>
                    ` : ''}
                    ${shipment.pickup_contact_email ? `
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Email:</dt>
                        <dd><a href="mailto:${shipment.pickup_contact_email}" class="text-primary hover:underline">${shipment.pickup_contact_email}</a></dd>
                    </div>
                    ` : ''}
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Location:</dt>
                        <dd>${shipment.origin}</dd>
                    </div>
                </dl>
            </div>

            <!-- Delivery Details -->
            <div>
                <h4 class="font-semibold mb-3 text-lg border-b pb-2">Delivery Details</h4>
                <dl class="space-y-2 text-sm">
                    ${shipment.delivery_company_name ? `
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Company:</dt>
                        <dd class="font-medium">${shipment.delivery_company_name}</dd>
                    </div>
                    ` : ''}
                    ${shipment.delivery_contact_name ? `
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Contact Name:</dt>
                        <dd>${shipment.delivery_contact_name}</dd>
                    </div>
                    ` : ''}
                    ${shipment.delivery_contact_phone ? `
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Phone:</dt>
                        <dd><a href="tel:${shipment.delivery_contact_phone}" class="text-primary hover:underline">${shipment.delivery_contact_phone}</a></dd>
                    </div>
                    ` : ''}
                    ${shipment.delivery_contact_email ? `
                    <div class="flex justify-between">
                        <dt class="text-muted-foreground">Email:</dt>
                        <dd><a href="mailto:${shipment.delivery_contact_email}" class="text-primary hover:underline">${shipment.delivery_contact_email}</a></dd>
                    </div>
                    ` : ''}
                    <div>
                        <dt class="text-muted-foreground mb-1">Address:</dt>
                        <dd class="font-medium">
                            ${shipment.delivery_address || ''}
                            ${shipment.delivery_address_line2 ? '<br>' + shipment.delivery_address_line2 : ''}
                            <br>${shipment.destination}
                            ${shipment.delivery_postal_code ? '<br>' + shipment.delivery_postal_code : ''}
                            ${shipment.delivery_country ? '<br>' + shipment.delivery_country : ''}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-6 pt-4 border-t">
            <h4 class="font-semibold mb-3">Shipment Progress</h4>
            <div class="flex justify-between text-sm mb-2">
                <span>Progress</span>
                <span class="font-medium">${shipment.progress}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-primary h-3 rounded-full transition-all" style="width: ${shipment.progress}%"></div>
            </div>
        </div>
    `;
    
    document.getElementById('quick-view-modal').classList.remove('hidden');
}
// Close Quick View
function closeQuickView() {
    document.getElementById('quick-view-modal').classList.add('hidden');
    currentShipmentId = null;
}

// Edit from Quick View
function editFromQuickView() {
    if (currentShipmentId) {
        window.location.href = `/admin/shipments/${currentShipmentId}/edit`;
    }
}

// Duplicate Shipment
async function duplicateShipment(shipmentId) {
    if (!confirm('Are you sure you want to duplicate this shipment?')) return;
    
    try {
        const response = await fetch(`/admin/shipments/${shipmentId}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(`Shipment duplicated successfully! New tracking number: ${data.tracking_number}`);
            if (confirm('Would you like to edit the duplicated shipment now?')) {
                window.location.href = data.redirect_url;
            } else {
                window.location.reload();
            }
        } else {
            alert('Failed to duplicate shipment: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to duplicate shipment. Please try again.');
    }
}
function openReturnModal(shipmentId) {
    // Get the button element that was clicked
    const button = event.currentTarget;
    
    // Get the URL from data attribute (recommended)
    const returnUrl = button.getAttribute('data-return-url');
    
    // Redirect to the create return page
    window.location.href = returnUrl;
}
// Delete Single Shipment
async function deleteShipment(shipmentId) {
    if (!confirm('Are you sure you want to delete this shipment? This action cannot be undone.')) return;
    
    try {
        const response = await fetch(`/admin/shipments/${shipmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        if (response.ok) {
            alert('Shipment deleted successfully!');
            window.location.reload();
        } else {
            const data = await response.json();
            alert('Failed to delete shipment: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete shipment. Please try again.');
    }
}

// Bulk Delete
async function bulkDelete() {
    if (selectedShipments.size === 0) {
        alert('Please select at least one shipment to delete.');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete ${selectedShipments.size} shipment(s)? Only draft and cancelled shipments can be deleted.`)) {
        return;
    }
    
    try {
        const response = await fetch('/admin/shipments/bulk-delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                shipment_ids: Array.from(selectedShipments)
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Failed to delete shipments: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete shipments. Please try again.');
    }
}

function bulkPrint() {
    if (selectedShipments.size === 0) {
        alert('Please select at least one shipment to print.');
        return;
    }
    
    const ids = Array.from(selectedShipments).join(',');
    window.open(`/admin/shipments/bulk-print?ids=${ids}`, '_blank');
}

// Bulk Export
function bulkExport() {
    if (selectedShipments.size === 0) {
        alert('Please select at least one shipment to export.');
        return;
    }
    
    const ids = Array.from(selectedShipments).join(',');
    window.location.href = `/admin/shipments/bulk-export?ids=${ids}`;
}

// Bulk Edit - Show modal for bulk editing
function bulkEdit() {
    if (selectedShipments.size === 0) {
        alert('Please select at least one shipment to edit.');
        return;
    }
    
    // Show bulk edit modal
    showBulkEditModal();
}

// Show Bulk Edit Modal
function showBulkEditModal() {
    const modalHTML = `
        <div id="bulk-edit-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-semibold">Bulk Edit Shipments</h3>
                        <button onclick="closeBulkEditModal()" class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <p class="text-sm text-muted-foreground mb-4">
                        Editing ${selectedShipments.size} shipment(s). Only selected fields will be updated.
                    </p>
                    
                    <form id="bulk-edit-form" class="space-y-4">
                        <div>
                            <label class="text-sm font-medium mb-2 block">
                                <input type="checkbox" name="update_status" class="mr-2">
                                Update Status
                            </label>
                            <select name="status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" disabled>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="picked_up">Picked Up</option>
                                <option value="in_transit">In Transit</option>
                                <option value="out_for_delivery">Out for Delivery</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium mb-2 block">
                                <input type="checkbox" name="update_priority" class="mr-2">
                                Update Priority
                            </label>
                            <select name="priority" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" disabled>
                                <option value="">Select Priority</option>
                                <option value="standard">Standard</option>
                                <option value="express">Express</option>
                                <option value="overnight">Overnight</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium mb-2 block">
                                <input type="checkbox" name="update_carrier" class="mr-2">
                                Update Carrier
                            </label>
                            <select name="carrier" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" disabled>
                                <option value="">Select Carrier</option>
                                @foreach($carriers ?? [] as $carrier)
                                    <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium mb-2 block">
                                <input type="checkbox" name="update_service_level" class="mr-2">
                                Update Service Level
                            </label>
                            <select name="service_level" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" disabled>
                                <option value="">Select Service Level</option>
                                <option value="road">Road</option>
                                <option value="air">Air</option>
                                <option value="ocean">Ocean</option>
                                <option value="rail">Rail</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium mb-2 block">
                                <input type="checkbox" name="update_expected_delivery" class="mr-2">
                                Update Expected Delivery Date
                            </label>
                            <input type="date" name="expected_delivery_date" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" disabled>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="send_notification" id="send_notification" class="h-4 w-4 rounded border-primary">
                            <label for="send_notification" class="ml-2 text-sm">Send notification to customers</label>
                        </div>
                    </form>
                    
                    <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                        <button onclick="closeBulkEditModal()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-4">
                            Cancel
                        </button>
                        <button onclick="submitBulkEdit()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-4">
                            Update Shipments
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Enable/disable fields based on checkboxes
    document.querySelectorAll('#bulk-edit-form input[type="checkbox"]').forEach(checkbox => {
        if (checkbox.name.startsWith('update_')) {
            const fieldName = checkbox.name.replace('update_', '');
            const field = document.querySelector(`#bulk-edit-form [name="${fieldName}"]`);
            
            checkbox.addEventListener('change', function() {
                if (field) {
                    field.disabled = !this.checked;
                }
            });
        }
    });
}

// Close Bulk Edit Modal
function closeBulkEditModal() {
    const modal = document.getElementById('bulk-edit-modal');
    if (modal) {
        modal.remove();
    }
}

// Submit Bulk Edit
async function submitBulkEdit() {
    const form = document.getElementById('bulk-edit-form');
    const formData = new FormData(form);
    
    // Collect only checked fields
    const updates = {};
    
    if (formData.get('update_status') === 'on') {
        updates.status = formData.get('status');
    }
    if (formData.get('update_priority') === 'on') {
        updates.priority = formData.get('priority');
    }
    if (formData.get('update_carrier') === 'on') {
        updates.carrier = formData.get('carrier');
    }
    if (formData.get('update_service_level') === 'on') {
        updates.service_level = formData.get('service_level');
    }
    if (formData.get('update_expected_delivery') === 'on') {
        updates.expected_delivery_date = formData.get('expected_delivery_date');
    }
    
    updates.send_notification = formData.get('send_notification') === 'on';
    
    if (Object.keys(updates).length === 1 && updates.send_notification === false) {
        alert('Please select at least one field to update.');
        return;
    }
    
    try {
        const response = await fetch('/admin/shipments/bulk-update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                shipment_ids: Array.from(selectedShipments),
                updates: updates
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            closeBulkEditModal();
            window.location.reload();
        } else {
            alert('Failed to update shipments: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update shipments. Please try again.');
    }
}
// Toggle Map View
// Toggle Map View
function toggleMapView() {
    const modal = document.getElementById('map-view-modal');
    modal.classList.remove('hidden');
    initializeMap();
}

// Close Map View
function closeMapView() {
    document.getElementById('map-view-modal').classList.add('hidden');
}

// Get color based on status
function getStatusColor(status) {
    const statusColors = {
        'draft': '#6b7280',           // Gray
        'pending': '#f59e0b',         // Amber
        'picked_up': '#3b82f6',       // Blue
        'in_transit': '#8b5cf6',      // Purple
        'out_for_delivery': '#ec4899', // Pink
        'delivered': '#10b981'        // Green
    };
    return statusColors[status] || '#6b7280';
}

// Initialize Map
function initializeMap() {
    // Basic Leaflet.js implementation
    const mapDiv = document.getElementById('shipment-map');
    
    if (!window.mapInitialized) {
        // Add Leaflet CSS if not already added
        if (!document.getElementById('leaflet-css')) {
            const link = document.createElement('link');
            link.id = 'leaflet-css';
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(link);
        }
        
        // Add Leaflet JS if not already added
        if (!window.L) {
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.onload = function() {
                renderMap();
            };
            document.head.appendChild(script);
        } else {
            renderMap();
        }
    }
}

function renderMap() {
    const mapDiv = document.getElementById('shipment-map');
    mapDiv.innerHTML = ''; // Clear existing content
    
    // Initialize map centered on US
    const map = L.map('shipment-map').setView([37.0902, -95.7129], 4);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add markers for each shipment with coordinates
    @foreach($shipments as $shipment)
        @php
            $statusColor = match($shipment->status) {
                'draft' => '#6b7280',
                'pending' => '#f59e0b',
                'picked_up' => '#3b82f6',
                'in_transit' => '#8b5cf6',
                'out_for_delivery' => '#ec4899',
                'delivered' => '#10b981',
                default => '#6b7280'
            };
        @endphp
        
        @if($shipment->pickup_latitude && $shipment->pickup_longitude)
            L.marker([{{ $shipment->pickup_latitude }}, {{ $shipment->pickup_longitude }}], {
                icon: L.divIcon({
                    html: '<div style="background-color: {{ $statusColor }}; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white;"></div>',
                    className: 'custom-marker',
                    iconSize: [24, 24]
                })
            }).bindPopup(`
                <strong>{{ $shipment->tracking_number }}</strong><br>
                Origin: {{ $shipment->pickup_city }}, {{ $shipment->pickup_state }}<br>
                Status: {{ ucfirst($shipment->status) }}
            `).addTo(map);
        @endif
        
        @if($shipment->delivery_latitude && $shipment->delivery_longitude)
            L.marker([{{ $shipment->delivery_latitude }}, {{ $shipment->delivery_longitude }}], {
                icon: L.divIcon({
                    html: '<div style="background-color: {{ $statusColor }}; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white;"></div>',
                    className: 'custom-marker',
                    iconSize: [24, 24]
                })
            }).bindPopup(`
                <strong>{{ $shipment->tracking_number }}</strong><br>
                Destination: {{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}<br>
                Status: {{ ucfirst($shipment->status) }}
            `).addTo(map);
            
            // Draw line between origin and destination
            @if($shipment->pickup_latitude && $shipment->pickup_longitude)
                L.polyline([
                    [{{ $shipment->pickup_latitude }}, {{ $shipment->pickup_longitude }}],
                    [{{ $shipment->delivery_latitude }}, {{ $shipment->delivery_longitude }}]
                ], {
                    color: '{{ $statusColor }}',
                    weight: 2,
                    opacity: 0.5,
                    dashArray: '5, 10'
                }).addTo(map);
            @endif
        @endif
    @endforeach
    
    window.mapInitialized = true;
}
// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
});
</script>

@endsection