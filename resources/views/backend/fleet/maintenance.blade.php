@extends('admin.admin_dashboard')
@section('admin')

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Maintenance Logs</h1> 
            <p class="text-muted-foreground">Track and manage all vehicle maintenance records and schedules</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openScheduleModal()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                Schedule Maintenance
            </button>
            <div class="relative">
                <button onclick="toggleExportMenu()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground rounded-md px-3 h-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                        <path d="M12 15V3"></path>
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <path d="m7 10 5 5 5-5"></path>
                    </svg>
                    Export
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 ml-2">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>
                <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                    <a href="{{ route('admin.maintenance.export', 'csv') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Export as CSV
                    </a>
                    <a href="{{ route('admin.maintenance.export', 'excel') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Export as Excel
                    </a>
                    <a href="{{ route('admin.maintenance.export', 'pdf') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Export as PDF
                    </a>
                    <button onclick="window.print()" class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-2">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect width="12" height="8" x="6" y="14"></rect>
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Total Records</h3>
                <div class="p-2 bg-primary/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-muted-foreground">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                        <path d="M10 9H8"></path>
                        <path d="M16 13H8"></path>
                        <path d="M16 17H8"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $stats['total_records'] }}</div>
                <p class="text-xs text-muted-foreground">Maintenance records tracked</p>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Completion Rate</h3>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-green-500">
                        <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                        <path d="m9 11 3 3L22 4"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $stats['completion_rate'] }}%</div>
                <div class="relative h-2 w-full overflow-hidden rounded-full bg-secondary mt-2">
                    <div class="h-full bg-primary transition-all" style="width: {{ $stats['completion_rate'] }}%"></div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Total Cost</h3>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-green-500">
                        <line x1="12" x2="12" y1="2" y2="22"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">${{ number_format($stats['total_cost'], 2) }}</div>
                <p class="text-xs text-muted-foreground">Average: ${{ number_format($stats['average_cost'], 2) }} per service</p>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Overdue Items</h3>
                <div class="p-2 bg-red-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-red-500">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold text-red-600">{{ $stats['overdue_items'] }}</div>
                <p class="text-xs text-muted-foreground">Require immediate attention</p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"></path>
                </svg>
                Filters & Search
            </h3>
        </div>
        <div class="p-4 md:p-6 pt-0 space-y-4">
            <form id="filterForm" class="space-y-4">
                <div class="flex flex-col gap-4 md:flex-row">
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                        <input type="text" name="search" id="searchInput" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm pl-8" placeholder="Search by vehicle, description, provider, or technician..."/>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="toggleViewMode()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                            <svg id="viewModeIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                                <path d="M3 9h18"></path>
                                <path d="M3 15h18"></path>
                                <path d="M9 3v18"></path>
                                <path d="M15 3v18"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                    <select name="vehicle_id" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">All Vehicles</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_number }} - {{ $vehicle->vehicle_type }} {{ $vehicle->model }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>

                    <select name="maintenance_type" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">All Types</option>
                        @foreach($maintenanceTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>

                    <select name="priority" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">All Priorities</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>

                    <button type="button" onclick="applyFilters()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table View -->
    <div id="tableView" class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex flex-wrap gap-2 items-center justify-between">
                <span>Maintenance Records ({{ $maintenanceLogs->total() }})</span>
                <button onclick="toggleViewMode()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <rect width="7" height="7" x="3" y="3" rx="1"></rect>
                        <rect width="7" height="7" x="14" y="3" rx="1"></rect>
                        <rect width="7" height="7" x="14" y="14" rx="1"></rect>
                        <rect width="7" height="7" x="3" y="14" rx="1"></rect>
                    </svg>
                    Card View
                </button>
            </h3>
        </div>
        <div class="p-4 md:p-6 pt-0">
            <div class="overflow-x-auto">
                <table class="w-full caption-bottom text-sm whitespace-nowrap">
                    <thead class="[&_tr]:border-b">
                        <tr class="border-b transition-colors hover:bg-muted/50">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground w-12">
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" class="h-4 w-4 rounded border-primary"/>
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Service ID</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Vehicle</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Service Type</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Category</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Description</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Priority</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Provider</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Cost</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Scheduled</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0" id="maintenanceTableBody">
                        @foreach($maintenanceLogs as $log)
                        <tr class="border-b transition-colors hover:bg-muted/50">
                            <td class="p-4 align-middle">
                                <input type="checkbox" class="row-checkbox h-4 w-4 rounded border-primary" value="{{ $log->id }}"/>
                            </td>
                            <td class="p-4 align-middle font-medium">{{ $log->log_number }}</td>
                            <td class="p-4 align-middle">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-muted-foreground">
                                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                        <path d="M15 18H9"></path>
                                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                        <circle cx="17" cy="18" r="2"></circle>
                                        <circle cx="7" cy="18" r="2"></circle>
                                    </svg>
                                    <div>
                                        <div class="font-medium">{{ $log->vehicle->vehicle_number ?? 'N/A'}}</div>
                                        <div class="text-sm text-muted-foreground">{{ $log->vehicle->vehicle_type ?? 'N/A' }} {{ $log->vehicle->model ?? 'N/A'}}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 align-middle">{{ ucfirst($log->maintenance_type) }}</td>
                            <td class="p-4 align-middle">{{ $log->category ?? 'N/A' }}</td>
                            <td class="p-4 align-middle max-w-xs truncate">{{ $log->description }}</td>
                            <td class="p-4 align-middle">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-{{ $log->status_color }}-100 text-{{ $log->status_color }}-800">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="p-4 align-middle">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-{{ $log->priority_color }}-100 text-{{ $log->priority_color }}-800">
                                    {{ ucfirst($log->priority ?? 'medium') }}
                                </span>
                            </td>
                            <td class="p-4 align-middle">
                                <div>
                                    <div class="font-medium">{{ $log->vendor_name ?? 'N/A' }}</div>
                                    <div class="text-sm text-muted-foreground">{{ $log->technician_name ?? ($log->performedBy->name ?? 'N/A') }}</div>
                                </div>
                            </td>
                            <td class="p-4 align-middle">${{ number_format($log->cost, 2) }}</td>
                            <td class="p-4 align-middle">{{ $log->maintenance_date->format('Y-m-d') }}</td>
                            <td class="p-4 align-middle">
                                <div class="relative">
                                    <button onclick="toggleActionMenu({{ $log->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium hover:bg-accent h-10 w-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg>
                                    </button>
                                    <div id="actionMenu-{{ $log->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                                        <button onclick="viewDetails({{ $log->id }})" class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="inline mr-2" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            View Details
                                        </button>
                                        <button onclick="editRecord({{ $log->id }})" class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="inline mr-2" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit Record
                                        </button>
                                        <button onclick="viewDocuments({{ $log->id }})" class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="inline mr-2" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                            </svg>
                                            View Documents
                                        </button>
                                        <button onclick="scheduleFollowUp({{ $log->id }})" class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="inline mr-2" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            Schedule Follow-up
                                        </button>
                                        <button onclick="deleteRecord({{ $log->id }})" class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-100 text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="inline mr-2" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                            Delete
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

        <!-- Add this right after the closing </table> tag and before the closing </div> of the table view -->

<!-- Pagination Controls -->
<div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
    <!-- Per Page Selector -->
    <div class="flex items-center gap-2">
        <label class="text-sm text-muted-foreground">Show:</label>
        <select id="perPageSelect" onchange="changePerPage(this.value)" class="h-9 rounded-md border border-input bg-background px-3 py-1 text-sm">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ request('per_page') == 15 || !request('per_page') ? 'selected' : '' }}>15</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
        </select>
        <span class="text-sm text-muted-foreground">per page</span>
    </div>

    <!-- Pagination Info -->
    <div class="text-sm text-muted-foreground">
        Showing {{ $maintenanceLogs->firstItem() ?? 0 }} to {{ $maintenanceLogs->lastItem() ?? 0 }} 
        of {{ $maintenanceLogs->total() }} results
    </div>

    <!-- Pagination Links -->
    <div class="flex items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($maintenanceLogs->onFirstPage())
            <button disabled class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background h-9 w-9 opacity-50 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
            </button>
        @else
            <a href="{{ $maintenanceLogs->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" 
               class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 w-9">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
            </a>
        @endif

        {{-- Page Numbers --}}
        <div class="flex items-center gap-1">
            @php
                $start = max($maintenanceLogs->currentPage() - 2, 1);
                $end = min($start + 4, $maintenanceLogs->lastPage());
                $start = max($end - 4, 1);
            @endphp

            @if($start > 1)
                <a href="{{ $maintenanceLogs->url(1) }}&{{ http_build_query(request()->except('page')) }}" 
                   class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 w-9">
                    1
                </a>
                @if($start > 2)
                    <span class="px-2">...</span>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $maintenanceLogs->currentPage())
                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground h-9 w-9">
                        {{ $i }}
                    </button>
                @else
                    <a href="{{ $maintenanceLogs->url($i) }}&{{ http_build_query(request()->except('page')) }}" 
                       class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 w-9">
                        {{ $i }}
                    </a>
                @endif
            @endfor

            @if($end < $maintenanceLogs->lastPage())
                @if($end < $maintenanceLogs->lastPage() - 1)
                    <span class="px-2">...</span>
                @endif
                <a href="{{ $maintenanceLogs->url($maintenanceLogs->lastPage()) }}&{{ http_build_query(request()->except('page')) }}" 
                   class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 w-9">
                    {{ $maintenanceLogs->lastPage() }}
                </a>
            @endif
        </div>

        {{-- Next Page Link --}}
        @if ($maintenanceLogs->hasMorePages())
            <a href="{{ $maintenanceLogs->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" 
               class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 w-9">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
            </a>
        @else
            <button disabled class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background h-9 w-9 opacity-50 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
            </button>
        @endif
    </div>
</div>
    </div>

    <!-- Card View (Hidden by default) -->
    <div id="cardView" class="hidden grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach($maintenanceLogs as $log)
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" class="row-checkbox h-4 w-4 rounded border-primary" value="{{ $log->id }}"/>
                    <h3 class="text-xl font-bold">{{ $log->log_number }}</h3>
                </div>
                <button onclick="toggleActionMenu({{ $log->id }})" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted-foreground">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                    <span class="font-medium">{{ $log->vehicle->vehicle_number ?? 'N/A'}}</span>
                    <span class="text-muted-foreground">{{ $log->vehicle->vehicle_type ?? 'N/A' }} {{ $log->vehicle->model ?? 'N/A'}}</span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <span class="text-muted-foreground">Service Type</span>
                        <div class="font-medium">{{ ucfirst($log->maintenance_type) }}</div>
                    </div>
                    <div>
                        <span class="text-muted-foreground">Category</span>
                        <div class="font-medium">{{ $log->category ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="text-sm">
                    <span class="text-muted-foreground">Description</span>
                    <div class="font-medium">{{ $log->description }}</div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <span class="text-xs text-muted-foreground">Status</span>
                        <div>
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-{{ $log->status_color }}-100 text-{{ $log->status_color }}-800">
                                {{ ucfirst($log->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs text-muted-foreground">Priority</span>
                        <div>
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-{{ $log->priority_color }}-100 text-{{ $log->priority_color }}-800">
                                {{ ucfirst($log->priority ?? 'medium') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="text-sm">
                    <span class="text-muted-foreground">Provider</span>
                    <div class="font-medium">{{ $log->vendor_name ?? 'N/A' }}</div>
                    <div class="text-xs text-muted-foreground">{{ $log->technician_name ?? ($log->performedBy->name ?? 'N/A') }}</div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <span class="text-muted-foreground">Cost</span>
                        <div class="font-bold text-lg">${{ number_format($log->cost, 2) }}</div>
                    </div>
                    <div>
                        <span class="text-muted-foreground">Scheduled</span>
                        <div class="font-medium">{{ $log->maintenance_date->format('Y-m-d') }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Schedule Maintenance Modal -->
<div id="scheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-2xl font-bold">Schedule New Maintenance</h2>
            <button onclick="closeScheduleModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="scheduleForm" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Vehicle</label>
                    <select name="vehicle_id" required class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">Select vehicle</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_number }} - {{ $vehicle->vehicle_type }} {{ $vehicle->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Service Type</label>
                    <select name="maintenance_type" required class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">Select type</option>
                        @foreach($maintenanceTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Category</label>
                    <input type="text" name="category" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="e.g., Engine, Brakes"/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Priority</label>
                    <select name="priority" required class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm">
                        @foreach($priorities as $priority)
                            <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" required rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Describe the maintenance work needed..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Service Provider</label>
                    <input type="text" name="vendor_name" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Provider name"/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Scheduled Date</label>
                    <input type="date" name="maintenance_date" required class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Technician Name</label>
                    <input type="text" name="technician_name" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Technician name"/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Estimated Cost ($)</label>
                    <input type="number" name="cost" required step="0.01" min="0" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="0.00"/>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Additional Notes</label>
                <textarea name="notes" rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Any additional information..."></textarea>
            </div>

            <input type="hidden" name="status" value="scheduled"/>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeScheduleModal()" class="px-4 py-2 border rounded-md hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">Schedule Maintenance</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-2xl font-bold">Edit Maintenance Record</h2>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="editForm" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_record_id" name="record_id"/>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <select name="status" id="edit_status" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Priority</label>
                    <select name="priority" id="edit_priority" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm">
                        @foreach($priorities as $priority)
                            <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" id="edit_description" rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Service Provider</label>
                    <input type="text" name="vendor_name" id="edit_vendor_name" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Technician</label>
                    <input type="text" name="technician_name" id="edit_technician_name" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Cost ($)</label>
                    <input type="number" name="cost" id="edit_cost" step="0.01" min="0" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Mileage</label>
                    <input type="number" name="mileage_at_maintenance" id="edit_mileage" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Notes</label>
                <textarea name="notes" id="edit_notes" rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded-md hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-2xl font-bold">Maintenance Record Details</h2>
            <button onclick="closeDetailsModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div id="detailsContent" class="p-6">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>

<!-- Follow-up Maintenance Modal -->
<div id="followUpModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Schedule Follow-up Maintenance</h2>
                <p id="followup_vehicle_info" class="text-sm text-gray-600 mt-1">Create a follow-up maintenance record</p>
            </div>
            <button onclick="closeFollowUpModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="followUpForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="followup_parent_id" name="parent_id"/>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Service Type</label>
                    <select name="maintenance_type" required class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">Select type</option>
                        <option value="scheduled">Preventive</option>
                        <option value="inspection">Inspection</option>
                        <option value="repair">Corrective</option>
                        <option value="breakdown">Emergency</option>
                        <option value="service">Service</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Category</label>
                    <input type="text" name="category" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="e.g., Transmission"/>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" required rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Describe the follow-up maintenance work needed..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Priority</label>
                    <select name="priority" required class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Scheduled Date</label>
                    <input type="date" name="maintenance_date" required class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Service Provider</label>
                    <input type="text" name="vendor_name" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Provider name"/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Estimated Cost ($)</label>
                    <input type="number" name="cost" required step="0.01" min="0" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="0.00"/>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Follow-up maintenance for previous service"></textarea>
            </div>

            <input type="hidden" name="vehicle_id"/>
            <input type="hidden" name="status" value="scheduled"/>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeFollowUpModal()" class="px-4 py-2 border rounded-md hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">Schedule Follow-up</button>
            </div>
        </form>
    </div>
</div>

<script>
// View mode toggle
let isCardView = false;
function toggleViewMode() {
    isCardView = !isCardView;
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    
    if (isCardView) {
        tableView.classList.add('hidden');
        cardView.classList.remove('hidden');
    } else {
        tableView.classList.remove('hidden');
        cardView.classList.add('hidden');
    }
}

// Export menu toggle
function toggleExportMenu() {
    const menu = document.getElementById('exportMenu');
    menu.classList.toggle('hidden');
}

// Close export menu when clicking outside
document.addEventListener('click', function(event) {
    const exportMenu = document.getElementById('exportMenu');
    const exportButton = event.target.closest('button[onclick="toggleExportMenu()"]');
    
    if (!exportButton && !exportMenu.contains(event.target)) {
        exportMenu.classList.add('hidden');
    }
});

// Action menu toggle
function toggleActionMenu(id) {
    const menu = document.getElementById('actionMenu-' + id);
    // Close all other menus
    document.querySelectorAll('[id^="actionMenu-"]').forEach(m => {
        if (m.id !== 'actionMenu-' + id) m.classList.add('hidden');
    });
    menu.classList.toggle('hidden');
}

// Close action menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleActionMenu"]') && !event.target.closest('[id^="actionMenu-"]')) {
        document.querySelectorAll('[id^="actionMenu-"]').forEach(m => m.classList.add('hidden'));
    }
});

// Select all checkboxes
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

// Apply filters
function applyFilters() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    window.location.href = '{{ route("admin.maintenance.index") }}?' + params.toString();
}

// Real-time search
let searchTimeout;
document.getElementById('searchInput')?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
});

// Schedule Modal
function openScheduleModal() {
    document.getElementById('scheduleModal').classList.remove('hidden');
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.getElementById('scheduleForm').reset();
}

// Handle schedule form submission
document.getElementById('scheduleForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("admin.maintenance.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Maintenance scheduled successfully!');
            closeScheduleModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to schedule maintenance'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});

// View details
async function viewDetails(id) {
    try {
        const response = await fetch(`/admin/maintenance/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const log = data.data;
            document.getElementById('detailsContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Service ID</h3>
                            <p class="text-lg font-semibold">${log.log_number}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold">${log.status}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Vehicle</h3>
                            <p class="text-lg">${log.vehicle.vehicle_number} - ${log.vehicle.vehicle_type} ${log.vehicle.model}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Mileage</h3>
                            <p class="text-lg">${log.mileage_at_maintenance ? log.mileage_at_maintenance + ' km' : 'N/A'}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Description</h3>
                        <p class="text-lg">${log.description}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Service Type</h3>
                            <p class="text-lg">${log.maintenance_type}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Category</h3>
                            <p class="text-lg">${log.category || 'N/A'}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Priority</h3>
                            <p class="text-lg">${log.priority || 'medium'}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Total Cost</h3>
                            <p class="text-lg font-bold">${parseFloat(log.cost).toFixed(2)}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Scheduled Date</h3>
                            <p class="text-lg">${log.maintenance_date}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Completed Date</h3>
                            <p class="text-lg">${log.completed_date || 'Not completed'}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Service Provider</h3>
                            <p class="text-lg">${log.vendor_name || 'N/A'}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Technician</h3>
                            <p class="text-lg">${log.technician_name || (log.performed_by ? log.performed_by.name : 'N/A')}</p>
                        </div>
                    </div>
                    ${log.notes ? `
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Notes</h3>
                        <p class="text-lg">${log.notes}</p>
                    </div>` : ''}
                </div>
            `;
            document.getElementById('detailsModal').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load details');
    }
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}

// Follow-up Modal functions
function closeFollowUpModal() {
    document.getElementById('followUpModal').classList.add('hidden');
    document.getElementById('followUpForm').reset();
}

// Handle follow-up form submission
document.getElementById('followUpForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const parentId = document.getElementById('followup_parent_id').value;
    
    try {
        const response = await fetch(`/admin/maintenance/${parentId}/follow-up`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Follow-up maintenance scheduled successfully!');
            closeFollowUpModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to schedule follow-up maintenance'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});

// Edit record
async function editRecord(id) {
    try {
        const response = await fetch(`/admin/maintenance/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const log = data.data;
            document.getElementById('edit_record_id').value = log.id;
            document.getElementById('edit_status').value = log.status;
            document.getElementById('edit_priority').value = log.priority || 'medium';
            document.getElementById('edit_description').value = log.description;
            document.getElementById('edit_vendor_name').value = log.vendor_name || '';
            document.getElementById('edit_technician_name').value = log.technician_name || '';
            document.getElementById('edit_cost').value = log.cost;
            document.getElementById('edit_mileage').value = log.mileage_at_maintenance || '';
            document.getElementById('edit_notes').value = log.notes || '';
            
            document.getElementById('editModal').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load record');
    }
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editForm').reset();
}

// Handle edit form submission
document.getElementById('editForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const id = document.getElementById('edit_record_id').value;
    
    try {
        const response = await fetch(`/admin/maintenance/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Record updated successfully!');
            closeEditModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update record'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});

// View documents (placeholder - implement based on your needs)
function viewDocuments(id) {
    alert('Documents feature - implement file upload/download functionality');
}

// Schedule follow-up
async function scheduleFollowUp(id) {
    try {
        const response = await fetch(`/admin/maintenance/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const log = data.data;
            
            // Open a dedicated follow-up modal
            document.getElementById('followUpModal').classList.remove('hidden');
            
            // Pre-fill the form with related data
            document.getElementById('followup_parent_id').value = log.id;
            document.getElementById('followup_vehicle_info').textContent = 
                `Create a follow-up maintenance record for ${log.vehicle.vehicle_type} ${log.vehicle.model} (${log.vehicle.vehicle_number})`;
            
            // Set vehicle (hidden field since it's the same vehicle)
            const vehicleSelect = document.querySelector('#followUpForm select[name="vehicle_id"]');
            if (vehicleSelect) {
                vehicleSelect.value = log.vehicle_id;
                vehicleSelect.disabled = true; // Can't change vehicle for follow-up
            }
            
            // Pre-fill category if available
            const categoryInput = document.querySelector('#followUpForm input[name="category"]');
            if (categoryInput && log.category) {
                categoryInput.value = log.category;
            }
            
            // Pre-fill service provider
            const providerInput = document.querySelector('#followUpForm input[name="vendor_name"]');
            if (providerInput && log.vendor_name) {
                providerInput.value = log.vendor_name;
            }
            
            // Set default description
            const descriptionTextarea = document.querySelector('#followUpForm textarea[name="description"]');
            if (descriptionTextarea) {
                descriptionTextarea.value = `Follow-up to ${log.log_number}: ${log.description}`;
            }
            
            // Set default notes
            const notesTextarea = document.querySelector('#followUpForm textarea[name="notes"]');
            if (notesTextarea) {
                notesTextarea.value = `Follow-up maintenance for previous service (${log.log_number})`;
            }
            
            // Set default service type to preventive for follow-ups
            const serviceTypeSelect = document.querySelector('#followUpForm select[name="maintenance_type"]');
            if (serviceTypeSelect) {
                serviceTypeSelect.value = 'scheduled';
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load maintenance details for follow-up');
    }
}

// Delete record
async function deleteRecord(id) {
    if (!confirm('Are you sure you want to delete this maintenance record?')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/maintenance/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Record deleted successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to delete record'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

// Set today's date as minimum for date inputs
document.addEventListener('DOMContentLoaded', function() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    dateInputs.forEach(input => {
        if (!input.value) {
            input.setAttribute('min', today);
        }
    });
});




// Add this to your existing script section

// Change items per page
function changePerPage(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page when changing per_page
    window.location.href = url.toString();
}

// Update the applyFilters function to preserve per_page
function applyFilters() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    // Preserve per_page setting
    const perPage = document.getElementById('perPageSelect')?.value;
    if (perPage) {
        params.set('per_page', perPage);
    }
    
    window.location.href = '{{ route("admin.maintenance.index") }}?' + params.toString();
}

// Update real-time search to preserve pagination
let searchTimeout;
document.getElementById('searchInput')?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
}

/* Custom scrollbar for modals */
#scheduleModal > div,
#editModal > div,
#detailsModal > div {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

#scheduleModal > div::-webkit-scrollbar,
#editModal > div::-webkit-scrollbar,
#detailsModal > div::-webkit-scrollbar {
    width: 8px;
}

#scheduleModal > div::-webkit-scrollbar-track,
#editModal > div::-webkit-scrollbar-track,
#detailsModal > div::-webkit-scrollbar-track {
    background: #f7fafc;
}

#scheduleModal > div::-webkit-scrollbar-thumb,
#editModal > div::-webkit-scrollbar-thumb,
#detailsModal > div::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 4px;
}
</style>

@endsection