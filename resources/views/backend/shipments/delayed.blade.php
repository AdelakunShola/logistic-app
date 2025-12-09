@extends('admin.admin_dashboard')
@section('admin')

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Delayed Shipments</h1>
            <p class="text-muted-foreground">Monitor and manage shipments experiencing delays</p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Refresh Button -->
            <button onclick="location.reload()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw h-4 w-4 mr-2" aria-hidden="true">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M21 3v5h-5"></path>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                    <path d="M8 16H3v5"></path>
                </svg>
                Refresh
            </button>
            
            <!-- Export Dropdown -->
            <div style="position: relative; display: inline-block;">
                <button onclick="toggleDropdown('exportDropdown')" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
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
                <div id="exportDropdown" style="display: none; position: absolute; right: 0; margin-top: 8px; width: 200px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 50;">
                    <a href="{{ route('admin.delayed-shipments.export', 'csv') }}" style="display: flex; align-items: center; width: 100%; padding: 12px 16px; text-align: left; text-decoration: none; color: inherit; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 12px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Export as CSV
                    </a>
                    <a href="{{ route('admin.delayed-shipments.export', 'excel') }}" style="display: flex; align-items: center; width: 100%; padding: 12px 16px; text-align: left; text-decoration: none; color: inherit; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 12px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Export as Excel
                    </a>
                    <hr style="margin: 4px 0; border: none; border-top: 1px solid #e5e7eb;">
                    <button onclick="window.print()" style="display: flex; align-items: center; width: 100%; padding: 12px 16px; text-align: left; border: none; background: none; cursor: pointer; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 12px;">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Total Delayed</h3>
                <div class="p-2 bg-orange-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert h-4 w-4 text-orange-500" aria-hidden="true">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $stats['total_delayed'] }}</div>
                <p class="text-xs text-muted-foreground">Active delays requiring attention</p>
            </div>
        </div>
        
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Critical Delays</h3>
                <div class="p-2 bg-red-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x h-4 w-4 text-red-500" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m15 9-6 6"></path>
                        <path d="m9 9 6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $stats['critical_delays'] }}</div>
                <p class="text-xs text-muted-foreground">Require immediate attention</p>
            </div>
        </div>
        
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Avg Delay Time</h3>
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4 text-blue-500" aria-hidden="true">
                        <path d="M12 6v6l4 2"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ round($stats['avg_delay_hours']) }}h</div>
                <p class="text-xs text-muted-foreground">Average delay duration</p>
            </div>
        </div>
        
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Financial Impact</h3>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign h-4 w-4 text-green-500" aria-hidden="true">
                        <line x1="12" x2="12" y1="2" y2="22"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">${{ number_format($stats['total_value_affected'] / 1000, 0) }}K</div>
                <p class="text-xs text-muted-foreground">Total shipment value affected</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Filter Delayed Shipments</h3>
        </div>
        <div class="p-4 md:p-6 pt-0">
            <form method="GET" action="{{ route('admin.delayed-shipments.index') }}" class="flex flex-col gap-4 md:flex-row md:items-center">
                <div class="flex-1">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" aria-hidden="true">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                        <input name="search" value="{{ request('search') }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8" placeholder="Search by tracking number or customer..."/>
                    </div>
                </div>
                
                <select name="severity" class="flex h-10 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-[180px]">
                    <option value="all" {{ request('severity') == 'all' ? 'selected' : '' }}>All Severities</option>
                    <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
                
                <select name="carrier" class="flex h-10 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-[180px]">
                    <option value="all" {{ request('carrier') == 'all' ? 'selected' : '' }}>All Carriers</option>
                    @foreach($carriers as $carrier)
                        <option value="{{ $carrier->name }}" {{ request('carrier') == $carrier->name ? 'selected' : '' }}>
                            {{ $carrier->name }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 rounded-md px-4">
                    Apply Filters
                </button>
            </form>
        </div>
    </div>

    <!-- Shipments Table -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">
                Delayed Shipments (<span id="shipmentCount">{{ $delays->total() }}</span>)
            </h3>
            <div class="text-sm text-muted-foreground">Shipments currently experiencing delays sorted by severity</div>
        </div>
        <div class="p-4 md:p-6 pt-0">
            @if($delays->isEmpty())
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto mb-4 text-muted-foreground">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                    <h3 class="text-lg font-semibold mb-2">No Delayed Shipments</h3>
                    <p class="text-muted-foreground">All shipments are on schedule!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <div class="relative w-full overflow-auto">
                        <table id="shipmentsTable" class="w-full caption-bottom text-sm whitespace-nowrap">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tracking #</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Customer</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Route</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Carrier</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Delay</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Severity</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Cause</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Value</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @foreach($delays as $delay)
                                <tr class="border-b transition-colors hover:bg-muted/50" data-severity="{{ $delay->severity }}" data-carrier="{{ $delay->shipment->carrier->name ?? 'N/A' }}" data-delay-id="{{ $delay->id }}">
                                    <td class="p-4 align-middle font-medium">{{ $delay->shipment->tracking_number }}</td>
                                    <td class="p-4 align-middle">
                                        <div>
                                            <div class="font-medium">{{ $delay->shipment->customer->first_name }} {{ $delay->shipment->customer->last_name }}</div>
                                            <div class="text-sm text-muted-foreground">{{ $delay->shipment->customer->email }}</div>
                                        </div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <div class="text-sm">
                                            <div>{{ $delay->shipment->pickup_city }}, {{ $delay->shipment->pickup_state }}</div>
                                            <div class="text-muted-foreground">→ {{ $delay->shipment->delivery_city }}, {{ $delay->shipment->delivery_state }}</div>
                                        </div>
                                    </td>
                                    <td class="p-4 align-middle">{{ $delay->shipment->carrier->name ?? 'N/A' }}</td>
                                    <td class="p-4 align-middle">
                                        <div class="text-sm">
                                            <div class="font-medium">{{ $delay->delay_hours }}h</div>
                                            <div class="text-muted-foreground">Due: {{ $delay->new_delivery_date->format('m/d/Y') }}</div>
                                        </div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-primary/80 
                                            @if($delay->severity === 'critical') bg-red-500 text-white
                                            @elseif($delay->severity === 'high') bg-orange-500 text-white
                                            @elseif($delay->severity === 'medium') bg-yellow-500 text-black
                                            @else bg-green-500 text-white
                                            @endif">
                                            {{ strtoupper($delay->severity) }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle">{{ ucfirst(str_replace('_', ' ', $delay->delay_reason)) }}</td>
                                    <td class="p-4 align-middle">
                                        <div class="flex items-center gap-2">
                                            @if($delay->shipment->status === 'in_transit')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-blue-500">
                                                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                                    <path d="M15 18H9"></path>
                                                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                                    <circle cx="17" cy="18" r="2"></circle>
                                                    <circle cx="7" cy="18" r="2"></circle>
                                                </svg>
                                                <span class="text-sm">In Transit</span>
                                            @elseif($delay->shipment->status === 'out_for_delivery')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-green-500">
                                                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                                    <path d="M12 22V12"></path>
                                                    <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                                </svg>
                                                <span class="text-sm">Out for Delivery</span>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-orange-500">
                                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                                                    <path d="M12 9v4"></path>
                                                    <path d="M12 17h.01"></path>
                                                </svg>
                                                <span class="text-sm">{{ ucfirst($delay->shipment->status) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-4 align-middle">${{ number_format($delay->shipment->total_value, 0) }}</td>
                                    <td class="p-4 align-middle">
                                        <div style="position: relative; display: inline-block;">
                                            <button onclick="toggleDropdown('actions{{ $delay->id }}')" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8 p-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="19" cy="12" r="1"></circle>
                                                    <circle cx="5" cy="12" r="1"></circle>
                                                </svg>
                                            </button>
                                            <div id="actions{{ $delay->id }}" style="display: none; position: absolute; right: 0; margin-top: 8px; width: 200px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 50;">
                                                <button onclick="viewDetails({{ $delay->id }})" style="display: flex; align-items: center; width: 100%; padding: 12px 16px; text-align: left; border: none; background: none; cursor: pointer; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 12px;">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    View Details
                                                </button>
                                                <button onclick="startResolution({{ $delay->id }})" style="display: flex; align-items: center; width: 100%; padding: 12px 16px; text-align: left; border: none; background: none; cursor: pointer; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 12px;">
                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                    </svg>
                                                    Start Resolution
                                                </button>
                                                <button onclick="contactCustomer({{ $delay->id }})" style="display: flex; align-items: center; width: 100%; padding: 12px 16px; text-align: left; border: none; background: none; cursor: pointer; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 12px;">
                                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                                    </svg>
                                                    Contact Customer
                                                </button>
                                                <button onclick="resolveDelay({{ $delay->id }})" style="display: flex; align-items: center; width: 100%; padding: 12px 16px; text-align: left; border: none; background: none; cursor: pointer; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 12px;">
                                                        <path d="M20 6 9 17l-5-5"></path>
                                                    </svg>
                                                    Mark as Resolved
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                           </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    @if($delays->hasPages())
                    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Per Page Selector -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-muted-foreground">Show:</label>
                            <select onchange="changePerPage(this.value)" class="flex h-9 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-muted-foreground">
                                Showing {{ $delays->firstItem() }} to {{ $delays->lastItem() }} of {{ $delays->total() }} results
                            </span>
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center gap-2">
                            {{-- Previous Button --}}
                            @if ($delays->onFirstPage())
                                <span class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 w-9 opacity-50 cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $delays->previousPageUrl() }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 w-9">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                </a>
                            @endif

                            {{-- Page Numbers --}}
                            <div class="hidden sm:flex items-center gap-1">
                                @foreach ($delays->getUrlRange(1, $delays->lastPage()) as $page => $url)
                                    @if ($page == $delays->currentPage())
                                        <span class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium h-9 w-9 bg-primary text-primary-foreground">
                                            {{ $page }}
                                        </span>
                                    @elseif ($page == 1 || $page == $delays->lastPage() || abs($page - $delays->currentPage()) < 3)
                                        <a href="{{ $url }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 w-9">
                                            {{ $page }}
                                        </a>
                                    @elseif (abs($page - $delays->currentPage()) == 3)
                                        <span class="inline-flex items-center justify-center h-9 w-9 text-muted-foreground">...</span>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Mobile: Just show current page info --}}
                            <div class="sm:hidden">
                                <span class="text-sm text-muted-foreground">
                                    Page {{ $delays->currentPage() }} of {{ $delays->lastPage() }}
                                </span>
                            </div>

                            {{-- Next Button --}}
                            @if ($delays->hasMorePages())
                                <a href="{{ $delays->nextPageUrl() }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 w-9">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                            @else
                                <span class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 w-9 opacity-50 cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('backend.shipments.details-modal')
@include('backend.shipments.contact-modal')
@include('backend.shipments.resolution-modal')

<script>
let currentDelayId = null;

// Toggle dropdown visibility
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const allDropdowns = document.querySelectorAll('[id^="actions"], [id$="Dropdown"]');
    
    allDropdowns.forEach(dd => {
        if (dd.id !== dropdownId) {
            dd.style.display = 'none';
        }
    });
    
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('button') && !event.target.closest('[id^="actions"]') && !event.target.closest('[id$="Dropdown"]')) {
        const allDropdowns = document.querySelectorAll('[id^="actions"], [id$="Dropdown"]');
        allDropdowns.forEach(dd => dd.style.display = 'none');
    }
});

// View delay details
function viewDetails(delayId) {
    currentDelayId = delayId;
    
    fetch(`/admin/delayed-shipments/${delayId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const delay = data.delay;
                document.getElementById('modalTrackingNumber').textContent = delay.tracking_number;
                document.getElementById('modalCustomer').textContent = delay.customer;
                document.getElementById('modalPriority').textContent = delay.severity;
                document.getElementById('modalOrigin').textContent = delay.origin;
                document.getElementById('modalDestination').textContent = delay.destination;
                document.getElementById('modalCarrier').textContent = delay.carrier;
                document.getElementById('modalValue').textContent = delay.value;
                document.getElementById('modalDelay').textContent = delay.delay;
                document.getElementById('modalSeverity').textContent = delay.severity;
                document.getElementById('modalCause').textContent = delay.cause;
                document.getElementById('modalEmail').textContent = delay.email;
                document.getElementById('modalPhone').textContent = delay.phone;
                
                closeAllDropdowns();
                document.getElementById('detailsModal').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load details');
        });
}

// Start resolution process
function startResolution(delayId) {
    currentDelayId = delayId;
    document.getElementById('resolutionDelayId').value = delayId;
    
    closeAllDropdowns();
    document.getElementById('resolutionModal').style.display = 'block';
}

// Submit resolution
function submitResolution() {
    const delayId = document.getElementById('resolutionDelayId').value;
    const resolutionType = document.getElementById('resolutionType').value;
    const priority = document.getElementById('resolutionPriority').value;
    const notes = document.getElementById('resolutionNotes').value;
    
    if (!resolutionType || !priority) {
        alert('Please fill in all required fields');
        return;
    }
    
    fetch(`/admin/delayed-shipments/${delayId}/start-resolution`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            resolution_type: resolutionType,
            priority: priority,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeModal('resolutionModal');
            location.reload();
        } else {
            alert(data.message || 'Failed to start resolution');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to start resolution process');
    });
}

// Contact customer
function contactCustomer(delayId) {
    currentDelayId = delayId;
    document.getElementById('contactDelayId').value = delayId;
    
    fetch(`/admin/delayed-shipments/${delayId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const delay = data.delay;
                document.getElementById('contactCustomerName').textContent = delay.customer;
                document.getElementById('contactTrackingNumber').textContent = delay.tracking_number;
                document.getElementById('contactModalCustomer').textContent = delay.customer;
                document.getElementById('contactModalEmail').textContent = delay.email;
                document.getElementById('contactModalTracking').textContent = delay.tracking_number;
                document.getElementById('contactModalPhone').textContent = delay.phone;
                document.getElementById('contactSubject').value = 'Shipment Delay Update - ' + delay.tracking_number;
                
                const message = `Dear ${delay.customer},

We wanted to update you on the status of your shipment ${delay.tracking_number}.

Your shipment is currently experiencing a delay due to ${delay.cause.toLowerCase()}. We sincerely apologize for any inconvenience this may cause.

We are working diligently to resolve this issue and will keep you updated on the progress.

If you have any questions or concerns, please don't hesitate to reach out.

Best regards,
Shipment Support Team`;
                
                document.getElementById('contactMessage').value = message;
                
                closeAllDropdowns();
                document.getElementById('contactModal').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load customer details');
        });
}

// Send message to customer
function sendMessage() {
    const delayId = document.getElementById('contactDelayId').value;
    const method = document.getElementById('contactMethod').value;
    const subject = document.getElementById('contactSubject').value;
    const message = document.getElementById('contactMessage').value;
    
    if (!method || !subject || !message) {
        alert('Please fill in all fields');
        return;
    }
    
    fetch(`/admin/delayed-shipments/${delayId}/contact-customer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            contact_method: method,
            subject: subject,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeModal('contactModal');
            location.reload();
        } else {
            alert(data.message || 'Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send message');
    });
}

// Resolve delay
function resolveDelay(delayId) {
    if (!confirm('Are you sure you want to mark this delay as resolved?')) {
        return;
    }
    
    fetch(`/admin/delayed-shipments/${delayId}/resolve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            notes: 'Delay resolved manually by admin'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to resolve delay');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to resolve delay');
    });
}

// Close modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    
    // Reset forms
    if (modalId === 'contactModal') {
        document.getElementById('contactMethod').value = '';
        document.getElementById('contactMessage').value = '';
    } else if (modalId === 'resolutionModal') {
        document.getElementById('resolutionType').value = '';
        document.getElementById('resolutionPriority').value = '';
        document.getElementById('resolutionNotes').value = '';
    }
}

// Close all dropdowns
function closeAllDropdowns() {
    const allDropdowns = document.querySelectorAll('[id^="actions"]');
    allDropdowns.forEach(dd => dd.style.display = 'none');
}

// Start resolution from details modal
function startResolutionFromModal() {
    closeModal('detailsModal');
    startResolution(currentDelayId);
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal('detailsModal');
        closeModal('contactModal');
        closeModal('resolutionModal');
    }
});



// Change per page
function changePerPage(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.set('page', 1); // Reset to first page
    window.location.href = url.toString();
}
</script>

@endsection