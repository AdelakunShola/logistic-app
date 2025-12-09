@extends('admin.admin_dashboard')
@section('admin') 


<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div id="fleet-status-container">
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-wrap gap-3 items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Fleet Status</h1>
                <p class="text-muted-foreground">Monitor and manage your entire fleet with real-time status updates and analytics</p>
            </div>
            <div class="flex gap-2">
                <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3" 
                        type="button" 
                        onclick="openAddVehicleModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-2">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Add Vehicle
                </button>
                <a href="{{ route('admin.shipments.create') }}"
   class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
         class="lucide lucide-plus h-4 w-4 mr-2">
        <path d="M5 12h14"></path>
        <path d="M12 5v14"></path>
    </svg>
    New Shipment
</a>

            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-3">
            {{-- Total Vehicles Card --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-4 md:p-6 pt-6">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-muted-foreground">Total Vehicles</p>
                            <p class="text-2xl font-bold" id="stat-total-vehicles">0</p>
                        </div>
                        <div class="p-2 bg-primary/10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-5 w-5 text-primary">
                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                <path d="M15 18H9"></path>
                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                <circle cx="17" cy="18" r="2"></circle>
                                <circle cx="7" cy="18" r="2"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2" id="vehicle-breakdown">
                        {{-- Dynamic content loaded via JS --}}
                    </div>
                </div>
            </div>

            {{-- Fleet Efficiency Card --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-4 md:p-6 pt-6">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-muted-foreground">Fleet Efficiency</p>
                            <p class="text-2xl font-bold" id="stat-fleet-efficiency">0%</p>
                        </div>
                        <div class="p-2 bg-green-500/10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big h-5 w-5 text-green-500">
                                <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div aria-valuemax="100" aria-valuemin="0" role="progressbar" class="relative w-full overflow-hidden rounded-full bg-secondary h-2">
                            <div class="h-full w-full flex-1 bg-primary transition-all" id="progress-fleet-efficiency" style="transform:translateX(-13%)"></div>
                        </div>
                        <p class="mt-2 text-sm text-muted-foreground">Overall fleet performance based on uptime, fuel efficiency, and maintenance compliance</p>
                    </div>
                </div>
            </div>

            {{-- Fuel Efficiency Card 
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-4 md:p-6 pt-6">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-muted-foreground">Fuel Efficiency</p>
                            <p class="text-2xl font-bold" id="stat-fuel-efficiency">0%</p>
                        </div>
                        <div class="p-2 bg-blue-500/10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-5 w-5 text-blue-500">
                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                <path d="M15 18H9"></path>
                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                <circle cx="17" cy="18" r="2"></circle>
                                <circle cx="7" cy="18" r="2"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div aria-valuemax="100" aria-valuemin="0" role="progressbar" class="relative w-full overflow-hidden rounded-full bg-secondary h-2">
                            <div class="h-full w-full flex-1 bg-primary transition-all" id="progress-fuel-efficiency" style="transform:translateX(-8%)"></div>
                        </div>
                        <p class="mt-2 text-sm text-muted-foreground">Average fuel efficiency across all vehicles compared to industry standards</p>
                    </div>
                </div>
            </div>--}}

            {{-- Maintenance Compliance Card --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-4 md:p-6 pt-6">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-muted-foreground">Maintenance Compliance</p>
                            <p class="text-2xl font-bold" id="stat-maintenance-compliance">0%</p>
                        </div>
                        <div class="p-2 bg-yellow-500/10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench h-5 w-5 text-yellow-500">
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div aria-valuemax="100" aria-valuemin="0" role="progressbar" class="relative w-full overflow-hidden rounded-full bg-secondary h-2">
                            <div class="h-full w-full flex-1 bg-primary transition-all" id="progress-maintenance-compliance" style="transform:translateX(-5%)"></div>
                        </div>
                        <p class="mt-2 text-sm text-muted-foreground">Percentage of vehicles with up-to-date maintenance schedules</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filters Section --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="md:p-6 p-4">
                <div class="flex flex-col xl:flex-row gap-4">
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                        <input type="search" 
                               class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8" 
                               placeholder="Search by vehicle ID, model, or driver..." 
                               id="search-input"
                               value=""/>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 md:w-auto">
                        <!-- Vehicle Type Dropdown -->
						<select name="vehicle_type" id="vehicle_type" class="px-3 py-2 border rounded-md form-control">
							<option value="">All Vehicle Types</option>
							@foreach($vehicleTypes as $type)
								<option value="{{ $type }}">{{ ucfirst($type) }}</option>
							@endforeach
						</select>

						<!-- Status Dropdown -->
						<select name="status" id="status" class="px-3 py-2 border rounded-md form-control">
							<option value="">All Statuses</option>
							@foreach($statuses as $status)
								<option value="{{ $status }}">{{ ucwords(str_replace('_', ' ', $status)) }}</option>
							@endforeach
						</select>
                        <select class="px-3 py-2 border rounded-md" id="filter-location">
                            <option value="">Location</option>
                            {{-- Will be populated dynamically --}}
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-10 w-10 shrink-0 bg-transparent" 
                                type="button"
                                onclick="applyFilters()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-funnel h-4 w-4">
                                <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"></path>
                            </svg>
                            <span class="sr-only">More filters</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fleet Location Map --}}
       <!-- <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                <div>
                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-5 w-5">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Fleet Location Map
                    </h3>
                    <div class="text-sm text-muted-foreground">Real-time location of all vehicles</div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10"
                            onclick="refreshMapLocations()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw h-4 w-4">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                            <path d="M21 3v5h-5"></path>
                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                            <path d="M8 16H3v5"></path>
                        </svg>
                        <span class="sr-only">Refresh</span>
                    </button>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="relative rounded-md overflow-hidden border h-[400px] bg-[#f8f9fa] dark:bg-[#111827]" id="fleet-map-container">
                    {{-- Map background --}}
                    <svg width="100%" height="100%" viewBox="0 0 1000 500" preserveAspectRatio="xMidYMid meet" class="opacity-20 dark:opacity-10">
                        <path d="M215,220 L240,220 L260,200 L280,210 L300,190 L330,190 L350,170 L380,170 L400,150 L430,150 L450,130 L480,130 L500,110 L530,110 L550,130 L580,130 L600,150 L630,150 L650,170 L680,170 L700,190 L730,190 L750,210 L780,210 L800,230 L830,230 L850,250 L880,250 L900,270 L930,270 L950,290 L980,290" fill="none" stroke="#ced4da" stroke-width="2"></path>
                        <path d="M215,290 L980,290" fill="none" stroke="#ced4da" stroke-width="1" stroke-dasharray="5,5"></path>
                        <path d="M215,350 L980,350" fill="none" stroke="#ced4da" stroke-width="1" stroke-dasharray="5,5"></path>
                    </svg>
                    
                    {{-- Vehicle markers will be inserted here dynamically --}}
                    <div id="vehicle-markers"></div>
                    
                    {{-- Map Legend --}}
                    <div class="absolute bottom-4 right-4 bg-background border rounded-md p-2 shadow-sm">
                        <div class="text-xs font-medium mb-1">Vehicle Status</div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                            <div class="flex items-center gap-1">
                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                <span class="text-xs">Active</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="h-3 w-3 rounded-full bg-yellow-500"></div>
                                <span class="text-xs">Maintenance</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                                <span class="text-xs">Available</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="h-3 w-3 rounded-full bg-red-500"></div>
                                <span class="text-xs">Out of Service</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->

        {{-- Vehicle Status Table --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Vehicle Status</h3>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div dir="ltr" data-orientation="horizontal" class="overflow-x-auto !static">
                    <div role="tablist" aria-orientation="horizontal" class="items-center rounded-md bg-muted p-1 text-muted-foreground flex flex-wrap gap-2 justify-start h-full w-full sm:w-max" tabindex="-1">
                        <button type="button" role="tab" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" 
                                data-tab="all" 
                                data-state="active"
                                onclick="switchTab('all', this)">
                            All Vehicles
                        </button>
                        <button type="button" role="tab" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" 
                                data-tab="active"
                                data-state="inactive"
                                onclick="switchTab('active', this)">
                            Active
                        </button>
                        <button type="button" role="tab" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" 
                                data-tab="maintenance"
                                data-state="inactive"
                                onclick="switchTab('maintenance', this)">
                            Maintenance
                        </button>
                        <button type="button" role="tab" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" 
                                data-tab="inactive"
                                data-state="inactive"
                                onclick="switchTab('inactive', this)">
                            Available
                        </button>
                    </div>
                    
                    <div class="rounded-md border overflow-x-auto mt-4 p-5">
                        <div class="relative w-full overflow-auto">
                            <table class="caption-bottom text-sm w-full whitespace-nowrap" id="vehicles-table">
                                <thead class="[&_tr]:border-b">
                                    <tr class="border-b transition-colors hover:bg-muted/50">
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground w-[100px]">ID</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Type/Model</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Driver</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Location</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Maintenance</th>
                                        <!--<th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Fuel</th>--->
                                    </tr>
                                </thead>
                                <tbody class="[&_tr:last-child]:border-0" id="vehicles-tbody">
                                    {{-- Dynamic content loaded via JS --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fleet Performance and Maintenance Schedule Grid --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            {{-- Fleet Performance Section --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Fleet Performance</h3>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div dir="ltr" data-orientation="horizontal">
                        <div role="tablist" aria-orientation="horizontal" class="items-center rounded-md bg-muted p-1 text-muted-foreground mb-4 flex flex-wrap gap-1 justify-start">
                           
                            <button type="button" role="tab" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" 
                                    data-state="active"
                                    data-performance-tab="maintenance"
                                    onclick="switchPerformanceTab('maintenance', this)">
                                Maintenance Cost
                            </button>
                             <!--<button type="button" role="tab" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" 
                                    data-state="inactive"
                                    data-performance-tab="fuel"
                                    onclick="switchPerformanceTab('fuel', this)">
                                Fuel Efficiency
                            </button>-->
                        </div>
                        
                        {{-- Fuel Efficiency Tab Content 
                        <div id="performance-fuel-content" style="display:block;">
                            <div class="w-full h-[300px]">
                                <canvas id="fuel-efficiency-chart"></canvas>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <div class="rounded-lg border p-3">
                                    <div class="text-sm font-medium">Current Average</div>
                                    <div class="mt-1 flex flex-wrap items-end justify-between">
                                        <div class="text-2xl font-bold" id="projected-savings">$0</div>
                                        <div class="text-sm text-muted-foreground">Annual</div>
                                    </div>
                                </div>
                            </div>
                        </div>--}}
                        
                        {{-- Maintenance Cost Tab Content --}}
                        <div id="performance-maintenance-content" style="display:none;">
                            <div class="w-full h-[300px]">
                                <canvas id="maintenance-cost-chart"></canvas>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <div class="rounded-lg border p-3">
                                    <div class="text-sm font-medium">Preventive vs. Repair</div>
                                    <div class="mt-1 flex flex-wrap items-end justify-between">
                                        <div class="text-2xl font-bold" id="preventive-vs-repair">75% / 25%</div>
                                        <div class="text-sm text-green-600" id="preventive-change">+12% YTD</div>
                                    </div>
                                </div>
                              {{--   <div class="rounded-lg border p-3">
                                    <div class="text-sm font-medium">Cost per Mile</div>
                                    <div class="mt-1 flex flex-wrap items-end justify-between">
                                        <div class="text-2xl font-bold" id="cost-per-mile">$0.00</div>
                                        <div class="text-sm text-green-600" id="cost-per-mile-change">-8% YTD</div>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Maintenance Schedule Section --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex flex-wrap gap-2 items-center justify-between">
                        <span>Maintenance Schedule</span>
                        <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3" 
                                type="button" 
                                onclick="openScheduleMaintenanceModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-4 w-4 mr-2">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg>
                            Schedule
                        </button>
                    </h3>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="grid grid-cols-1 2xl:grid-cols-2 gap-4">
                        <div class="w-full">
                            <div class="rdp-root p-3 w-full border rounded-md" id="maintenance-calendar">
                                {{-- Calendar will be rendered here --}}
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h3 class="text-sm font-medium" id="selected-date-display">Select a date</h3>
                            <div id="maintenance-schedule-content">
                                <div class="flex flex-col items-center justify-center h-40 text-center border rounded-md p-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-10 w-10 text-muted-foreground mb-2 opacity-20">
                                        <path d="M8 2v4"></path>
                                        <path d="M16 2v4"></path>
                                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                        <path d="M3 10h18"></path>
                                    </svg>
                                    <p class="text-sm text-muted-foreground">No maintenance scheduled for this date</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





	{{-- ============================================================================ --}}
{{-- MODALS SECTION --}}
{{-- ============================================================================ --}}

{{-- Add Vehicle Modal --}}



<div id="add-vehicle-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Add New Vehicle</h2>
                <button onclick="closeAddVehicleModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        <form id="add-vehicle-form" onsubmit="handleAddVehicle(event)">
			
            @csrf
            <div class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Vehicle Number *</label>
                            <input type="text" name="vehicle_number" required placeholder="e.g., TRK-004" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Vehicle Name</label>
                            <input type="text" name="vehicle_name" placeholder="e.g., Delivery Truck 4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Type *</label>
                            <select name="vehicle_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                
							<option value="">All Vehicle Types</option>
							@foreach($vehicleTypes as $type)
								<option value="{{ $type }}">{{ ucfirst($type) }}</option>
							@endforeach
						</select>

                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Status *</label>
                            <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"> 
							<option value="">All Statuses</option>
							@foreach($statuses as $status)
								<option value="{{ $status }}">{{ ucwords(str_replace('_', ' ', $status)) }}</option>
							@endforeach
						</select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Make</label>
                            <input type="text" name="make" placeholder="e.g., Freightliner" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Model</label>
                            <input type="text" name="model" placeholder="e.g., Cascadia" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Year</label>
                            <input type="number" name="year" placeholder="e.g., 2023" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">License Plate</label>
                            <input type="text" name="license_plate" placeholder="e.g., TRK-004-NY" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Assigned Driver</label>
                            <select name="assigned_driver_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select driver</option>
                                @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->first_name }} {{ $driver->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Location</label>
                            <select name="warehouse_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Location</option>
                                @foreach($warehouse as $ware)
                                <option value="{{ $ware->id }}">{{ $ware->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">VIN</label>
                        <input type="text" name="vin" maxlength="17" placeholder="Vehicle Identification Number (17 characters)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Notes</label>
                        <textarea name="notes" rows="4" placeholder="Additional notes about the vehicle..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" id="cancelAddBtn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Vehicle</button>
            </div>
        </form>
		</div>
    </div>
</div>


{{-- New Shipment Modal --}}
<div id="new-shipment-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Create New Shipment</h2>
                <button onclick="closeNewShipmentModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-muted-foreground mb-6">Create a new shipment and assign it to a vehicle.</p>
            
            <form id="new-shipment-form" onsubmit="handleNewShipment(event)">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Vehicle</label>
                        <select name="vehicle_id" class="w-full px-3 py-2 border rounded-md" required>
                            <option value="">Select vehicle</option>
                            {{-- Will be populated dynamically --}}
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Pickup Location</label>
                            <input type="text" name="pickup_location" class="w-full px-3 py-2 border rounded-md" placeholder="Enter pickup address" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Delivery Location</label>
                            <input type="text" name="delivery_location" class="w-full px-3 py-2 border rounded-md" placeholder="Enter delivery address" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Pickup Date</label>
                            <input type="date" name="pickup_date" class="w-full px-3 py-2 border rounded-md" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Delivery Date</label>
                            <input type="date" name="delivery_date" class="w-full px-3 py-2 border rounded-md" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Priority</label>
                        <select name="priority" class="w-full px-3 py-2 border rounded-md" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Description</label>
                        <textarea name="description" class="w-full px-3 py-2 border rounded-md" rows="3" placeholder="Shipment details..."></textarea>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6 justify-end">
                    <button type="button" onclick="closeNewShipmentModal()" class="px-4 py-2 border rounded-md hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">
                        Create Shipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Schedule Maintenance Modal --}}
<div id="schedule-maintenance-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Schedule New Maintenance</h2>
                <button onclick="closeScheduleMaintenanceModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-muted-foreground mb-6">Create a new maintenance record for a vehicle. Fill in all the required information.</p>
            
            <form id="schedule-maintenance-form" onsubmit="handleScheduleMaintenance(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Vehicle</label>
                        <select name="vehicle_id" class="w-full px-3 py-2 border rounded-md" required>
                            <option value="">Select vehicle</option>
                            {{-- Will be populated dynamically --}}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Service Type</label>
                        <select name="maintenance_type" class="w-full px-3 py-2 border rounded-md" required>
                            <option value="">Select type</option>
                            <option value="scheduled">Scheduled Service</option>
                            <option value="inspection">Inspection</option>
                            <option value="repair">Repair</option>
                            <option value="breakdown">Breakdown</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Category</label>
                        <select name="category" class="w-full px-3 py-2 border rounded-md" required>
                            <option value="">Select category</option>
                            <option value="Engine">Engine</option>
                            <option value="Transmission">Transmission</option>
                            <option value="Brakes">Brakes</option>
                            <option value="Electrical">Electrical</option>
                            <option value="Tires">Tires</option>
                            <option value="General">General</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Priority</label>
                        <select name="priority" class="w-full px-3 py-2 border rounded-md" required>
                            <option value="">Select priority</option>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                           <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Description</label>
                        <textarea name="description" class="w-full px-3 py-2 border rounded-md" rows="3" placeholder="Describe the maintenance work needed..." required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Service Provider</label>
                        <input type="text" name="vendor_name" class="w-full px-3 py-2 border rounded-md" placeholder="e.g., AAA Auto Service">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Scheduled Date</label>
                        <input type="date" name="maintenance_date" class="w-full px-3 py-2 border rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Technician</label>
                        <input type="text" name="technician_name" class="w-full px-3 py-2 border rounded-md" placeholder="Technician name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Estimated Cost ($)</label>
                        <input type="number" name="cost" class="w-full px-3 py-2 border rounded-md" placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Additional Notes</label>
                        <textarea name="notes" class="w-full px-3 py-2 border rounded-md" rows="2" placeholder="Any additional information..."></textarea>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6 justify-end">
                    <button type="button" onclick="closeScheduleMaintenanceModal()" class="px-4 py-2 border rounded-md hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">
                        Schedule Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Advanced Filters Dropdown --}}
<div id="advanced-filters-dropdown" class="absolute top-full right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border z-50 hidden">
    <div class="p-4">
        <h3 class="font-semibold mb-4">Advanced Filters</h3>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2">Fuel Level Range</label>
                <div class="flex items-center gap-2">
                    <input type="number" id="fuel-min" class="w-20 px-2 py-1 border rounded text-sm" placeholder="Min" min="0" max="100">
                    <span class="text-sm">to</span>
                    <input type="number" id="fuel-max" class="w-20 px-2 py-1 border rounded text-sm" placeholder="Max" min="0" max="100">
                    <span class="text-sm">%</span>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2">Mileage Range</label>
                <div class="flex items-center gap-2">
                    <input type="number" id="mileage-min" class="w-24 px-2 py-1 border rounded text-sm" placeholder="Min">
                    <span class="text-sm">to</span>
                    <input type="number" id="mileage-max" class="w-24 px-2 py-1 border rounded text-sm" placeholder="Max">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2">Maintenance Due Date</label>
                <select id="maintenance-due" class="w-full px-3 py-2 border rounded-md text-sm">
                    <option value="">Any time</option>
                    <option value="overdue">Overdue</option>
                    <option value="this-week">This week</option>
                    <option value="this-month">This month</option>
                    <option value="next-month">Next month</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2">Driver Assignment</label>
                <select id="driver-assignment" class="w-full px-3 py-2 border rounded-md text-sm">
                    <option value="">All vehicles</option>
                    <option value="assigned">Assigned</option>
                    <option value="unassigned">Unassigned</option>
                </select>
            </div>
            
            <div class="flex gap-2 pt-2">
                <button onclick="applyAdvancedFilters()" class="flex-1 px-3 py-2 bg-primary text-white rounded-md text-sm hover:bg-primary/90">
                    Apply Filters
                </button>
                <button onclick="clearAdvancedFilters()" class="px-3 py-2 border rounded-md text-sm hover:bg-gray-100">
                    Clear
                </button>
            </div>
            
            <div class="border-t pt-3 mt-3">
                <button onclick="saveFilterPreset()" class="w-full px-3 py-2 border rounded-md text-sm hover:bg-gray-100 mb-2">
                    Save Filter Preset
                </button>
                <button onclick="loadFilterPreset()" class="w-full px-3 py-2 border rounded-md text-sm hover:bg-gray-100">
                    Load Filter Preset
                </button>
            </div>
        </div>
    </div>
</div>

</div>



<div id="loading-indicator" style="display:none;" class="fixed top-4 left-1/2 -translate-x-1/2 z-50">
    <div class="bg-primary text-primary-foreground px-4 py-2 rounded-md shadow-lg">
        <svg class="animate-spin h-5 w-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Loading...
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>


<script>



// ============================================================================
// FLEET STATUS DASHBOARD - COMPLETE WORKING VERSION
// ============================================================================

// Global Variables
let allVehiclesData = [];
let currentTab = 'all';
let fuelEfficiencyChart = null;
let maintenanceCostChart = null;
let currentCalendarDate = new Date();

console.log('%c🚀 FLEET DASHBOARD INITIALIZING', 'background: #222; color: #bada55; font-size: 20px');

// ============================================================================
// INITIALIZATION
// ============================================================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM LOADED - STARTING INITIALIZATION ===');
    
    try {
        // Verify critical elements exist
        const criticalElements = ['vehicles-tbody', 'stat-total-vehicles', 'search-input'];
        
        let missingElements = [];
        criticalElements.forEach(id => {
            const el = document.getElementById(id);
            if (!el) {
                console.error(`❌ Missing element: ${id}`);
                missingElements.push(id);
            } else {
                console.log(`✅ Found element: ${id}`);
            }
        });
        
        if (missingElements.length > 0) {
            console.error('CRITICAL: Missing elements:', missingElements);
            alert('Dashboard initialization failed. Missing elements: ' + missingElements.join(', '));
            return;
        }
        
        // Load dashboard data
        loadDashboardData();
        
        // Set up real-time updates (every 30 seconds)
        //setInterval(loadDashboardData, 30000);
        
        // Set up search with debounce
        let searchTimeout;
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    console.log('Search triggered:', e.target.value);
                    filterVehicles();
                }, 300);
            });
        }
        
        console.log('✅ Fleet Dashboard initialized successfully');
        
    } catch (error) {
        console.error('❌ INITIALIZATION ERROR:', error);
        alert('Failed to initialize dashboard: ' + error.message);
    }
});

// ============================================================================
// MAIN DATA LOADING FUNCTION
// ============================================================================
async function loadDashboardData() {
    console.log('%c=== LOADING DASHBOARD DATA ===', 'background: #0066cc; color: white; padding: 5px');
    
    try {
        showLoadingIndicator();
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const dashboardDataUrl = '/admin/fleet/dashboard-data';
        
        console.log('📡 Fetching from:', dashboardDataUrl);
        console.log('🔑 CSRF Token:', csrfToken ? 'Present' : 'MISSING');
        
        const response = await fetch(dashboardDataUrl, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        });
        
        console.log('📥 Response status:', response.status, response.statusText);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Response error:', errorText);
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        console.log('%c✅ DATA RECEIVED SUCCESSFULLY', 'background: #00aa00; color: white; padding: 5px');
        console.log('📊 Stats:', data.stats);
        console.log('🚗 Vehicles:', data.vehicles?.length || 0);
        console.log('📍 Locations:', data.locations?.length || 0);
        
        // Verify data structure
        if (!data.vehicles || !Array.isArray(data.vehicles)) {
            console.error('❌ Invalid data structure - vehicles is not an array');
            throw new Error('Invalid data structure received');
        }
        
        // Store vehicles data FIRST
        allVehiclesData = data.vehicles;
        console.log('💾 Stored', allVehiclesData.length, 'vehicles in global array');
        
        // Update all dashboard components
        updateStatistics(data.stats);
        renderVehiclesTable(allVehiclesData);
        renderVehicleMarkers(data.locations || []);
        renderPerformanceCharts(data.performance || {});
        renderMaintenanceSchedule(data.maintenance_schedule || {});
        populateLocationFilter(allVehiclesData);
        
        console.log('%c=== DASHBOARD LOADED SUCCESSFULLY ===', 'background: #00aa00; color: white; padding: 5px');
        
    } catch (error) {
        console.error('%c❌ ERROR LOADING DASHBOARD', 'background: #ff0000; color: white; padding: 5px');
        console.error('Error details:', error);
        handleFetchError(error);
    } finally {
        hideLoadingIndicator();
    }
}

// ============================================================================
// STATISTICS UPDATES
// ============================================================================
function updateStatistics(stats) {
    if (!stats) {
        console.warn('⚠️ No stats data received');
        return;
    }
    
    console.log('📊 Updating statistics...');
    
    // Total vehicles
    updateElement('stat-total-vehicles', stats.total_vehicles || 0);
    
    // Vehicle breakdown
    const breakdownElement = document.getElementById('vehicle-breakdown');
    if (breakdownElement) {
        const total = stats.total_vehicles || 1;
        breakdownElement.innerHTML = `
            <div class="flex items-center justify-between text-sm">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-500 mr-1">
                        <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                        <path d="m9 11 3 3L22 4"></path>
                    </svg>
                    Active
                </span>
                <span class="font-medium">${stats.active || 0}</span>
            </div>
            <div role="progressbar" class="relative w-full overflow-hidden rounded-full h-1 bg-muted">
                <div class="h-full w-full flex-1 bg-primary transition-all" style="transform:translateX(-${100 - ((stats.active || 0) / total * 100)}%)"></div>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-500 mr-1">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                    Maintenance
                </span>
                <span class="font-medium">${stats.maintenance || 0}</span>
            </div>
            <div role="progressbar" class="relative w-full overflow-hidden rounded-full h-1 bg-muted">
                <div class="h-full w-full flex-1 bg-primary transition-all" style="transform:translateX(-${100 - ((stats.maintenance || 0) / total * 100)}%)"></div>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-500 mr-1">
                        <path d="M12 6v6l4 2"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    Available
                </span>
                <span class="font-medium">${stats.available || 0}</span>
            </div>
            <div role="progressbar" class="relative w-full overflow-hidden rounded-full h-1 bg-muted">
                <div class="h-full w-full flex-1 bg-primary transition-all" style="transform:translateX(-${100 - ((stats.available || 0) / total * 100)}%)"></div>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-500 mr-1">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m4.9 4.9 14.2 14.2"></path>
                    </svg>
                    Out of Service
                </span>
                <span class="font-medium">${stats.out_of_service || 0}</span>
            </div>
            <div role="progressbar" class="relative w-full overflow-hidden rounded-full h-1 bg-muted">
                <div class="h-full w-full flex-1 bg-primary transition-all" style="transform:translateX(-${100 - ((stats.out_of_service || 0) / total * 100)}%)"></div>
            </div>
        `;
        console.log('✅ Vehicle breakdown updated');
    }
    
    // Fleet efficiency
    updateElement('stat-fleet-efficiency', (stats.fleet_efficiency || 0) + '%');
    updateProgress('progress-fleet-efficiency', stats.fleet_efficiency || 0);
    
    // Fuel efficiency  
    updateElement('stat-fuel-efficiency', (stats.fuel_efficiency || 0) + '%');
    updateProgress('progress-fuel-efficiency', stats.fuel_efficiency || 0);
    
    // Maintenance compliance
    updateElement('stat-maintenance-compliance', (stats.maintenance_compliance || 0) + '%');
    updateProgress('progress-maintenance-compliance', stats.maintenance_compliance || 0);
    
    console.log('✅ Statistics updated successfully');
}

function updateElement(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value;
    } else {
        console.warn(`⚠️ Element not found: ${id}`);
    }
}

function updateProgress(id, percentage) {
    const element = document.getElementById(id);
    if (element) {
        element.style.transform = `translateX(-${100 - percentage}%)`;
    } else {
        console.warn(`⚠️ Progress element not found: ${id}`);
    }
}

// ============================================================================
// VEHICLE TABLE RENDERING
// ============================================================================
function renderVehiclesTable(vehicles) {
    console.log('%c🚗 RENDERING TABLE', 'background: #0066cc; color: white; padding: 3px');
    console.log('Total vehicles to render:', vehicles.length);
    console.log('Current tab:', currentTab);
    
    const tbody = document.getElementById('vehicles-tbody');
    if (!tbody) {
        console.error('❌ vehicles-tbody element not found!');
        return;
    }
    
    // Filter by current tab
    let filteredVehicles = vehicles;
    if (currentTab !== 'all') {
        filteredVehicles = vehicles.filter(v => {
            return v.status === currentTab;
        });
    }
    
    console.log('Filtered vehicles:', filteredVehicles.length);
    
    if (filteredVehicles.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center p-4 text-muted-foreground">No vehicles found for this filter</td></tr>';
        console.log('⚠️ No vehicles to display');
        return;
    }
    
    const rows = filteredVehicles.map(vehicle => {
        const vehicleTypeIcon = getVehicleTypeIcon(vehicle.vehicle_type);
        const fuelLevel = parseFloat(vehicle.fuel) || 0;
        const fuelColor = fuelLevel >= 70 ? 'bg-green-500' : fuelLevel >= 40 ? 'bg-yellow-500' : 'bg-red-500';
        
        const statusColors = {
            'active': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'maintenance': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'inactive': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'repair': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
        };
        
        const statusIcons = {
            'active': '<svg class="h-3 w-3 text-green-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
            'maintenance': '<svg class="h-3 w-3 text-yellow-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            'inactive': '<svg class="h-3 w-3 text-blue-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'repair': '<svg class="h-3 w-3 text-red-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>'
        };
        
        return `
            <tr class="border-b transition-colors hover:bg-muted/50">
                <td class="p-4 align-middle font-medium">${vehicle.vehicle_id}</td>
                <td class="p-4 align-middle">
                    <div class="flex items-center gap-2">
                        ${vehicleTypeIcon}
                        <span>${vehicle.type_model}</span>
                    </div>
                </td>
                <td class="p-4 align-middle">
                    <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold inline-flex items-center gap-1 ${statusColors[vehicle.status] || statusColors['inactive']}">
                        ${statusIcons[vehicle.status] || statusIcons['inactive']}
                        <span>${capitalizeFirst(vehicle.status)}</span>
                    </span>
                </td>
                <td class="p-4 align-middle">${vehicle.driver}</td>
                <td class="p-4 align-middle">${vehicle.location}</td>
                <td class="p-4 align-middle">
                    <div class="flex flex-col">
                        ${vehicle.maintenance?.next ? `<span class="text-xs text-muted-foreground">Next: ${vehicle.maintenance.next}</span>` : ''}
                        ${vehicle.maintenance?.last ? `<span class="text-xs text-muted-foreground">Last: ${vehicle.maintenance.last}</span>` : ''}
                    </div>
                </td>
                
            </tr>
        `;
    }).join('');
    
    tbody.innerHTML = rows;
    console.log(`✅ Table rendered with ${filteredVehicles.length} rows`);
}

function getVehicleTypeIcon(type) {
    const icons = {
        'truck': '<svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h2a1 1 0 001-1m-6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>',
        'van': '<svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
        'car': '<svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>',
        'bike': '<svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6m-9-9h6m6 0h6"/></svg>'
    };
    return icons[type] || icons['truck'];
}

// ============================================================================
// TAB SWITCHING
// ============================================================================
function switchTab(tab, element) {
    console.log(`🔄 Switching tab from ${currentTab} to ${tab}`);
    
    currentTab = tab;
    
    // Update tab visual states
    const tabs = document.querySelectorAll('[role="tab"][data-tab]');
    tabs.forEach(t => {
        const tabValue = t.getAttribute('data-tab');
        if (tabValue === tab) {
            t.setAttribute('data-state', 'active');
            t.classList.add('bg-background', 'text-foreground', 'shadow-sm');
        } else {
            t.setAttribute('data-state', 'inactive');
            t.classList.remove('bg-background', 'text-foreground', 'shadow-sm');
        }
    });
    
    // Re-render table
    renderVehiclesTable(allVehiclesData);
    console.log('✅ Tab switched successfully');
}

function switchPerformanceTab(tab, element) {
    console.log('Switching performance tab to:', tab);
    
    const tabs = document.querySelectorAll('[data-performance-tab]');
    tabs.forEach(t => {
        if (t.getAttribute('data-performance-tab') === tab) {
            t.setAttribute('data-state', 'active');
            t.classList.add('bg-background', 'text-foreground', 'shadow-sm');
        } else {
            t.setAttribute('data-state', 'inactive');
            t.classList.remove('bg-background', 'text-foreground', 'shadow-sm');
        }
    });
    
    const fuelContent = document.getElementById('performance-fuel-content');
    const maintenanceContent = document.getElementById('performance-maintenance-content');
    
    if (tab === 'fuel') {
        if (fuelContent) fuelContent.style.display = 'block';
        if (maintenanceContent) maintenanceContent.style.display = 'none';
    } else {
        if (fuelContent) fuelContent.style.display = 'none';
        if (maintenanceContent) maintenanceContent.style.display = 'block';
    }
}

// ============================================================================
// PERFORMANCE CHARTS
// ============================================================================


// ============================================================================
// PERFORMANCE CHARTS - REAL DATA ONLY (NO SAMPLE DATA)
// ============================================================================

function renderPerformanceCharts(performance) {
    console.log('📈 Rendering performance charts...');
    console.log('Performance data received:', performance);
    
    if (!performance) {
        console.warn('⚠️ No performance data');
        return;
    }
    
    if (typeof Chart === 'undefined') {
        console.warn('⚠️ Chart.js not loaded yet, retrying...');
        setTimeout(() => renderPerformanceCharts(performance), 500);
        return;
    }
    
    renderFuelEfficiencyChart(performance.fuel_efficiency);
    renderMaintenanceCostChart(performance.maintenance_cost);
    
    console.log('✅ Charts rendered');
}

function renderFuelEfficiencyChart(fuelData) {
    console.log('🔋 Rendering fuel efficiency chart...');
    console.log('Fuel data:', fuelData);
    
    const canvas = document.getElementById('fuel-efficiency-chart');
    if (!canvas) {
        console.error('❌ fuel-efficiency-chart canvas not found');
        return;
    }
    
    // Check if we have real data
    if (!fuelData || !fuelData.chart_data || fuelData.chart_data.length === 0) {
        console.warn('⚠️ No fuel efficiency data available');
        
        // Show "No data available" message
        const parent = canvas.parentElement;
        parent.innerHTML = `
            <div class="flex flex-col items-center justify-center h-[300px] text-center">
                <svg class="h-16 w-16 text-muted-foreground mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-sm text-muted-foreground mb-2">No fuel efficiency data available</p>
                <p class="text-xs text-muted-foreground">Data will appear once vehicles record fuel efficiency metrics</p>
            </div>
        `;
        
        // Update stats to show zero
        updateElement('projected-savings', '$0');
        return;
    }
    
    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart
    if (fuelEfficiencyChart) {
        fuelEfficiencyChart.destroy();
    }
    
    fuelEfficiencyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: fuelData.chart_data.map(d => d.month),
            datasets: [{
                label: 'Actual',
                data: fuelData.chart_data.map(d => d.actual),
                borderColor: 'rgb(0, 0, 0)',
                backgroundColor: 'rgba(0, 0, 0, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }, {
                label: 'Target',
                data: fuelData.chart_data.map(d => d.target),
                borderColor: 'rgb(156, 163, 175)',
                backgroundColor: 'rgba(156, 163, 175, 0.1)',
                borderDash: [5, 5],
                tension: 0.4,
                fill: false,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' MPG';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'MPG'
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(1);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
    
    // Update the statistics cards with real data
    updateElement('projected-savings', '$' + (fuelData.projected_savings || '0'));
    
    console.log('✅ Fuel efficiency chart rendered with real data');
}

function renderMaintenanceCostChart(maintenanceData) {
    console.log('🔧 Rendering maintenance cost chart...');
    console.log('Maintenance data:', maintenanceData);
    
    const canvas = document.getElementById('maintenance-cost-chart');
    if (!canvas) {
        console.error('❌ maintenance-cost-chart canvas not found');
        return;
    }
    
    // Check if we have real data
    if (!maintenanceData || !maintenanceData.chart_data || maintenanceData.chart_data.length === 0) {
        console.warn('⚠️ No maintenance cost data available');
        
        // Show "No data available" message
        const parent = canvas.parentElement;
        parent.innerHTML = `
            <div class="flex flex-col items-center justify-center h-[300px] text-center">
                <svg class="h-16 w-16 text-muted-foreground mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-sm text-muted-foreground mb-2">No maintenance cost data available</p>
                <p class="text-xs text-muted-foreground">Data will appear once maintenance records are completed</p>
            </div>
        `;
        
        // Update stats to show zero
        updateElement('preventive-vs-repair', '0% / 0%');
        updateElement('cost-per-mile', '$0.00');
        updateElement('preventive-change', '0% YTD');
        updateElement('cost-per-mile-change', '0% YTD');
        return;
    }
    
    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart
    if (maintenanceCostChart) {
        maintenanceCostChart.destroy();
    }
    
    maintenanceCostChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: maintenanceData.chart_data.map(d => d.month),
            datasets: [{
                label: 'Preventive',
                data: maintenanceData.chart_data.map(d => d.preventive),
                backgroundColor: 'rgb(0, 0, 0)',
                borderRadius: 4,
                barThickness: 30
            }, {
                label: 'Repair',
                data: maintenanceData.chart_data.map(d => d.repair),
                backgroundColor: 'rgb(107, 114, 128)',
                borderRadius: 4,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cost ($)'
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000) {
                                return '$' + (value / 1000).toFixed(0) + 'k';
                            }
                            return '$' + value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // Update the statistics cards with real data
    if (maintenanceData.preventive_vs_repair) {
        const preventive = maintenanceData.preventive_vs_repair.preventive || 0;
        const repair = maintenanceData.preventive_vs_repair.repair || 0;
        const change = maintenanceData.preventive_vs_repair.change || 0;
        
        updateElement('preventive-vs-repair', preventive + '% / ' + repair + '%');
        
        const changeClass = change >= 0 ? 'text-green-600' : 'text-red-600';
        const preventiveChangeEl = document.getElementById('preventive-change');
        if (preventiveChangeEl) {
            preventiveChangeEl.textContent = (change >= 0 ? '+' : '') + change + '% YTD';
            preventiveChangeEl.className = 'text-sm ' + changeClass;
        }
    }
    
    if (maintenanceData.cost_per_mile) {
        const costPerMile = maintenanceData.cost_per_mile.value || 0;
        const change = maintenanceData.cost_per_mile.change || 0;
        
        updateElement('cost-per-mile', '$' + costPerMile.toFixed(2));
        
        const changeClass = change <= 0 ? 'text-green-600' : 'text-red-600';
        const costChangeEl = document.getElementById('cost-per-mile-change');
        if (costChangeEl) {
            costChangeEl.textContent = (change >= 0 ? '+' : '') + change + '% YTD';
            costChangeEl.className = 'text-sm ' + changeClass;
        }
    }
    
    console.log('✅ Maintenance cost chart rendered with real data');
}



// ============================================================================
// VEHICLE MAP & MARKERS
// ============================================================================
function renderVehicleMarkers(locations) {
    const container = document.getElementById('vehicle-markers');
    if (!container) {
        console.warn('⚠️ vehicle-markers container not found');
        return;
    }
    
    console.log('📍 Rendering', locations.length, 'vehicle markers');
    
    const statusColors = {
        'active': 'text-green-500 bg-green-100 dark:bg-green-900',
        'maintenance': 'text-yellow-500 bg-yellow-100 dark:bg-yellow-900',
        'inactive': 'text-blue-500 bg-blue-100 dark:bg-blue-900',
        'repair': 'text-red-500 bg-red-100 dark:bg-red-900'
    };
    
    const markers = locations.map(vehicle => {
        const leftPercent = ((vehicle.longitude + 180) / 360) * 100;
        const topPercent = ((90 - vehicle.latitude) / 180) * 100;
        
        const vehicleJson = JSON.stringify(vehicle).replace(/"/g, '&quot;');
        
        return `
            <div class="absolute w-10 h-10 -ml-5 -mt-5 rounded-full flex items-center justify-center cursor-pointer transition-all ${statusColors[vehicle.status] || statusColors['inactive']} hover:scale-110"
                 style="left:${leftPercent}%;top:${topPercent}%"
                 title="${vehicle.id}: ${vehicle.name}"
                 onclick='showVehiclePopup(${vehicleJson})'>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h2a1 1 0 001-1m-6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
            </div>
        `;
    }).join('');
    
    container.innerHTML = markers;
    console.log('✅ Map markers rendered');
}

function showVehiclePopup(vehicle) {
    console.log('📍 Showing vehicle popup:', vehicle.id);
    const info = `Vehicle: ${vehicle.name}
Status: ${vehicle.status}
Driver: ${vehicle.driver}
Location: ${vehicle.location_name}
Fuel: ${vehicle.fuel}%
Mileage: ${vehicle.mileage}`;
    alert(info);
}

function refreshMapLocations() {
    console.log('🔄 Refreshing map locations...');
    loadDashboardData();
}

// ============================================================================
// MAINTENANCE SCHEDULE & CALENDAR
// ============================================================================
function renderMaintenanceSchedule(schedule) {
    if (!schedule) {
        console.warn('⚠️ No schedule data');
        return;
    }
    
    console.log('📅 Rendering maintenance schedule...');
    console.log('Full schedule object:', schedule);
    console.log('Today items:', schedule.today);
    console.log('No maintenance flag:', schedule.no_maintenance);
    
    updateElement('selected-date-display', schedule.current_date || 'Select a date');
    
    renderCalendar(schedule);
    
    const contentElement = document.getElementById('maintenance-schedule-content');
    if (!contentElement) {
        console.error('❌ maintenance-schedule-content element not found');
        return;
    }
    
    // Check if there's maintenance for this date
    if (schedule.no_maintenance || !schedule.today || schedule.today.length === 0) {
        console.log('⚠️ No maintenance items for this date');
        contentElement.innerHTML = `
            <div class="flex flex-col items-center justify-center h-40 text-center border rounded-md p-4">
                <svg class="h-10 w-10 text-muted-foreground mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-muted-foreground">No maintenance scheduled for this date</p>
            </div>
        `;
    } else {
        // Display maintenance items for the selected date
        console.log(`✅ Rendering ${schedule.today.length} maintenance items`);
        
        const priorityColors = {
            'low': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'medium': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'high': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'urgent': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
        };
        
        const statusColors = {
            'scheduled': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'in_progress': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'pending': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
            'completed': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        };
        
        const maintenanceItems = schedule.today.map(item => {
            console.log('Rendering item:', item);
            return `
            <div class="border rounded-lg p-4 hover:bg-muted/50 transition-colors">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h2a1 1 0 001-1m-6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                            <span class="font-semibold text-base">${item.vehicle_id}</span>
                            <span class="text-sm text-muted-foreground">${item.vehicle_name}</span>
                        </div>
                        <div class="text-sm mb-3 text-foreground">${item.description}</div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="rounded-full px-2.5 py-1 font-medium ${priorityColors[item.priority] || priorityColors['medium']}">
                                ${capitalizeFirst(item.priority)}
                            </span>
                            <span class="rounded-full px-2.5 py-1 font-medium ${statusColors[item.status] || statusColors['scheduled']}">
                                ${capitalizeFirst(item.status.replace('_', ' '))}
                            </span>
                            <span class="px-2.5 py-1 bg-muted rounded-full text-muted-foreground font-medium">${item.category}</span>
                        </div>
                    </div>
                    <div class="text-right text-xs text-muted-foreground ml-4">
                        <div class="font-semibold text-sm mb-1">${item.time}</div>
                        <div class="font-bold text-base text-foreground">${item.estimated_cost}</div>
                    </div>
                </div>
                ${item.vendor !== 'N/A' || item.technician !== 'N/A' ? `
                    <div class="flex gap-4 text-xs text-muted-foreground pt-3 border-t mt-3">
                        ${item.vendor !== 'N/A' ? `
                            <div class="flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span><strong>Vendor:</strong> ${item.vendor}</span>
                            </div>
                        ` : ''}
                        ${item.technician !== 'N/A' ? `
                            <div class="flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span><strong>Technician:</strong> ${item.technician}</span>
                            </div>
                        ` : ''}
                    </div>
                ` : ''}
            </div>
        `;
        }).join('');
        
        contentElement.innerHTML = `
            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                <div class="text-xs text-muted-foreground mb-2">
                    Found ${schedule.today.length} maintenance ${schedule.today.length === 1 ? 'item' : 'items'}
                </div>
                ${maintenanceItems}
            </div>
        `;
    }
    
    console.log('✅ Schedule rendered successfully');
}

function renderCalendar(schedule) {
    const calendarContainer = document.getElementById('maintenance-calendar');
    if (!calendarContainer) return;
    
    const now = currentCalendarDate;
    const year = now.getFullYear();
    const month = now.getMonth();
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    
    const dayNames = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
    
    let calendarHTML = `
        <div class="rdp-months">
            <div class="rdp-month">
                <div class="flex items-center justify-between mb-4">
                    <button type="button" class="inline-flex items-center justify-center h-7 w-7 hover:bg-accent rounded-md" onclick="changeMonth(-1)">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <div class="font-medium">${monthNames[month]} ${year}</div>
                    <button type="button" class="inline-flex items-center justify-center h-7 w-7 hover:bg-accent rounded-md" onclick="changeMonth(1)">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                <table class="rdp-table w-full border-collapse">
                    <thead class="rdp-head">
                        <tr class="rdp-head_row">
                            ${dayNames.map(day => `<th class="rdp-head_cell text-xs text-muted-foreground font-medium p-1">${day}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody class="rdp-tbody">
    `;
    
    let dayCounter = 1;
    let rows = Math.ceil((daysInMonth + startingDayOfWeek) / 7);
    
    for (let row = 0; row < rows; row++) {
        calendarHTML += '<tr class="rdp-row">';
        
        for (let col = 0; col < 7; col++) {
            if (row === 0 && col < startingDayOfWeek) {
                calendarHTML += '<td class="rdp-cell p-0"></td>';
            } else if (dayCounter > daysInMonth) {
                calendarHTML += '<td class="rdp-cell p-0"></td>';
            } else {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCounter).padStart(2, '0')}`;
                const today = new Date();
                const isToday = dayCounter === today.getDate() && month === today.getMonth() && year === today.getFullYear();
                
                const todayClass = isToday ? 'bg-primary text-primary-foreground' : '';
                
                calendarHTML += `
                    <td class="rdp-cell p-0">
                        <button type="button" 
                                class="relative w-full aspect-square flex items-center justify-center hover:bg-accent rounded-md text-sm ${todayClass}"
                                onclick="selectCalendarDate('${dateStr}')">
                            ${dayCounter}
                        </button>
                    </td>
                `;
                dayCounter++;
            }
        }
        
        calendarHTML += '</tr>';
    }
    
    calendarHTML += `
                    </tbody>
                </table>
            </div>
        </div>
    `;
    
    calendarContainer.innerHTML = calendarHTML;
}

function changeMonth(direction) {
    currentCalendarDate.setMonth(currentCalendarDate.getMonth() + direction);
    loadScheduleByDate(currentCalendarDate);
}

function selectCalendarDate(dateStr) {
    console.log('📅 Date selected:', dateStr);
    loadScheduleByDate(new Date(dateStr));
}

async function loadScheduleByDate(date) {
    try {
        showLoadingIndicator();
        
        const dateStr = date.toISOString().split('T')[0];
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const scheduleUrl = `/admin/fleet/schedule/${dateStr}`;
        
        console.log('📅 Loading schedule for date:', dateStr);
        
        const response = await fetch(scheduleUrl, {
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const schedule = await response.json();
        console.log('Schedule received:', schedule);
        
        renderMaintenanceSchedule(schedule);
        
    } catch (error) {
        console.error('Error loading schedule:', error);
        alert('Failed to load maintenance schedule. Please try again.');
    } finally {
        hideLoadingIndicator();
    }
}
// ============================================================================
// FILTERING & SEARCH
// ============================================================================
function filterVehicles() {
    const searchInput = document.getElementById('search-input');
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    
    console.log('🔍 Filtering vehicles with search term:', searchTerm);
    
    let filtered = allVehiclesData;
    
    if (searchTerm) {
        filtered = filtered.filter(v => 
            v.vehicle_id.toLowerCase().includes(searchTerm) ||
            v.type_model.toLowerCase().includes(searchTerm) ||
            v.driver.toLowerCase().includes(searchTerm) ||
            v.location.toLowerCase().includes(searchTerm)
        );
    }
    
    console.log(`Found ${filtered.length} vehicles matching "${searchTerm}"`);
    renderVehiclesTable(filtered);
}

function applyFilters() {
    console.log('🔍 Applying filters...');
    
    const vehicleType = document.getElementById('filter-vehicle-type')?.value;
    const status = document.getElementById('filter-status')?.value;
    const location = document.getElementById('filter-location')?.value;
    const searchTerm = document.getElementById('search-input')?.value.toLowerCase() || '';
    
    console.log('Filters:', { vehicleType, status, location, searchTerm });
    
    let filtered = allVehiclesData;
    
    if (vehicleType) {
        filtered = filtered.filter(v => v.vehicle_type === vehicleType);
    }
    
    if (status) {
        filtered = filtered.filter(v => v.status === status);
    }
    
    if (location) {
        filtered = filtered.filter(v => v.location === location);
    }
    
    if (searchTerm) {
        filtered = filtered.filter(v => 
            v.vehicle_id.toLowerCase().includes(searchTerm) ||
            v.type_model.toLowerCase().includes(searchTerm) ||
            v.driver.toLowerCase().includes(searchTerm) ||
            v.location.toLowerCase().includes(searchTerm)
        );
    }
    
    console.log(`✅ Filtered to ${filtered.length} vehicles`);
    renderVehiclesTable(filtered);
}

function populateLocationFilter(vehicles) {
    const locationFilter = document.getElementById('filter-location');
    if (!locationFilter) return;
    
    const locations = [...new Set(vehicles.map(v => v.location))].sort();
    
    locationFilter.innerHTML = '<option value="">Location</option>' +
        locations.map(loc => `<option value="${loc}">${loc}</option>`).join('');
    
    console.log('✅ Location filter populated with', locations.length, 'locations');
}


// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================
function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function showLoadingIndicator() {
    const indicator = document.getElementById('loading-indicator');
    if (indicator) indicator.style.display = 'block';
}

function hideLoadingIndicator() {
    const indicator = document.getElementById('loading-indicator');
    if (indicator) indicator.style.display = 'none';
}

function handleFetchError(error) {
    console.error('❌ Fetch error:', error);
    
    const errorMessage = `
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg z-50 max-w-md" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Failed to load dashboard data. ${error.message}</span>
            <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    `;
    
    const existingError = document.querySelector('[role="alert"]');
    if (!existingError) {
        document.body.insertAdjacentHTML('beforeend', errorMessage);
        
        setTimeout(() => {
            const alert = document.querySelector('[role="alert"]');
            if (alert) alert.remove();
        }, 5000);
    }
}
















// ============================================================================
// MODAL FUNCTIONS - UPDATED
// ============================================================================

function openAddVehicleModal() {
    console.log('➕ Opening Add Vehicle Modal...');
    document.getElementById('add-vehicle-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddVehicleModal() {
    document.getElementById('add-vehicle-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('add-vehicle-form').reset();
}

function openNewShipmentModal() {
    console.log('📦 Opening New Shipment Modal...');
    document.getElementById('new-shipment-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Populate vehicle dropdown with active vehicles
    const vehicleSelect = document.querySelector('#new-shipment-form select[name="vehicle_id"]');
    if (vehicleSelect && allVehiclesData) {
        const activeVehicles = allVehiclesData.filter(v => v.status === 'active');
        vehicleSelect.innerHTML = '<option value="">Select vehicle</option>' +
            activeVehicles.map(v => `<option value="${v.id}">${v.vehicle_id} - ${v.type_model}</option>`).join('');
    }
}

function closeNewShipmentModal() {
    document.getElementById('new-shipment-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('new-shipment-form').reset();
}

function openScheduleMaintenanceModal() {
    console.log('🔧 Opening Schedule Maintenance Modal...');
    document.getElementById('schedule-maintenance-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Populate vehicle dropdown
    const vehicleSelect = document.querySelector('#schedule-maintenance-form select[name="vehicle_id"]');
    if (vehicleSelect && allVehiclesData) {
        vehicleSelect.innerHTML = '<option value="">Select vehicle</option>' +
            allVehiclesData.map(v => `<option value="${v.id}">${v.vehicle_id} - ${v.type_model}</option>`).join('');
    }
}

function closeScheduleMaintenanceModal() {
    document.getElementById('schedule-maintenance-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('schedule-maintenance-form').reset();
}

// ============================================================================
// FORM HANDLERS
// ============================================================================

async function handleAddVehicle(event) {
    event.preventDefault();
    console.log('Submitting Add Vehicle form...');
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/admin/store/vehicles', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            alert('Vehicle added successfully!');
            closeAddVehicleModal();
            loadDashboardData(); // Reload dashboard
        } else {
            alert('Failed to add vehicle. Please try again.');
        }
    } catch (error) {
        console.error('Error adding vehicle:', error);
        alert('An error occurred. Please try again.');
    }
}

async function handleNewShipment(event) {
    event.preventDefault();
    console.log('Submitting New Shipment form...');
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/admin/fleet/shipment/new', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            alert('Shipment created successfully!');
            closeNewShipmentModal();
            loadDashboardData();
        } else {
            alert('Failed to create shipment. Please try again.');
        }
    } catch (error) {
        console.error('Error creating shipment:', error);
        alert('An error occurred. Please try again.');
    }
}

async function handleScheduleMaintenance(event) {
    event.preventDefault();
    console.log('Submitting Schedule Maintenance form...');
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    try {
        showLoadingIndicator();
        
        const response = await fetch('/admin/store/maintenance', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Maintenance scheduled successfully!');
            closeScheduleMaintenanceModal();
            loadDashboardData();
        } else {
            alert(result.message || 'Failed to schedule maintenance. Please try again.');
        }
    } catch (error) {
        console.error('Error scheduling maintenance:', error);
        alert('An error occurred. Please try again.');
    } finally {
        hideLoadingIndicator();
    }
}

// ============================================================================
// ADVANCED FILTERS
// ============================================================================

// Toggle advanced filters dropdown
document.querySelector('[onclick="applyFilters()"]')?.addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('advanced-filters-dropdown');
    dropdown.classList.toggle('hidden');
    
    // Position dropdown
    const button = this;
    const rect = button.getBoundingClientRect();
    dropdown.style.top = (rect.bottom + window.scrollY) + 'px';
    dropdown.style.right = (window.innerWidth - rect.right) + 'px';
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('advanced-filters-dropdown');
    const filterButton = document.querySelector('[onclick="applyFilters()"]');
    
    if (!dropdown.contains(e.target) && e.target !== filterButton) {
        dropdown.classList.add('hidden');
    }
});

function applyAdvancedFilters() {
    console.log('🔍 Applying advanced filters...');
    
    const fuelMin = document.getElementById('fuel-min').value;
    const fuelMax = document.getElementById('fuel-max').value;
    const mileageMin = document.getElementById('mileage-min').value;
    const mileageMax = document.getElementById('mileage-max').value;
    const maintenanceDue = document.getElementById('maintenance-due').value;
    const driverAssignment = document.getElementById('driver-assignment').value;
    
    let filtered = allVehiclesData;
    
    // Apply fuel filter
    if (fuelMin) {
        filtered = filtered.filter(v => parseFloat(v.fuel) >= parseFloat(fuelMin));
    }
    if (fuelMax) {
        filtered = filtered.filter(v => parseFloat(v.fuel) <= parseFloat(fuelMax));
    }
    
    // Apply mileage filter
    if (mileageMin) {
        filtered = filtered.filter(v => parseInt(v.mileage.replace(/,/g, '')) >= parseInt(mileageMin));
    }
    if (mileageMax) {
        filtered = filtered.filter(v => parseInt(v.mileage.replace(/,/g, '')) <= parseInt(mileageMax));
    }
    
    // Apply driver assignment filter
    if (driverAssignment === 'assigned') {
        filtered = filtered.filter(v => v.driver !== 'Unassigned');
    } else if (driverAssignment === 'unassigned') {
        filtered = filtered.filter(v => v.driver === 'Unassigned');
    }
    
    console.log(`✅ Advanced filters applied: ${filtered.length} vehicles`);
    renderVehiclesTable(filtered);
    
    // Close dropdown
    document.getElementById('advanced-filters-dropdown').classList.add('hidden');
}

function clearAdvancedFilters() {
    document.getElementById('fuel-min').value = '';
    document.getElementById('fuel-max').value = '';
    document.getElementById('mileage-min').value = '';
    document.getElementById('mileage-max').value = '';
    document.getElementById('maintenance-due').value = '';
    document.getElementById('driver-assignment').value = '';
    
    renderVehiclesTable(allVehiclesData);
    console.log('✅ Advanced filters cleared');
}

function saveFilterPreset() {
    const preset = {
        fuelMin: document.getElementById('fuel-min').value,
        fuelMax: document.getElementById('fuel-max').value,
        mileageMin: document.getElementById('mileage-min').value,
        mileageMax: document.getElementById('mileage-max').value,
        maintenanceDue: document.getElementById('maintenance-due').value,
        driverAssignment: document.getElementById('driver-assignment').value
    };
    
    localStorage.setItem('fleet_filter_preset', JSON.stringify(preset));
    alert('Filter preset saved successfully!');
}

function loadFilterPreset() {
    const preset = JSON.parse(localStorage.getItem('fleet_filter_preset') || '{}');
    
    if (Object.keys(preset).length === 0) {
        alert('No saved filter preset found.');
        return;
    }
    
    document.getElementById('fuel-min').value = preset.fuelMin || '';
    document.getElementById('fuel-max').value = preset.fuelMax || '';
    document.getElementById('mileage-min').value = preset.mileageMin || '';
    document.getElementById('mileage-max').value = preset.mileageMax || '';
    document.getElementById('maintenance-due').value = preset.maintenanceDue || '';
    document.getElementById('driver-assignment').value = preset.driverAssignment || '';
    
    alert('Filter preset loaded successfully!');
}




// ============================================================================
// MAKE FUNCTIONS GLOBALLY ACCESSIBLE
// ============================================================================
window.loadDashboardData = loadDashboardData;
window.switchTab = switchTab;
window.switchPerformanceTab = switchPerformanceTab;
window.applyFilters = applyFilters;
window.filterVehicles = filterVehicles;
window.refreshMapLocations = refreshMapLocations;
window.changeMonth = changeMonth;
window.selectCalendarDate = selectCalendarDate;
window.showVehiclePopup = showVehiclePopup;
window.openAddVehicleModal = openAddVehicleModal;
window.closeAddVehicleModal = closeAddVehicleModal;
window.openNewShipmentModal = openNewShipmentModal;
window.closeNewShipmentModal = closeNewShipmentModal;
window.openScheduleMaintenanceModal = openScheduleMaintenanceModal;
window.closeScheduleMaintenanceModal = closeScheduleMaintenanceModal;
window.handleAddVehicle = handleAddVehicle;
window.handleNewShipment = handleNewShipment;
window.handleScheduleMaintenance = handleScheduleMaintenance;
window.applyAdvancedFilters = applyAdvancedFilters;
window.clearAdvancedFilters = clearAdvancedFilters;
window.saveFilterPreset = saveFilterPreset;
window.loadFilterPreset = loadFilterPreset;


</script>


@endsection