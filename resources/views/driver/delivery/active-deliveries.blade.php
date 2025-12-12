@extends('driver.driver_dashboard')
@section('driver')
 <meta name="csrf-token" content="{{ csrf_token() }}">
<div class="space-y-6">
    <!-- Header -->
   <!-- Step 1: Add View Toggle Buttons to Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight">Active Deliveries</h1>
        <p class="text-muted-foreground">Manage your current delivery assignments</p>
    </div>
    <div class="flex items-center gap-2">
        <!-- NEW: View Toggle Buttons -->
        <div class="inline-flex rounded-md border border-input bg-background p-1">
            <button onclick="switchView('list')" id="list-view-btn" class="inline-flex items-center justify-center rounded px-3 h-7 text-sm font-medium bg-accent">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="8" x2="21" y1="6" y2="6"></line>
                    <line x1="8" x2="21" y1="12" y2="12"></line>
                    <line x1="8" x2="21" y1="18" y2="18"></line>
                    <line x1="3" x2="3.01" y1="6" y2="6"></line>
                    <line x1="3" x2="3.01" y1="12" y2="12"></line>
                    <line x1="3" x2="3.01" y1="18" y2="18"></line>
                </svg>
            </button>
            <button onclick="switchView('grid')" id="grid-view-btn" class="inline-flex items-center justify-center rounded px-3 h-7 text-sm font-medium hover:bg-accent/50">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect width="7" height="7" x="3" y="3" rx="1"></rect>
                    <rect width="7" height="7" x="14" y="3" rx="1"></rect>
                    <rect width="7" height="7" x="14" y="14" rx="1"></rect>
                    <rect width="7" height="7" x="3" y="14" rx="1"></rect>
                </svg>
            </button>
        </div>
        
        <button onclick="refreshPage()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                <path d="M21 3v5h-5"></path>
                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                <path d="M8 16H3v5"></path>
            </svg>
            Refresh 
        </button>
    </div>
</div>

    <!-- Statistics Cards -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Total Active</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-blue-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-500">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Pending Pickup</p>
                    <p class="text-2xl font-bold">{{ $stats['pending'] }}</p>
                </div>
                <div class="p-2 bg-yellow-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-yellow-500">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">In Transit</p>
                    <p class="text-2xl font-bold">{{ $stats['in_transit'] }}</p>
                </div>
                <div class="p-2 bg-purple-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-purple-500">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Urgent</p>
                    <p class="text-2xl font-bold">{{ $stats['urgent'] }}</p>
                </div>
                <div class="p-2 bg-red-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-red-500">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-lg border bg-card shadow-sm p-6">
        <form action="{{ route('driver.active-deliveries') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground">
        <circle cx="11" cy="11" r="8"></circle>
        <path d="m21 21-4.35-4.35"></path>
    </svg>
    <input 
        type="search" 
        name="search" 
        id="search-input"
        value="{{ $filters['search'] }}" 
        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm pl-8" 
        placeholder="Search by tracking number, address, customer... (or scan barcode)"
        autofocus
        autocomplete="off"
    />
</div>
                <button type="button" onclick="toggleFilters()" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent rounded-md px-3 h-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                        <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"></path>
                    </svg>
                    Filters
                </button>
            </div>

            <!-- Filter Panel -->
            <div id="filter-panel" class="hidden mt-4 p-4 border rounded-lg bg-muted/50">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm font-medium mb-2 block">Status</label>
                        <select name="status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="picked_up" {{ ($filters['status'] ?? '') === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                            <option value="in_transit" {{ ($filters['status'] ?? '') === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                            <option value="out_for_delivery" {{ ($filters['status'] ?? '') === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
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
                        <label class="text-sm font-medium mb-2 block">From Date</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">To Date</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="flex gap-2 mt-4">
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-4">
                        Apply Filters
                    </button>
                    <a href="{{ route('driver.active-deliveries') }}" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-4">
                        Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Deliveries List -->
   
<div class="rounded-lg border bg-card shadow-sm">
    <div class="p-6">
        <h3 class="text-xl font-semibold mb-4">Active Deliveries</h3>
        <div id="list-view" class="space-y-4">
            @forelse($shipments as $shipment)
            <div class="border rounded-lg p-4 hover:bg-muted/50 transition-colors">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h4 class="font-semibold text-lg">{{ $shipment->tracking_number }}</h4>
                                <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                    @if($shipment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($shipment->status === 'picked_up') bg-blue-100 text-blue-800
                                    @elseif($shipment->status === 'in_transit') bg-purple-100 text-purple-800
                                    @elseif($shipment->status === 'out_for_delivery') bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                </span>
                                @if($shipment->delivery_priority === 'overnight')
                                <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-red-100 text-red-800">
                                    URGENT
                                </span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="text-muted-foreground">Customer:</span>
                                    <span class="font-medium ml-2">{{ $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Contact:</span>
                                    <span class="font-medium ml-2">{{ $shipment->delivery_contact_phone }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Pickup:</span>
                                    <span class="ml-2">{{ $shipment->pickup_city }}, {{ $shipment->pickup_state }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Delivery:</span>
                                    <span class="ml-2">{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Expected:</span>
                                    <span class="ml-2">{{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('M d, Y H:i') : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Total Weight:</span>
                                    <span class="ml-2">{{ number_format($shipment->total_weight, 1) }} lbs</span>
                                </div>

                                <div>
        <span class="text-muted-foreground">Payment:</span>
        <span class="ml-2 font-medium">
            {{ strtoupper($shipment->payment_mode) }}
            @if($shipment->payment_mode === 'cod')
                <span class="text-green-600">- ${{ number_format($shipment->cod_amount, 2) }}</span>
            @endif
        </span>
    </div>
                            </div>


                            @if($shipment->payment_mode === 'cod')
<div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-600 mt-0.5 flex-shrink-0">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M12 16v-4"></path>
        <path d="M12 8h.01"></path>
    </svg>
    <div class="text-sm">
        <span class="font-semibold text-amber-800">Cash on Delivery Required</span>
        <p class="text-amber-700 mt-1">Collect ${{ number_format($shipment->cod_amount, 2) }} from customer upon delivery</p>
    </div>
</div>
@endif


                            <!-- Shipment Items Section -->
                            @if($shipment->shipmentItems && $shipment->shipmentItems->count() > 0)
                            <div class="mt-4 pt-4 border-t">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="font-medium text-sm">Shipment Items ({{ $shipment->shipmentItems->count() }})</h5>
                                    <button onclick="toggleItems({{ $shipment->id }})" class="text-xs text-blue-600 hover:text-blue-800">
                                        <span id="toggle-text-{{ $shipment->id }}">Show Items</span>
                                    </button>
                                </div>
                                <div id="items-{{ $shipment->id }}" class="hidden space-y-2">
                                    @foreach($shipment->shipmentItems as $item)
                                    <div class="flex items-start gap-3 p-3 bg-muted/30 rounded-lg text-sm">
                                        <div class="p-2 bg-white rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-600">
                                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                                                <path d="m3.3 7 8.7 5 8.7-5"></path>
                                                <path d="M12 22V12"></path>
                                            </svg> 
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-medium">{{ $item->description ?? 'N/A' }}</div> 
                                            <div class="text-xs text-muted-foreground mt-1 space-y-1">
                                                <div class="flex gap-4">
                                                    <span>Qty: <span class="font-medium">{{ $item->quantity }}</span></span>
                                                    <span>Weight: <span class="font-medium">{{ number_format($item->weight, 1) }} lbs</span></span>
                                                    @if($item->dimensions)
                                                    <span>Size: <span class="font-medium">{{ $item->dimensions }}</span></span>
                                                    @endif
                                                </div>
                                                @if($item->special_handling)
                                                <div class="flex items-center gap-1 text-orange-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                                                    </svg>
                                                    <span>Special Handling Required</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex flex-col gap-2 md:w-48">
                            <button onclick="viewDelivery({{ $shipment->id }})" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                View Details
                            </button>

                            <button
        class="inline-flex items-center justify-center text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-3"
        >
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
        <path d="M21 3v5h-5"/>
        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
    </svg>
    Get Route
</button>
                            
                            @if($shipment->status === 'pending')
                            <button onclick="updateStatus({{ $shipment->id }}, 'picked_up')" class="inline-flex items-center justify-center text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3">
                                Mark as Picked Up
                            </button>
                            @elseif($shipment->status === 'picked_up')
                            <button onclick="updateStatus({{ $shipment->id }}, 'in_transit')" class="inline-flex items-center justify-center text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3">
                                Start Transit
                            </button>
                            @elseif($shipment->status === 'in_transit')
                            <button onclick="updateStatus({{ $shipment->id }}, 'out_for_delivery')" class="inline-flex items-center justify-center text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3">
                                Out for Delivery
                            </button>
                            @elseif($shipment->status === 'out_for_delivery')
                            <button onclick="completeDelivery({{ $shipment->id }})" class="inline-flex items-center justify-center text-sm font-medium bg-green-600 text-white hover:bg-green-700 h-9 rounded-md px-3">
                                Complete Delivery
                            </button>
                            @endif
                            
                            <button onclick="reportDelay({{ $shipment->id }})" class="inline-flex items-center justify-center text-sm font-medium border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 h-9 rounded-md px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                                    <path d="M12 9v4"></path>
                                    <path d="M12 17h.01"></path>
                                </svg>
                                Report Delay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto text-muted-foreground mb-4">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
                <p class="text-muted-foreground">No active deliveries found.</p>
            </div>
            @endforelse
        </div>












        <!-- GRID VIEW: Compact card layout -->
<div id="grid-view" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($shipments as $shipment)
    <div class="border rounded-lg p-4 hover:shadow-lg transition-all bg-card">
        <div class="space-y-3">
            
            <!-- Header -->
            <div class="border-b pb-3">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <h4 class="font-semibold text-base truncate">{{ $shipment->tracking_number }}</h4>
                    @if($shipment->delivery_priority === 'overnight')
                    <span class="rounded-full border px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-800 flex-shrink-0">
                        URGENT
                    </span>
                    @endif
                </div>
                <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold
                    @if($shipment->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($shipment->status === 'picked_up') bg-blue-100 text-blue-800
                    @elseif($shipment->status === 'in_transit') bg-purple-100 text-purple-800
                    @elseif($shipment->status === 'out_for_delivery') bg-green-100 text-green-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                </span>
            </div>

            <!-- Customer Info -->
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted-foreground flex-shrink-0">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="truncate">{{ $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted-foreground flex-shrink-0">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <span class="truncate">{{ $shipment->delivery_contact_phone }}</span>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-muted/30 rounded p-2 text-xs space-y-1">
                <div class="flex items-start gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-500 mt-0.5 flex-shrink-0">
                        <circle cx="12" cy="10" r="3"></circle>
                        <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 6.9 8 11.7z"></path>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <span class="text-muted-foreground">From:</span>
                        <span class="truncate block">{{ $shipment->pickup_city }}, {{ $shipment->pickup_state }}</span>
                    </div>
                </div>
                <div class="flex items-start gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-500 mt-0.5 flex-shrink-0">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <span class="text-muted-foreground">To:</span>
                        <span class="truncate block">{{ $shipment->delivery_address }}, {{ $shipment->delivery_address_line2 }}, {{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}, {{ $shipment->delivery_country }}</span>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div>
                    <span class="text-muted-foreground block">Weight</span>
                    <span class="font-medium">{{ number_format($shipment->total_weight, 1) }} lbs</span>
                </div>
                <div>
                    <span class="text-muted-foreground block">Items</span>
                    <span class="font-medium">{{ $shipment->shipmentItems ? $shipment->shipmentItems->count() : 0 }}</span>
                </div>
            </div>

            <!-- COD Warning -->
            @if($shipment->payment_mode === 'cod')
            <div class="p-2 bg-amber-50 border border-amber-200 rounded text-xs">
                <div class="flex items-center gap-1 text-amber-800 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                        <path d="M12 18V6"></path>
                    </svg>
                    COD: ${{ number_format($shipment->cod_amount, 2) }}
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-col gap-2 pt-2 border-t">
                <button onclick="viewDelivery({{ $shipment->id }})" class="inline-flex items-center justify-center text-xs font-medium border border-input bg-background hover:bg-accent h-8 rounded px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    View Details
                </button>

                <button class="inline-flex items-center justify-center text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 h-8 rounded px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                        <path d="M21 3v5h-5"/>
                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                    </svg>
                    Get Route
                </button>

                @if($shipment->status === 'pending')
                <button onclick="updateStatus({{ $shipment->id }}, 'picked_up')" class="inline-flex items-center justify-center text-xs font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-8 rounded px-2">
                    Mark as Picked Up
                </button>
                @elseif($shipment->status === 'picked_up')
                <button onclick="updateStatus({{ $shipment->id }}, 'in_transit')" class="inline-flex items-center justify-center text-xs font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-8 rounded px-2">
                    Start Transit
                </button>
                @elseif($shipment->status === 'in_transit')
                <button onclick="updateStatus({{ $shipment->id }}, 'out_for_delivery')" class="inline-flex items-center justify-center text-xs font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-8 rounded px-2">
                    Out for Delivery
                </button>
                @elseif($shipment->status === 'out_for_delivery')
                <button onclick="completeDelivery({{ $shipment->id }})" class="inline-flex items-center justify-center text-xs font-medium bg-green-600 text-white hover:bg-green-700 h-8 rounded px-2">
                    Complete Delivery
                </button>
                @endif
            </div>

        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto text-muted-foreground mb-4">
            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
            <circle cx="17" cy="18" r="2"></circle>
            <circle cx="7" cy="18" r="2"></circle>
        </svg>
        <p class="text-muted-foreground">No active deliveries found.</p>
    </div>
    @endforelse
</div>
<!-- END GRID VIEW -->
    </div>
</div>
</div>

<!-- Quick View Modal -->
<div id="quick-view-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-semibold">Delivery Details</h3>
                <button onclick="closeQuickView()" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="quick-view-content"></div>
        </div>
    </div>
</div>

<!-- Report Delay Modal -->
<div id="delay-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">Report Delay</h3>
                <button onclick="closeDelayModal()" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="delay-form" class="space-y-4">
                <div>
                    <label class="text-sm font-medium mb-2 block">Delay Reason *</label>
                    <select name="delay_reason" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">Select reason</option>
                        <option value="traffic_congestion">Traffic Congestion</option>
                        <option value="weather_conditions">Weather Conditions</option>
                        <option value="vehicle_issues">Vehicle Issues</option>
                        <option value="address_issues">Address Issues</option>
                        <option value="customer_unavailable">Customer Unavailable</option>
                        <option value="mechanical_failure">Mechanical Failure</option>
                        <option value="road_closure">Road Closure</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium mb-2 block">Estimated Delay (hours) *</label>
                    <input type="number" name="delay_hours" min="1" max="168" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium mb-2 block">Description *</label>
                    <textarea name="delay_description" rows="3" required class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Provide details about the delay..."></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="notify_customer" id="notify_customer" class="h-4 w-4 rounded border-primary">
                    <label for="notify_customer" class="ml-2 text-sm">Notify customer about delay</label>
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="button" onclick="closeDelayModal()" class="flex-1 inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-4">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-4">
                        Report Delay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete Delivery Modal - Full Screen -->
<div id="complete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full h-full max-w-full max-h-full overflow-y-auto">
        <div class="sticky top-0 bg-white border-b z-10 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-semibold">Complete Delivery</h3>
                    <p class="text-sm text-muted-foreground mt-1" id="complete-tracking">Tracking: <span class="font-medium"></span></p>
                </div>
                <button onclick="closeCompleteModal()" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6 max-w-4xl mx-auto">
            <form id="complete-form" class="space-y-6">
                <!-- Shipment Information -->
                <div class="rounded-lg border bg-card p-6">
                    <h4 class="font-semibold text-lg mb-4">Shipment Information</h4>
                    <div id="complete-shipment-info" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <!-- Will be populated dynamically -->
                    </div>
                </div>

                <!-- Special Services -->
                <div id="special-services-section" class="rounded-lg border bg-blue-50 border-blue-200 p-6 hidden">
                    <h4 class="font-semibold text-lg mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2 text-blue-600">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                        </svg>
                        Special Services Applied
                    </h4>
                    <div id="special-services-list" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Will be populated dynamically -->
                    </div>
                </div>

                <!-- Delivery Notes -->
                <div class="rounded-lg border bg-card p-6">
                    <label class="text-sm font-medium mb-3 block">Delivery Notes (Optional)</label>
                    <textarea name="notes" rows="4" class="flex w-full rounded-md border border-input bg-background px-4 py-3 text-sm" placeholder="Add any notes about the delivery (e.g., package condition, delivery location, customer instructions followed, etc.)"></textarea>
                </div>

                <!-- Customer Signature -->
                <div class="rounded-lg border bg-card p-6">
                    <div class="mb-4">
                        <label class="text-sm font-medium mb-2 block">Customer Signature *</label>
                        <p class="text-xs text-muted-foreground">Please ask the customer to sign below to confirm delivery</p>
                    </div>
                    
                    <div class="border-2 border-gray-300 rounded-lg bg-white overflow-hidden">
                        <canvas id="signature-pad" class="w-full cursor-crosshair" style="height: 300px; touch-action: none;"></canvas>
                    </div>
                    
                    <div class="flex gap-2 mt-3">
                        <button type="button" onclick="clearSignature()" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            </svg>
                            Clear Signature
                        </button>
                        <div class="flex-1 flex items-center justify-end text-xs text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" x2="12" y1="8" y2="12"></line>
                                <line x1="12" x2="12.01" y1="16" y2="16"></line>
                            </svg>
                            Draw with mouse or touch
                        </div>
                    </div>
                    <input type="hidden" name="signature" id="signature-data">
                </div>

                <!-- Photo Upload (Optional) -->
                <div class="rounded-lg border bg-card p-6">
                    <label class="text-sm font-medium mb-3 block">Delivery Photo (Optional)</label>
                    <p class="text-xs text-muted-foreground mb-3">Take a photo of the delivered package or delivery location</p>
                    <input type="file" accept="image/*" capture="environment" id="delivery-photo" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <div id="photo-preview" class="mt-3 hidden">
                        <img id="photo-preview-img" class="w-full max-w-md mx-auto rounded-lg border">
                    </div>
                    <input type="hidden" name="photo" id="photo-data">
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 sticky bottom-0 bg-white border-t py-4">
                    <button type="button" onclick="closeCompleteModal()" class="flex-1 inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-11 rounded-md px-6">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center text-sm font-medium bg-green-600 text-white hover:bg-green-700 h-11 rounded-md px-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Complete Delivery
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>



<script>


let currentView = 'list'; // Track current view

function switchView(view) {
    currentView = view;
    const listView = document.getElementById('list-view');
    const gridView = document.getElementById('grid-view');
    const listBtn = document.getElementById('list-view-btn');
    const gridBtn = document.getElementById('grid-view-btn');
    
    if (view === 'list') {
        // Show list view, hide grid view
        listView.classList.remove('hidden');
        gridView.classList.add('hidden');
        
        // Update button styles
        listBtn.classList.add('bg-accent');
        listBtn.classList.remove('hover:bg-accent/50');
        gridBtn.classList.remove('bg-accent');
        gridBtn.classList.add('hover:bg-accent/50');
    } else {
        // Show grid view, hide list view
        listView.classList.add('hidden');
        gridView.classList.remove('hidden');
        
        // Update button styles
        gridBtn.classList.add('bg-accent');
        gridBtn.classList.remove('hover:bg-accent/50');
        listBtn.classList.remove('bg-accent');
        listBtn.classList.add('hover:bg-accent/50');
    }
    
    // Save preference to localStorage
    localStorage.setItem('deliveriesView', view);
}

// Load saved view preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('deliveriesView');
    if (savedView && savedView === 'grid') {
        switchView('grid');
    }
});


let currentShipmentId = null;

function toggleItems(shipmentId) {
    const itemsDiv = document.getElementById(`items-${shipmentId}`);
    const toggleText = document.getElementById(`toggle-text-${shipmentId}`);
    
    if (itemsDiv.classList.contains('hidden')) {
        itemsDiv.classList.remove('hidden');
        toggleText.textContent = 'Hide Items';
    } else {
        itemsDiv.classList.add('hidden');
        toggleText.textContent = 'Show Items';
    }
}

function toggleFilters() {
    document.getElementById('filter-panel').classList.toggle('hidden');
}

function refreshPage() {
    window.location.reload();
}

async function viewDelivery(shipmentId) {
    try {
        const response = await fetch(`/driver/deliveries/${shipmentId}/quick-view`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayQuickView(data.shipment);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load delivery details.');
    }
}

function displayQuickView(shipment) {
    // Payment Information HTML
    let paymentHtml = '';
    if (shipment.payment_mode === 'cod') {
        paymentHtml = `
            <div class="col-span-2 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-600 mt-1">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-amber-800">Cash on Delivery</p>
                        <p class="text-amber-700 text-sm mt-1">Collect <span class="font-bold">$${parseFloat(shipment.cod_amount).toFixed(2)}</span> from customer</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Items HTML
    let itemsHtml = '';
    if (shipment.items && shipment.items.length > 0) {
        itemsHtml = `
            <div class="col-span-2 mt-4 pt-4 border-t">
                <h4 class="font-semibold mb-3">Shipment Items (${shipment.items.length})</h4>
                <div class="space-y-2">
                    ${shipment.items.map(item => `
                        <div class="flex items-start gap-3 p-3 bg-muted/30 rounded-lg">
                            <div class="p-2 bg-white rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-600">
                                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                                    <path d="m3.3 7 8.7 5 8.7-5"></path>
                                    <path d="M12 22V12"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm">${item.description}</div>
                                <div class="text-xs text-muted-foreground mt-1">
                                    Qty: ${item.quantity} | Weight: ${item.weight} lbs
                                    ${item.dimensions ? ` | Size: ${item.dimensions}` : ''}
                                </div>
                                ${item.special_handling ? '<div class="text-xs text-orange-600 mt-1">⚠️ Special Handling Required</div>' : ''}
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    const content = document.getElementById('quick-view-content');
    content.innerHTML = `
        <div class="space-y-4">
            ${paymentHtml}
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-muted-foreground">Tracking Number</p>
                    <p class="font-medium">${shipment.tracking_number}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Status</p>
                    <p class="font-medium">${shipment.status}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Customer</p>
                    <p class="font-medium">${shipment.customer}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Contact</p>
                    <p class="font-medium">${shipment.contact_phone}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-muted-foreground">Pickup Address</p>
                    <p class="font-medium">${shipment.pickup_address}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-muted-foreground">Delivery Address</p>
                    <p class="font-medium">${shipment.delivery_address}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Expected Delivery</p>
                    <p class="font-medium">${shipment.expected_delivery}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Priority</p>
                    <p class="font-medium">${shipment.priority}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Payment Mode</p>
                    <p class="font-medium">${shipment.payment_mode.toUpperCase()}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Total Items</p>
                    <p class="font-medium">${shipment.items_count}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Total Weight</p>
                    <p class="font-medium">${shipment.total_weight}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Total Amount</p>
                    <p class="font-medium">$${parseFloat(shipment.total_amount).toFixed(2)}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-muted-foreground">Special Instructions</p>
                    <p class="font-medium">${shipment.special_instructions}</p>
                </div>
                ${itemsHtml}
            </div>
        </div>
    `;
    document.getElementById('quick-view-modal').classList.remove('hidden');
}

function closeQuickView() {
    document.getElementById('quick-view-modal').classList.add('hidden');
}

async function updateStatus(shipmentId, status) {
    if (!confirm(`Are you sure you want to update status to "${status.replace('_', ' ')}"?`)) return;
    
    try {
        const response = await fetch(`/driver/deliveries/${shipmentId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Failed to update status: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update status.');
    }
}

function reportDelay(shipmentId) {
    currentShipmentId = shipmentId;
    document.getElementById('delay-modal').classList.remove('hidden');
}

function closeDelayModal() {
    document.getElementById('delay-modal').classList.add('hidden');
    document.getElementById('delay-form').reset();
    currentShipmentId = null;
}

document.getElementById('delay-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        delay_reason: formData.get('delay_reason'),
        delay_hours: parseInt(formData.get('delay_hours')),
        delay_description: formData.get('delay_description'),
        notify_customer: formData.get('notify_customer') === 'on',
    };
    
    try {
        const response = await fetch(`/driver/deliveries/${currentShipmentId}/report-delay`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            closeDelayModal();
            window.location.reload();
        } else {
            alert('Failed to report delay: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to report delay.');
    }
});



</script>





<script>
let signaturePad = null;
let currentDeliveryShipment = null;

function completeDelivery(shipmentId) {
    currentShipmentId = shipmentId;
    
    // Fetch shipment details first
    fetch(`/driver/deliveries/${shipmentId}/quick-view`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentDeliveryShipment = data.shipment;
            populateCompleteModal(data.shipment);
            document.getElementById('complete-modal').classList.remove('hidden');
            initializeSignaturePad();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to load shipment details.');
    });
}

function populateCompleteModal(shipment) {
    // Set tracking number
    document.querySelector('#complete-tracking span').textContent = shipment.tracking_number;
    
    // Populate shipment info with payment details
    const infoHtml = `
        <div>
            <p class="text-muted-foreground">Customer</p>
            <p class="font-medium">${shipment.customer}</p>
        </div>
        <div>
            <p class="text-muted-foreground">Contact</p>
            <p class="font-medium">${shipment.contact_phone}</p>
        </div>
        <div>
            <p class="text-muted-foreground">Delivery Address</p>
            <p class="font-medium">${shipment.delivery_address}</p>
        </div>
        <div>
            <p class="text-muted-foreground">Total Items</p>
            <p class="font-medium">${shipment.items_count}</p>
        </div>
        <div>
            <p class="text-muted-foreground">Total Weight</p>
            <p class="font-medium">${shipment.total_weight}</p>
        </div>
        <div>
            <p class="text-muted-foreground">Payment Mode</p>
            <p class="font-medium">${shipment.payment_mode.toUpperCase()}</p>
        </div>
    `;
    document.getElementById('complete-shipment-info').innerHTML = infoHtml;
    
    // Add COD Warning Section (before special services)
    const specialServicesSection = document.getElementById('special-services-section');
    
    // Remove existing COD section if any
    const existingCOD = document.getElementById('cod-payment-section');
    if (existingCOD) existingCOD.remove();
    
    if (shipment.payment_mode === 'cod') {
        const codSection = document.createElement('div');
        codSection.id = 'cod-payment-section';
        codSection.className = 'rounded-lg border bg-green-50 border-green-200 p-6';
        codSection.innerHTML = `
            <h4 class="font-semibold text-lg mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2 text-green-600">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                    <path d="M12 18V6"></path>
                </svg>
                Cash on Delivery Required
            </h4>
            <div class="bg-white rounded-lg p-4 border border-green-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground mb-1">Amount to Collect</p>
                        <p class="text-3xl font-bold text-green-700">$${parseFloat(shipment.cod_amount).toFixed(2)}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600">
                            <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                            <path d="M2 10h20"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-amber-50 rounded border border-amber-200">
                    <p class="text-xs text-amber-800 flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mt-0.5 flex-shrink-0">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                            <path d="M12 9v4"></path>
                            <path d="M12 17h.01"></path>
                        </svg>
                        <span>Please ensure you collect the full amount from the customer before completing delivery.</span>
                    </p>
                </div>
            </div>
            <div class="mt-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="cod-collected" required class="h-4 w-4 rounded border-primary">
                    <span class="text-sm font-medium">I confirm that I have collected $${parseFloat(shipment.cod_amount).toFixed(2)} from the customer</span>
                </label>
            </div>
        `;
        specialServicesSection.parentNode.insertBefore(codSection, specialServicesSection);
    }
    
    // Display shipment items
    const itemsSection = document.getElementById('shipment-items-section');
    if (itemsSection) itemsSection.remove();
    
    if (shipment.items && shipment.items.length > 0) {
        const itemsDiv = document.createElement('div');
        itemsDiv.id = 'shipment-items-section';
        itemsDiv.className = 'rounded-lg border bg-card p-6';
        itemsDiv.innerHTML = `
            <h4 class="font-semibold text-lg mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                    <path d="m3.3 7 8.7 5 8.7-5"></path>
                    <path d="M12 22V12"></path>
                </svg>
                Shipment Items (${shipment.items.length})
            </h4>
            <div class="space-y-3">
                ${shipment.items.map((item, index) => `
                    <div class="flex items-start gap-3 p-4 bg-muted/30 rounded-lg border">
                        <div class="flex items-center justify-center w-8 h-8 bg-primary/10 rounded-full text-primary font-semibold text-sm">
                            ${index + 1}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium">${item.description}</div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs text-muted-foreground mt-2">
                                <div>
                                    <span class="font-medium">Quantity:</span> ${item.quantity}
                                </div>
                                <div>
                                    <span class="font-medium">Weight:</span> ${item.weight} lbs
                                </div>
                                ${item.dimensions ? `
                                    <div>
                                        <span class="font-medium">Dimensions:</span> ${item.dimensions}
                                    </div>
                                ` : ''}
                                ${item.value ? `
                                    <div>
                                        <span class="font-medium">Value:</span> $${parseFloat(item.value).toFixed(2)}
                                    </div>
                                ` : ''}
                            </div>
                            ${item.special_handling ? `
                                <div class="mt-2 flex items-center gap-1 text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded inline-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                                    </svg>
                                    <span>Special Handling Required</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        const deliveryNotesSection = document.querySelector('form#complete-form .rounded-lg.border.bg-card.p-6');
        deliveryNotesSection.parentNode.insertBefore(itemsDiv, deliveryNotesSection);
    }
    
    // Check and display special services (existing code continues...)
    const specialServices = [];
    if (shipment.insurance_required) {
        specialServices.push({
            icon: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>',
            label: 'Insurance Coverage',
            value: shipment.insurance_amount ? `$${parseFloat(shipment.insurance_amount).toFixed(2)}` : 'Yes'
        });
    }
    if (shipment.signature_required) {
        specialServices.push({
            icon: '<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/>',
            label: 'Signature Required',
            value: 'Yes'
        });
    }
    if (shipment.temperature_controlled) {
        specialServices.push({
            icon: '<path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z"/>',
            label: 'Temperature Controlled',
            value: 'Yes'
        });
    }
    if (shipment.fragile_handling) {
        specialServices.push({
            icon: '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
            label: 'Fragile Handling',
            value: 'Yes'
        });
    }
    
    if (specialServices.length > 0) {
        const servicesHtml = specialServices.map(service => `
            <div class="flex items-start gap-3 bg-white rounded-lg p-4 border border-blue-200">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                        ${service.icon}
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm">${service.label}</p>
                    <p class="text-xs text-muted-foreground">${service.value}</p>
                </div>
            </div>
        `).join('');
        
        document.getElementById('special-services-list').innerHTML = servicesHtml;
        document.getElementById('special-services-section').classList.remove('hidden');
    } else {
        document.getElementById('special-services-section').classList.add('hidden');
    }
}

function initializeSignaturePad() {
    const canvas = document.getElementById('signature-pad');
    
    // Set canvas size
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        if (signaturePad) {
            signaturePad.clear();
        }
    }
    
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    // Initialize signature pad
    signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)',
        minWidth: 1,
        maxWidth: 3,
    });
}

function clearSignature() {
    if (signaturePad) {
        signaturePad.clear();
    }
}

function closeCompleteModal() {
    document.getElementById('complete-modal').classList.add('hidden');
    document.getElementById('complete-form').reset();
    if (signaturePad) {
        signaturePad.clear();
    }
    document.getElementById('photo-preview').classList.add('hidden');
    currentShipmentId = null;
    currentDeliveryShipment = null;
}

// Handle photo upload preview
document.getElementById('delivery-photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('photo-preview-img').src = event.target.result;
            document.getElementById('photo-preview').classList.remove('hidden');
            document.getElementById('photo-data').value = event.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Handle form submission
document.getElementById('complete-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Validate signature
    if (!signaturePad || signaturePad.isEmpty()) {
        alert('Please provide customer signature before completing delivery.');
        return;
    }
    
    // Validate COD collection if applicable
    if (currentDeliveryShipment && currentDeliveryShipment.payment_mode === 'cod') {
        const codCheckbox = document.getElementById('cod-collected');
        if (!codCheckbox || !codCheckbox.checked) {
            alert('Please confirm that you have collected the COD amount before completing delivery.');
            return;
        }
    }
    
    // Get signature as base64 image
    const signatureData = signaturePad.toDataURL('image/png');
    
    const formData = new FormData(this);
    const data = {
        notes: formData.get('notes'),
        signature: signatureData,
        photo: document.getElementById('photo-data').value || null,
        cod_collected: currentDeliveryShipment && currentDeliveryShipment.payment_mode === 'cod' ? true : false,
    };
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Completing...';
    
    try {
        const response = await fetch(`/driver/deliveries/${currentShipmentId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            closeCompleteModal();
            window.location.reload();
        } else {
            alert('Failed to complete delivery: ' + result.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to complete delivery. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});
</script>



<script>
let searchTimeout = null;

// Auto-submit search when barcode is scanned
document.getElementById('search-input')?.addEventListener('input', function(e) {
    const searchValue = this.value.trim();
    
    // Clear previous timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    
    // If input looks like a tracking number (typically alphanumeric, 8+ chars)
    // Auto-submit after a short delay to ensure full barcode is captured
    if (searchValue.length >= 8) {
        searchTimeout = setTimeout(() => {
            const form = this.closest('form');
            form.submit();
        }, 500); // 500ms delay to ensure complete scan
    }
});

// Also handle Enter key for manual searches
document.getElementById('search-input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        const form = this.closest('form');
        if (this.value.trim()) {
            form.submit();
        }
    }
});

// Keep focus on search input for quick scanning
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.focus();
        
        // Re-focus if user clicks anywhere on the page (optional)
        document.addEventListener('click', function(e) {
            // Don't refocus if clicking on buttons or other inputs
            if (!e.target.closest('button, input, select, textarea, a')) {
                searchInput.focus();
            }
        });
    }
});

// Visual feedback when input is active
const searchInput = document.getElementById('search-input');
if (searchInput) {
    searchInput.addEventListener('focus', function() {
        this.classList.add('ring-2', 'ring-blue-500');
    });
    
    searchInput.addEventListener('blur', function() {
        this.classList.remove('ring-2', 'ring-blue-500');
    });
}
</script>



@endsection