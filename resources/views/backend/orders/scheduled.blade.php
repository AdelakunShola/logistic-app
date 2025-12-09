@extends('admin.admin_dashboard')
@section('admin')

<div class="">
    {{-- Statistics Cards --}}
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 mb-6">
        {{-- Today's Deliveries --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Today's Deliveries</h3>
                <div class="p-2 bg-primary/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days h-4 w-4 text-primary">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                        <path d="M8 14h.01"></path>
                        <path d="M12 14h.01"></path>
                        <path d="M16 14h.01"></path>
                        <path d="M8 18h.01"></path>
                        <path d="M12 18h.01"></path>
                        <path d="M16 18h.01"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $stats['today_deliveries'] }}</div>
                <div class="flex items-center text-xs text-muted-foreground">
                    <span class="text-green-600 dark:text-green-400">{{ $stats['today_on_schedule'] }} on schedule</span>
                    <span class="mx-1">•</span>
                    <span class="text-red-600 dark:text-red-400">{{ $stats['today_delayed'] }} delayed</span>
                </div>
            </div>
        </div>

        {{-- This Week --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">This Week</h3>
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days h-4 w-4 text-blue-500">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                        <path d="M8 14h.01"></path>
                        <path d="M12 14h.01"></path>
                        <path d="M16 14h.01"></path>
                        <path d="M8 18h.01"></path>
                        <path d="M12 18h.01"></path>
                        <path d="M16 18h.01"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $stats['week_deliveries'] }}</div>
                <div class="flex items-center text-xs text-muted-foreground">
                    <span class="text-green-600 dark:text-green-400">+{{ $stats['week_growth'] }}% from last week</span>
                </div>
            </div>
        </div>

        {{-- On-Time Rate --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">On-Time Rate</h3>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-timer h-4 w-4 text-green-500">
                        <line x1="10" x2="14" y1="2" y2="2"></line>
                        <line x1="12" x2="15" y1="14" y2="11"></line>
                        <circle cx="12" cy="14" r="8"></circle>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $stats['on_time_rate'] }}%</div>
                <div class="relative h-2 w-full overflow-hidden rounded-full bg-secondary mt-2">
                    <div class="h-full w-full flex-1 bg-primary transition-all" style="transform:translateX(-{{ 100 - $stats['on_time_rate'] }}%)"></div>
                </div>
            </div>
        </div>

        {{-- Avg. Delivery Time --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Avg. Delivery Time</h3>
                <div class="p-2 bg-orange-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4 text-orange-500">
                        <path d="M12 6v6l4 2"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $stats['avg_delivery_time'] }} mins</div>
                <p class="text-xs text-muted-foreground">
                    <span class="text-green-600 dark:text-green-400">-{{ $stats['time_improvement'] }} mins</span> from average
                </p>
            </div>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm mb-6">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-funnel h-5 w-5">
                        <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"></path>
                    </svg>
                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Delivery Schedule</h3>
                </div>
                <button 
    onclick="window.location.href='{{ route('admin.shipments.create') }}'" 
    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
    
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2 h-4 w-4">
        <path d="M5 12h14"></path>
        <path d="M12 5v14"></path>
    </svg>
    
    Schedule Delivery
</button>

            </div>
        </div>
        <div class="p-4 md:p-6 pt-0">
            <form method="GET" action="{{ route('admin.schedule.index') }}" class="flex flex-col gap-4">
                <div class="flex flex-col gap-4 md:flex-row">
                    {{-- Search --}}
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2 top-2.5 h-4 w-4 text-muted-foreground">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                        <input name="search" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8" placeholder="Search by order ID, customer, or address..." value="{{ request('search') }}"/>
                    </div>

                    {{-- Status Filter --}}
                    <select name="status" class="flex h-10 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-full md:w-[180px]">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                        <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                    </select>

                    {{-- Priority Filter --}}
                    <select name="priority" class="flex h-10 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-full md:w-[180px]">
                        <option value="">All Priorities</option>
                        <option value="standard" {{ request('priority') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="express" {{ request('priority') == 'express' ? 'selected' : '' }}>Express</option>
                        <option value="overnight" {{ request('priority') == 'overnight' ? 'selected' : '' }}>Overnight</option>
                    </select>

                    {{-- Type Filter --}}
                    <select name="type" class="flex h-10 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-full md:w-[180px]">
                        <option value="">All Types</option>
                        <option value="Standard Package" {{ request('type') == 'Standard Package' ? 'selected' : '' }}>Standard Package</option>
                        <option value="Document Envelope" {{ request('type') == 'Document Envelope' ? 'selected' : '' }}>Document</option>
                        <option value="Freight/Pallet" {{ request('type') == 'Freight/Pallet' ? 'selected' : '' }}>Freight</option>
                        <option value="Bulk Cargo" {{ request('type') == 'Bulk Cargo' ? 'selected' : '' }}>Bulk</option>
                    </select>

                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="space-y-4">
        <div class="items-center rounded-md bg-muted p-1 text-muted-foreground flex flex-wrap gap-3 h-full justify-start">
            <button type="button" onclick="filterByTab('all')" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 tab-btn {{ !request('tab') || request('tab') == 'all' ? 'bg-background text-foreground shadow-sm' : '' }}" data-tab="all">
                All Deliveries
            </button>
            <button type="button" onclick="filterByTab('today')" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 tab-btn {{ request('tab') == 'today' ? 'bg-background text-foreground shadow-sm' : '' }}" data-tab="today">
                Today
            </button>
            <button type="button" onclick="filterByTab('tomorrow')" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 tab-btn {{ request('tab') == 'tomorrow' ? 'bg-background text-foreground shadow-sm' : '' }}" data-tab="tomorrow">
                Tomorrow
            </button>
            <button type="button" onclick="filterByTab('week')" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 tab-btn {{ request('tab') == 'week' ? 'bg-background text-foreground shadow-sm' : '' }}" data-tab="week">
                This Week
            </button>
            <button type="button" onclick="filterByTab('calendar')" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 tab-btn {{ request('tab') == 'calendar' ? 'bg-background text-foreground shadow-sm' : '' }}" data-tab="calendar">
                Calendar View
            </button>
        </div>

        {{-- Shipments Table --}}
        <div class="rounded-md border">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="p-4 text-left font-medium">Order ID</th>
                            <th class="p-4 text-left font-medium">Customer</th>
                            <th class="p-4 text-left font-medium">Delivery Address</th>
                            <th class="p-4 text-left font-medium">Schedule</th>
                            <th class="p-4 text-left font-medium">Status</th>
                            <th class="p-4 text-left font-medium">Priority</th>
                            <th class="p-4 text-left font-medium">Driver</th>
                            <th class="p-4 text-left font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments as $shipment)
                        <tr class="border-b hover:bg-muted/50">
                            {{-- Order ID --}}
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">{{ $shipment->tracking_number }}</span>
                                    <button onclick="copyToClipboard('{{ $shipment->tracking_number }}')" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-6 w-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy h-3 w-3">
                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect>
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                            {{-- Customer --}}
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8">
                                        @if($shipment->customer && $shipment->customer->photo)
                                            <img alt="{{ $shipment->customer->first_name }}" src="{{ asset($shipment->customer->photo) }}" class="h-full w-full object-cover"/>
                                        @else
                                            <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">
                                                {{ $shipment->customer ? strtoupper(substr($shipment->customer->first_name, 0, 1) . substr($shipment->customer->last_name, 0, 1)) : 'N/A' }}
                                            </span>
                                        @endif
                                    </span>
                                    <div>
                                        <p class="font-medium">{{ $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A' }}</p>
                                        <p class="text-sm text-muted-foreground">{{ $shipment->delivery_contact_phone ?? $shipment->pickup_contact_phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Delivery Address --}}
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4 text-muted-foreground">
                                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span class="text-sm">{{ $shipment->delivery_address }}, {{ $shipment->delivery_city }}, {{ $shipment->delivery_state }} {{ $shipment->delivery_postal_code }}</span>
                                </div>
                            </td>

                            {{-- Schedule --}}
                            <td class="p-4">
                                <div>
                                    <p class="font-medium">{{ $shipment->pickup_date ? $shipment->pickup_date->format('Y-m-d') : 'N/A' }}</p>
                                    <p class="text-sm text-muted-foreground">{{ $shipment->pickup_date ? $shipment->pickup_date->format('H:i') : '' }} - {{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('H:i') : '' }}</p>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="p-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                        'pending' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'picked_up' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
                                        'in_transit' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'out_for_delivery' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-primary/80 {{ $statusColors[$shipment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                </span>
                            </td>

                            {{-- Priority --}}
                            <td class="p-4">
                                @php
                                    $priorityColors = [
                                        'standard' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'express' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'overnight' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-primary/80 {{ $priorityColors[$shipment->delivery_priority] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($shipment->delivery_priority) }}
                                </span>
                            </td>

                            {{-- Driver --}}
                            <td class="p-4">
                                @if($shipment->assignedDriver)
                                    <div>
                                        <p class="font-medium">{{ $shipment->assignedDriver->first_name }} {{ $shipment->assignedDriver->last_name }}</p>
                                        <p class="text-sm text-muted-foreground">DRV-{{ str_pad($shipment->assignedDriver->id, 3, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                @else
                                    <span class="text-sm text-muted-foreground">Unassigned</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="p-4">
                                <div class="relative">
                                    <button onclick="toggleActionMenu({{ $shipment->id }})" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-vertical h-4 w-4">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="12" cy="5" r="1"></circle>
                                            <circle cx="12" cy="19" r="1"></circle>
                                        </svg>
                                    </button>
                                    <div id="action-menu-{{ $shipment->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 border">
                                        <div class="py-1">
                                            <button onclick="viewDeliveryDetails({{ $shipment->id }})" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                View Details
                                            </button>
                                            <button onclick="openRescheduleModal({{ $shipment->id }})" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                    <path d="M8 2v4"></path>
                                                    <path d="M16 2v4"></path>
                                                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                    <path d="M3 10h18"></path>
                                                </svg>
                                                Reschedule
                                            </button>
                                            <button onclick="openAssignDriverModal({{ $shipment->id }})" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <line x1="19" x2="19" y1="8" y2="14"></line>
                                                    <line x1="22" x2="16" y1="11" y2="11"></line>
                                                </svg>
                                                Assign Driver
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-muted-foreground">
                                No deliveries scheduled yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($shipments->hasPages())
        <div class="flex items-center justify-between px-4 py-3">
            {{ $shipments->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Schedule New Delivery Modal --}}
<div id="scheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeModalOnBackdrop(event, 'scheduleModal')">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto m-4" onclick="event.stopPropagation()">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Schedule New Delivery</h2>
                <p class="text-sm text-muted-foreground mt-1">Enter delivery details to schedule a new delivery</p>
            </div>
            <button onclick="closeModal('scheduleModal')" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            {{-- Step Tabs --}}
            <div class="flex gap-4 mb-6 border-b">
                <button type="button" onclick="changeStep(1)" id="tab-step-1" class="px-4 py-2 font-medium border-b-2 border-primary text-primary step-tab">Customer</button>
                <button type="button" onclick="changeStep(2)" id="tab-step-2" class="px-4 py-2 font-medium text-muted-foreground step-tab">Delivery Details</button>
                <button type="button" onclick="changeStep(3)" id="tab-step-3" class="px-4 py-2 font-medium text-muted-foreground step-tab">Package</button>
                <button type="button" onclick="changeStep(4)" id="tab-step-4" class="px-4 py-2 font-medium text-muted-foreground step-tab">Driver</button>
            </div>

            <form id="scheduleDeliveryForm" action="{{ route('admin.shipments.store') }}" method="POST">
                @csrf

                {{-- Step 1: Customer --}}
                <div id="step-1" class="step-content">
                    <div class="space-y-4">
                        <div class="flex gap-4 mb-4">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="customer_type" value="existing" checked onchange="toggleCustomerType()" class="form-radio"/>
                                <span>Existing Customer</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="customer_type" value="new" onchange="toggleCustomerType()" class="form-radio"/>
                                <span>New Customer</span>
                            </label>
                        </div>

                        <div id="existing-customer" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Select Customer</label>
                                <select name="customer_id" id="customer_id" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm">
                                    <option value="">Select customer</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }} - {{ $customer->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="new-customer" class="hidden space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Full Name</label>
                                    <input type="text" name="pickup_contact_name" placeholder="Enter full name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Company (Optional)</label>
                                    <input type="text" name="pickup_company_name" placeholder="Enter company name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Email Address</label>
                                    <input type="email" name="pickup_contact_email" placeholder="Enter email address" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Phone Number</label>
                                    <input type="tel" name="pickup_contact_phone" placeholder="Enter phone number" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="closeModal('scheduleModal')" class="inline-flex items-center justify-center rounded-md border px-4 py-2 text-sm font-medium">Cancel</button>
                        <button type="button" onclick="nextStep()" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground px-4 py-2 text-sm font-medium">Next: Delivery Details</button>
                    </div>
                </div>

                {{-- Step 2: Delivery Details --}}
                <div id="step-2" class="step-content hidden">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Delivery Address</label>
                            <textarea name="delivery_address" rows="2" required placeholder="Enter full delivery address" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">City</label>
                                <input type="text" name="delivery_city" required placeholder="Enter city" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">State/Province</label>
                                <input type="text" name="delivery_state" required placeholder="Enter state" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Zip/Postal Code</label>
                                <input type="text" name="delivery_postal_code" required placeholder="Enter zip code" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Delivery Date</label>
                                <input type="date" name="pickup_date" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Time Slot</label>
                                <select name="time_slot" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                    <option value="">Select time slot</option>
                                    <option value="09:00-11:00">09:00 - 11:00</option>
                                    <option value="11:00-13:00">11:00 - 13:00</option>
                                    <option value="13:00-15:00">13:00 - 15:00</option>
                                    <option value="15:00-17:00">15:00 - 17:00</option>
                                    <option value="17:00-19:00">17:00 - 19:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Delivery Type</label>
                                <select name="shipment_type" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                    <option value="">Select delivery type</option>
                                    <option value="Standard Package">Standard Package</option>
                                    <option value="Document Envelope">Document</option>
                                    <option value="Freight/Pallet">Freight</option>
                                    <option value="Bulk Cargo">Bulk Cargo</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Priority</label>
                                <select name="delivery_priority" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                    <option value="">Select priority</option>
                                    <option value="standard">Standard</option>
                                    <option value="express">Express</option>
                                    <option value="overnight">Overnight</option>
                                </select>
                            </div>
                        </div>

                        

                        {{-- Hidden pickup fields with same values --}}
                        <input type="hidden" name="pickup_contact_name" id="pickup_contact_name_hidden"/>
                        <input type="hidden" name="pickup_contact_phone" id="pickup_contact_phone_hidden"/>
                        <input type="hidden" name="pickup_address" id="pickup_address_hidden"/>
                        <input type="hidden" name="pickup_city" id="pickup_city_hidden"/>
                        <input type="hidden" name="pickup_state" id="pickup_state_hidden"/>
                        <input type="hidden" name="pickup_postal_code" id="pickup_postal_code_hidden"/>
                        <input type="hidden" name="pickup_country" value="USA"/>
                        <input type="hidden" name="delivery_country" value="USA"/>
                        <input type="hidden" name="delivery_contact_name" id="delivery_contact_name_hidden"/>
                        <input type="hidden" name="delivery_contact_phone" id="delivery_contact_phone_hidden"/>
                    </div>

                    <div class="flex justify-between gap-2 mt-6">
                        <button type="button" onclick="previousStep()" class="inline-flex items-center justify-center rounded-md border px-4 py-2 text-sm font-medium">Back: Customer</button>
                        <button type="button" onclick="nextStep()" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground px-4 py-2 text-sm font-medium">Next: Package Details</button>
                    </div>
                </div>

                {{-- Step 3: Package --}}
                <div id="step-3" class="step-content hidden">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Number of Items</label>
                                <input type="number" name="number_of_items" min="1" value="1" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Total Value</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-muted-foreground">$</span>
                                    <input type="number" name="total_value" step="0.01" min="0" placeholder="Enter package value" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm pl-7"/>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Weight (kg)</label>
                                <input type="number" name="total_weight" step="0.01" min="0" placeholder="Enter weight" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Package Type</label>
                                <select name="package_type" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                    <option value="">Select type</option>
                                    <option value="box">Box</option>
                                    <option value="envelope">Envelope</option>
                                    <option value="pallet">Pallet</option>
                                    <option value="crate">Crate</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Dimensions (cm)</label>
                            <div class="grid grid-cols-3 gap-4">
                                <input type="number" name="length" placeholder="L" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                                <input type="number" name="width" placeholder="W" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                                <input type="number" name="height" placeholder="H" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Special Handling Requirements</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="fragile_handling" value="1" class="form-checkbox"/>
                                    <span>Fragile</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="perishable" value="1" class="form-checkbox"/>
                                    <span>Perishable</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_hazardous" value="1" class="form-checkbox"/>
                                    <span>Hazardous</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="temperature_controlled" value="1" class="form-checkbox"/>
                                    <span>Temperature Controlled</span>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium">Special Instructions</label>
                            <textarea name="special_instructions" rows="4" class="w-full px-4 py-2 border rounded-md" placeholder="Enter any special handling instructions, delivery notes, or requirements..."></textarea>
                        </div>
                    </div>

                    <div class="flex justify-between gap-2 mt-6">
                        <button type="button" onclick="previousStep()" class="inline-flex items-center justify-center rounded-md border px-4 py-2 text-sm font-medium">Back: Delivery Details</button>
                        <button type="button" onclick="nextStep()" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground px-4 py-2 text-sm font-medium">Next: Driver Assignment</button>
                    </div>
                </div>

                {{-- Step 4: Driver --}}
                <div id="step-4" class="step-content hidden">
                    <div class="space-y-4">
                        <div class="flex gap-4 mb-4">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="driver_assignment" value="auto" checked class="form-radio"/>
                                <span>Auto-Assign Driver</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="driver_assignment" value="manual" class="form-radio"/>
                                <span>Manually Assign Driver</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Select Driver (Optional)</label>
                            <select name="assigned_driver_id" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                <option value="">Select driver</option>
                                @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->first_name }} {{ $driver->last_name }} - DRV-{{ str_pad($driver->id, 3, '0', STR_PAD_LEFT) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Delivery Summary --}}
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 16v-4"></path>
                                    <path d="M12 8h.01"></path>
                                </svg>
                                <h3 class="font-semibold">Delivery Summary</h3>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-muted-foreground">Customer:</span>
                                    <span id="summary-customer" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-muted-foreground">Delivery Address:</span>
                                    <span id="summary-address" class="font-medium text-right">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-muted-foreground">Scheduled Date:</span>
                                    <span id="summary-date" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-muted-foreground">Time Slot:</span>
                                    <span id="summary-time" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-muted-foreground">Package:</span>
                                    <span id="summary-package" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-muted-foreground">Delivery Type:</span>
                                    <span id="summary-type" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-muted-foreground">Priority:</span>
                                    <span id="summary-priority" class="font-medium">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between gap-2 mt-6">
                        <button type="button" onclick="previousStep()" class="inline-flex items-center justify-center rounded-md border px-4 py-2 text-sm font-medium">Back: Package Details</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground px-4 py-2 text-sm font-medium">Schedule Delivery</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reschedule Modal --}}
<div id="rescheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeModalOnBackdrop(event, 'rescheduleModal')">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md m-4" onclick="event.stopPropagation()">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold">Reschedule Delivery</h2>
                <p class="text-sm text-muted-foreground mt-1">Select a new date and time for this delivery</p>
            </div>
            <button onclick="closeModal('rescheduleModal')" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <form id="rescheduleForm" class="p-6 space-y-4">
            <input type="hidden" id="reschedule_shipment_id" name="shipment_id"/>
            
            <div>
                <label class="block text-sm font-medium mb-2">New Delivery Date</label>
                <input type="date" name="new_delivery_date" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Time Slot</label>
                <select name="new_time_slot" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="">Select time slot</option>
                    <option value="09:00-11:00">09:00 - 11:00</option>
                    <option value="11:00-13:00">11:00 - 13:00</option>
                    <option value="13:00-15:00">13:00 - 15:00</option>
                    <option value="15:00-17:00">15:00 - 17:00</option>
                    <option value="17:00-19:00">17:00 - 19:00</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Reason for Rescheduling</label>
                <textarea name="reschedule_reason" required rows="3" placeholder="Enter reason..." class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModal('rescheduleModal')" class="inline-flex items-center justify-center rounded-md border px-4 py-2 text-sm font-medium">Cancel</button>
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground px-4 py-2 text-sm font-medium">Confirm Reschedule</button>
            </div>
        </form>
    </div>
</div>

{{-- Assign Driver Modal --}}
<div id="assignDriverModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeModalOnBackdrop(event, 'assignDriverModal')">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md m-4" onclick="event.stopPropagation()">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold">Assign Driver</h2>
                <p class="text-sm text-muted-foreground mt-1">Select a driver for this delivery</p>
            </div>
            <button onclick="closeModal('assignDriverModal')" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <form id="assignDriverForm" class="p-6 space-y-4">
            <input type="hidden" id="assign_shipment_id" name="shipment_id"/>
            
            <div>
                <label class="block text-sm font-medium mb-2">Available Drivers</label>
                <select name="driver_id" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="">Select driver</option>
                    @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}">{{ $driver->first_name }} {{ $driver->last_name }} - DRV-{{ str_pad($driver->id, 3, '0', STR_PAD_LEFT) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Assignment Notes</label>
                <textarea name="assignment_notes" rows="3" placeholder="Add any special instructions..." class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModal('assignDriverModal')" class="inline-flex items-center justify-center rounded-md border px-4 py-2 text-sm font-medium">Cancel</button>
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground px-4 py-2 text-sm font-medium">Assign Driver</button>
            </div>
        </form>
    </div>
</div>

{{-- Delivery Details Modal --}}
<div id="deliveryDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeModalOnBackdrop(event, 'deliveryDetailsModal')">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto m-4" onclick="event.stopPropagation()">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Delivery Details</h2>
                <p class="text-sm text-muted-foreground mt-1">Complete information about the scheduled delivery</p>
            </div>
            <button onclick="closeModal('deliveryDetailsModal')" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            {{-- Detail Tabs --}}
            <div class="flex gap-4 mb-6 border-b">
                <button type="button" onclick="changeDetailTab('overview')" class="px-4 py-2 font-medium border-b-2 border-primary text-primary detail-tab active" data-tab="overview">Overview</button>
                <button type="button" onclick="changeDetailTab('route')" class="px-4 py-2 font-medium text-muted-foreground detail-tab" data-tab="route">Route</button>
                <button type="button" onclick="changeDetailTab('items')" class="px-4 py-2 font-medium text-muted-foreground detail-tab" data-tab="items">Items</button>
                <button type="button" onclick="changeDetailTab('history')" class="px-4 py-2 font-medium text-muted-foreground detail-tab" data-tab="history">History</button>
            </div>

            {{-- Overview Tab --}}
            <div id="detail-overview" class="detail-tab-content">
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-muted-foreground">Order ID</p>
                            <p class="font-semibold" id="detail-order-id">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Delivery ID</p>
                            <p class="font-semibold" id="detail-delivery-id">-</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-3">Customer Information</h3>
                        <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                            <div class="h-12 w-12 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center" id="detail-customer-avatar"></div>
                            <div>
                                <p class="font-medium" id="detail-customer-name">-</p>
                                <p class="text-sm text-muted-foreground flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                    <span id="detail-customer-email">-</span>
                                </p>
                                <p class="text-sm text-muted-foreground flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    <span id="detail-customer-phone">-</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-3">Delivery Schedule</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                <p class="text-sm text-muted-foreground mb-1">Date</p>
                                <p class="font-semibold" id="detail-date">-</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                <p class="text-sm text-muted-foreground mb-1">Time Slot</p>
                                <p class="font-semibold" id="detail-time-slot">-</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-3">Delivery Status</h3>
                        <div class="flex gap-2" id="detail-status-badges"></div>
                    </div>
                </div>
            </div>

            {{-- Route Tab --}}
            <div id="detail-route" class="detail-tab-content hidden">
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary mt-1">
                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Delivery Address</p>
                                <p class="font-medium" id="detail-route-address">-</p>
                                <p class="text-sm text-muted-foreground mt-1">Distance: <span id="detail-route-distance">-</span> • Est. Time: <span id="detail-route-time">-</span></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-3">Assigned Driver</h3>
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium" id="detail-driver-name">-</p>
                                    <p class="text-sm text-muted-foreground" id="detail-driver-id">-</p>
                                </div>
                                <a href="#" class="text-primary hover:underline text-sm flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    <span id="detail-driver-phone">-</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-900 rounded-lg p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto text-muted-foreground mb-2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <path d="M12 18v-6"></path>
                            <path d="m9 15 3 3 3-3"></path>
                        </svg>
                        <p class="text-muted-foreground">Map view would be displayed here</p>
                    </div>
                </div>
            </div>

            {{-- Items Tab --}}
            <div id="detail-items" class="detail-tab-content hidden">
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h3 class="font-semibold mb-2">Package Details</h3>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted-foreground">
                                <path d="M16.5 9.4 7.55 4.24"></path>
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                <line x1="12" x2="12" y1="22" y2="12"></line>
                            </svg>
                            <div>
                                <p class="font-medium" id="detail-package-info">-</p>
                                <p class="text-sm text-muted-foreground">Total Value: <span id="detail-package-value">-</span></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-3">Special Instructions</h3>
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                            <p class="text-sm" id="detail-special-instructions">No special instructions provided</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- History Tab --}}
            <div id="detail-history" class="detail-tab-content hidden">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="rounded-full bg-green-100 dark:bg-green-900 p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 dark:text-green-400">
                                <path d="M20 6 9 17l-5-5"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">Order Scheduled</p>
                            <p class="text-sm text-muted-foreground" id="history-scheduled">-</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="rounded-full bg-blue-100 dark:bg-blue-900 p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600 dark:text-blue-400">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <line x1="19" x2="19" y1="8" y2="14"></line>
                                <line x1="22" x2="16" y1="11" y2="11"></line>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">Driver Assigned</p>
                            <p class="text-sm text-muted-foreground" id="history-driver-assigned">-</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="rounded-full bg-gray-100 dark:bg-gray-700 p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-600 dark:text-gray-400">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">Pending Pickup</p>
                            <p class="text-sm text-muted-foreground" id="history-pending">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button onclick="closeModal('deliveryDetailsModal')" class="inline-flex items-center justify-center rounded-md border px-4 py-2 text-sm font-medium">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let currentStep = 1;
let currentShipmentId = null;

// ============================================
// MODAL FUNCTIONS
// ============================================

function openScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        currentStep = 1;
        showStep(1);
        // Reset form
        document.getElementById('scheduleDeliveryForm').reset();
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Reset forms when closing
        if (modalId === 'scheduleModal') {
            document.getElementById('scheduleDeliveryForm').reset();
            currentStep = 1;
            showStep(1);
        } else if (modalId === 'rescheduleModal') {
            document.getElementById('rescheduleForm').reset();
        } else if (modalId === 'assignDriverModal') {
            document.getElementById('assignDriverForm').reset();
        }
    }
}

function closeModalOnBackdrop(event, modalId) {
    // Only close if clicking directly on the backdrop
    if (event.target.id === modalId) {
        closeModal(modalId);
    }
}

// ============================================
// STEP NAVIGATION FUNCTIONS
// ============================================

function changeStep(step) {
    if (step >= 1 && step <= 4) {
        currentStep = step;
        showStep(step);
    }
}

function showStep(step) {
    // Hide all steps
    for (let i = 1; i <= 4; i++) {
        const stepElement = document.getElementById('step-' + i);
        const tabElement = document.getElementById('tab-step-' + i);
        
        if (stepElement) {
            stepElement.classList.add('hidden');
        }
        
        if (tabElement) {
            tabElement.classList.remove('border-primary', 'text-primary', 'border-b-2');
            tabElement.classList.add('text-muted-foreground');
        }
    }
    
    // Show current step
    const currentStepElement = document.getElementById('step-' + step);
    const currentTabElement = document.getElementById('tab-step-' + step);
    
    if (currentStepElement) {
        currentStepElement.classList.remove('hidden');
    }
    
    if (currentTabElement) {
        currentTabElement.classList.add('border-primary', 'text-primary', 'border-b-2');
        currentTabElement.classList.remove('text-muted-foreground');
    }
    
    // Update summary on last step
    if (step === 4) {
        updateSummary();
    }
}

function nextStep() {
    if (currentStep < 4) {
        // Validate current step before proceeding
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    }
}

function previousStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

function validateStep(step) {
    const form = document.getElementById('scheduleDeliveryForm');
    let isValid = true;
    
    switch(step) {
        case 1:
            // Check customer selection
            const customerType = document.querySelector('input[name="customer_type"]:checked').value;
            if (customerType === 'existing') {
                const customerId = form.querySelector('[name="customer_id"]').value;
                if (!customerId) {
                    alert('Please select a customer');
                    isValid = false;
                }
            } else {
                const customerName = form.querySelector('[name="pickup_contact_name"]').value;
                const customerEmail = form.querySelector('[name="pickup_contact_email"]').value;
                if (!customerName || !customerEmail) {
                    alert('Please fill in customer name and email');
                    isValid = false;
                }
            }
            break;
            
        case 2:
            // Check delivery details
            const address = form.querySelector('[name="delivery_address"]').value;
            const city = form.querySelector('[name="delivery_city"]').value;
            const state = form.querySelector('[name="delivery_state"]').value;
            const postalCode = form.querySelector('[name="delivery_postal_code"]').value;
            const pickupDate = form.querySelector('[name="pickup_date"]').value;
            const timeSlot = form.querySelector('[name="time_slot"]').value;
            const shipmentType = form.querySelector('[name="shipment_type"]').value;
            const priority = form.querySelector('[name="delivery_priority"]').value;
            
            if (!address || !city || !state || !postalCode || !pickupDate || !timeSlot || !shipmentType || !priority) {
                alert('Please fill in all required delivery details');
                isValid = false;
            }
            break;
            
        case 3:
            // Check package details
            const numberOfItems = form.querySelector('[name="number_of_items"]').value;
            if (!numberOfItems || numberOfItems < 1) {
                alert('Please enter number of items');
                isValid = false;
            }
            break;
    }
    
    return isValid;
}

// ============================================
// SUMMARY UPDATE FUNCTION
// ============================================

function updateSummary() {
    const form = document.getElementById('scheduleDeliveryForm');
    
    // Customer
    const customerSelect = form.querySelector('[name="customer_id"]');
    const customerName = form.querySelector('[name="pickup_contact_name"]');
    const customerType = document.querySelector('input[name="customer_type"]:checked').value;
    
    let customerText = '-';
    if (customerType === 'existing' && customerSelect) {
        customerText = customerSelect.options[customerSelect.selectedIndex]?.text || '-';
    } else if (customerName) {
        customerText = customerName.value || '-';
    }
    document.getElementById('summary-customer').textContent = customerText;
    
    // Address
    const address = form.querySelector('[name="delivery_address"]')?.value || '';
    const city = form.querySelector('[name="delivery_city"]')?.value || '';
    const state = form.querySelector('[name="delivery_state"]')?.value || '';
    document.getElementById('summary-address').textContent = 
        address && city ? `${address}, ${city}, ${state}` : '-';
    
    // Date
    const date = form.querySelector('[name="pickup_date"]')?.value || '-';
    document.getElementById('summary-date').textContent = date;
    
    // Time
    const timeSlot = form.querySelector('[name="time_slot"]');
    document.getElementById('summary-time').textContent = 
        timeSlot?.options[timeSlot.selectedIndex]?.text || '-';
    
    // Package
    const items = form.querySelector('[name="number_of_items"]')?.value || '0';
    const value = form.querySelector('[name="total_value"]')?.value || '0';
    document.getElementById('summary-package').textContent = `${items} item(s), $${value}`;
    
    // Type
    const type = form.querySelector('[name="shipment_type"]');
    document.getElementById('summary-type').textContent = 
        type?.options[type.selectedIndex]?.text || '-';
    
    // Priority
    const priority = form.querySelector('[name="delivery_priority"]');
    document.getElementById('summary-priority').textContent = 
        priority?.options[priority.selectedIndex]?.text || '-';

    // Copy delivery details to pickup fields
    const customerPhone = form.querySelector('[name="pickup_contact_phone"]')?.value || '';
    form.querySelector('#pickup_contact_name_hidden').value = customerText;
    form.querySelector('#pickup_contact_phone_hidden').value = customerPhone;
    form.querySelector('#pickup_address_hidden').value = address;
    form.querySelector('#pickup_city_hidden').value = city;
    form.querySelector('#pickup_state_hidden').value = state;
    form.querySelector('#pickup_postal_code_hidden').value = 
        form.querySelector('[name="delivery_postal_code"]')?.value || '';
    form.querySelector('#delivery_contact_name_hidden').value = customerText;
    form.querySelector('#delivery_contact_phone_hidden').value = customerPhone;
}

// ============================================
// CUSTOMER TYPE TOGGLE
// ============================================

function toggleCustomerType() {
    const existingDiv = document.getElementById('existing-customer');
    const newDiv = document.getElementById('new-customer');
    const customerType = document.querySelector('input[name="customer_type"]:checked').value;
    
    if (customerType === 'existing') {
        existingDiv.classList.remove('hidden');
        newDiv.classList.add('hidden');
        document.querySelector('[name="customer_id"]').required = true;
        document.querySelector('[name="pickup_contact_name"]').required = false;
        document.querySelector('[name="pickup_contact_email"]').required = false;
        document.querySelector('[name="pickup_contact_phone"]').required = false;
    } else {
        existingDiv.classList.add('hidden');
        newDiv.classList.remove('hidden');
        document.querySelector('[name="customer_id"]').required = false;
        document.querySelector('[name="pickup_contact_name"]').required = true;
        document.querySelector('[name="pickup_contact_email"]').required = true;
        document.querySelector('[name="pickup_contact_phone"]').required = true;
    }
}

// ============================================
// ACTION MENU FUNCTIONS
// ============================================

function toggleActionMenu(shipmentId) {
    // Close all other menus first
    document.querySelectorAll('[id^="action-menu-"]').forEach(menu => {
        if (menu.id !== 'action-menu-' + shipmentId) {
            menu.classList.add('hidden');
        }
    });
    
    // Toggle current menu
    const menu = document.getElementById('action-menu-' + shipmentId);
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

// Close action menus when clicking outside
document.addEventListener('click', function(event) {
    // Check if click is outside action menu button
    const isActionButton = event.target.closest('[onclick^="toggleActionMenu"]');
    const isInsideMenu = event.target.closest('[id^="action-menu-"]');
    
    if (!isActionButton && !isInsideMenu) {
        document.querySelectorAll('[id^="action-menu-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// ============================================
// SPECIFIC MODAL FUNCTIONS
// ============================================

function openRescheduleModal(shipmentId) {
    currentShipmentId = shipmentId;
    document.getElementById('reschedule_shipment_id').value = shipmentId;
    const modal = document.getElementById('rescheduleModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function openAssignDriverModal(shipmentId) {
    currentShipmentId = shipmentId;
    document.getElementById('assign_shipment_id').value = shipmentId;
    const modal = document.getElementById('assignDriverModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function viewDeliveryDetails(shipmentId) {
    // Close action menu
    document.querySelectorAll('[id^="action-menu-"]').forEach(menu => {
        menu.classList.add('hidden');
    });
    
    // Fetch shipment details via AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    fetch(`/admin/shipments/${shipmentId}/details`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateDeliveryDetails(data.shipment);
            const modal = document.getElementById('deliveryDetailsModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        } else {
            alert('Failed to load shipment details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading shipment details');
    });
}

function populateDeliveryDetails(shipment) {
    // Populate all shipment details in the modal
    document.getElementById('detail-order-id').textContent = shipment.tracking_number || 'N/A';
    document.getElementById('detail-delivery-id').textContent = 'DEL-' + String(shipment.id).padStart(3, '0');
    
    // Customer info
    document.getElementById('detail-customer-name').textContent = shipment.pickup_contact_name || 'N/A';
    document.getElementById('detail-customer-email').textContent = shipment.pickup_contact_email || 'N/A';
    document.getElementById('detail-customer-phone').textContent = shipment.pickup_contact_phone || 'N/A';
    document.getElementById('detail-customer-avatar').textContent = shipment.customer_initials || 'NA';
    
    // Schedule
    document.getElementById('detail-date').textContent = shipment.pickup_date || 'N/A';
    document.getElementById('detail-time-slot').textContent = shipment.time_slot || 'N/A';
    
    // Status badges
    const statusBadges = document.getElementById('detail-status-badges');
    if (statusBadges) {
        statusBadges.innerHTML = `
            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800">
                ${shipment.status}
            </span>
            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-yellow-100 text-yellow-800">
                ${shipment.priority}
            </span>
            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-purple-100 text-purple-800">
                ${shipment.type}
            </span>
        `;
    }
    
    // Route
    document.getElementById('detail-route-address').textContent = shipment.delivery_address || 'N/A';
    
    // Driver
    document.getElementById('detail-driver-name').textContent = shipment.driver_name || 'Unassigned';
    document.getElementById('detail-driver-id').textContent = shipment.driver_id || '';
    document.getElementById('detail-driver-phone').textContent = shipment.driver_phone || '';
    
    // Package
    document.getElementById('detail-package-info').textContent = shipment.package_info || 'N/A';
    document.getElementById('detail-package-value').textContent = '$' + (shipment.total_value || '0.00');
    document.getElementById('detail-special-instructions').textContent = 
    
    
    // History
    document.getElementById('history-scheduled').textContent = shipment.created_at || 'N/A';
    document.getElementById('history-driver-assigned').textContent = shipment.driver_assigned_at || 'Not assigned yet';
    document.getElementById('history-pending').textContent = shipment.pending_since || 'N/A';
}

function changeDetailTab(tab) {
    // Hide all tabs
    document.querySelectorAll('.detail-tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    document.querySelectorAll('.detail-tab').forEach(btn => {
        btn.classList.remove('border-primary', 'text-primary', 'border-b-2');
        btn.classList.add('text-muted-foreground');
    });
    
    // Show selected tab
    const selectedContent = document.getElementById('detail-' + tab);
    const selectedBtn = document.querySelector(`.detail-tab[data-tab="${tab}"]`);
    
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
    
    if (selectedBtn) {
        selectedBtn.classList.add('border-primary', 'text-primary', 'border-b-2');
        selectedBtn.classList.remove('text-muted-foreground');
    }
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show toast notification
        showNotification('Tracking number copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy:', err);
        showNotification('Failed to copy tracking number', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Simple notification - you can replace with your notification system
    alert(message);
}

function filterByTab(tab) {
    const url = new URL(window.location.href);
    url.searchParams.set('tab', tab);
    window.location.href = url.toString();
}

// ============================================
// FORM SUBMISSIONS
// ============================================

// Schedule Delivery Form
document.addEventListener('DOMContentLoaded', function() {
    const scheduleForm = document.getElementById('scheduleDeliveryForm');
    if (scheduleForm) {
        scheduleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Scheduling...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Delivery scheduled successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to schedule delivery'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }
    
    // Reschedule Form
    const rescheduleForm = document.getElementById('rescheduleForm');
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const shipmentId = document.getElementById('reschedule_shipment_id').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Rescheduling...';
            
            fetch(`/admin/shipments/${shipmentId}/reschedule`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Delivery rescheduled successfully!');
                    closeModal('rescheduleModal');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to reschedule'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }
    
    // Assign Driver Form
    const assignDriverForm = document.getElementById('assignDriverForm');
    if (assignDriverForm) {
        assignDriverForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const shipmentId = document.getElementById('assign_shipment_id').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Assigning...';
            
            fetch(`/admin/shipments/${shipmentId}/assign-driver`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Driver assigned successfully!');
                    closeModal('assignDriverModal');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to assign driver'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }
});

// ESC key to close modals
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Close any open modals
        ['scheduleModal', 'rescheduleModal', 'assignDriverModal', 'deliveryDetailsModal'].forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal && !modal.classList.contains('hidden')) {
                closeModal(modalId);
            }
        });
        
        // Close all action menus
        document.querySelectorAll('[id^="action-menu-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});
</script>


@endsection