@extends('admin.admin_dashboard')
@section('admin')




		
		

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl md:text-2xl font-bold tracking-tight">Driver Assignments</h1>
            <p class="text-muted-foreground">Manage driver assignments, schedules, and performance tracking</p>
        </div>
        <div class="flex gap-2">
            <!-- Export Dropdown -->
            <div class="relative">
                <button id="exportDropdown" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground rounded-md px-3 h-10" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download h-4 w-4 mr-2">
                        <path d="M12 15V3"></path>
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <path d="m7 10 5 5 5-5"></path>
                    </svg>
                    Export
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 ml-2">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>
                <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border">
                    <a href="{{ route('admin.drivers.export', ['format' => 'csv']) }}" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Export as CSV
                    </a>
                    <a href="{{ route('admin.drivers.export', ['format' => 'excel']) }}" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Export as Excel
                    </a>
                    <a href="{{ route('admin.drivers.export', ['format' => 'pdf']) }}" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Export as PDF
                    </a>
                    <button onclick="window.print()" class="w-full flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect width="12" height="8" x="6" y="14"></rect>
                        </svg>
                        Print
                    </button>
                </div>
            </div>
            
            <!-- Add Driver Button -->
            <button onclick="openAddDriverModal()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus mr-2 h-4 w-4">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <line x1="19" x2="19" y1="8" y2="14"></line>
                    <line x1="22" x2="16" y1="11" y2="11"></line>
                </svg>
                Add Driver
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Total Drivers</h3>
                <div class="p-2 bg-primary/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4 text-primary">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $totalDrivers }}</div>
                <p class="text-xs text-muted-foreground">+{{ $driversAddedThisMonth }} from last month</p>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Available Drivers</h3>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big h-4 w-4 text-green-500">
                        <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                        <path d="m9 11 3 3L22 4"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $availableDrivers }}</div>
                <p class="text-xs text-muted-foreground">Ready for assignment</p>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">On Route</h3>
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity h-4 w-4 text-blue-500">
                        <path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ $onRouteDrivers }}</div>
                <p class="text-xs text-muted-foreground">Currently driving</p>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Avg Rating</h3>
                <div class="p-2 bg-yellow-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star h-4 w-4 text-yellow-500">
                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="text-2xl font-bold">{{ number_format($avgRating, 1) }}</div>
                <p class="text-xs text-muted-foreground">Fleet average</p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="space-y-4">
        <div class="inline-flex items-center rounded-md bg-muted p-1 text-muted-foreground flex-wrap h-full justify-start">
            <button onclick="switchTab('drivers')" id="tab-drivers" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-background text-foreground shadow-sm">
                Drivers
            </button>
            <button onclick="switchTab('assignments')" id="tab-assignments" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                Assignments
            </button>
            <button onclick="switchTab('performance')" id="tab-performance" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                Performance
            </button>
        </div>

        <!-- Drivers Tab -->
        <div id="content-drivers" class="tab-content space-y-4">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Driver Management</h3>
                    <div class="text-sm text-muted-foreground">View and manage all drivers in your fleet</div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-wrap justify-between gap-2 w-full">
                            <!-- Search -->
                            <div class="relative min-w-[250px]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2 top-2.5 h-4 w-4 text-muted-foreground">
                                    <path d="m21 21-4.34-4.34"></path>
                                    <circle cx="11" cy="11" r="8"></circle>
                                </svg>
                                <input id="searchInput" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8" placeholder="Search drivers..." value=""/>
                            </div>
                            
                            <!-- Status Filter -->
                            <select id="statusFilter" class="flex h-10 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-[180px]">
                                <option value="">All Status</option>
                                <option value="available">Available</option>
                                <option value="on_route">On Route</option>
                                <option value="off_duty">Off Duty</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Driver Cards -->
            <div id="driverCards" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($drivers as $driver)
                <div class="driver-card rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow" data-status="{{ $driver->status }}" data-name="{{ strtolower($driver->first_name . ' ' . $driver->last_name) }}">
                    <div class="flex flex-col space-y-1.5 p-4 md:p-6 pb-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                    @if($driver->profile_photo)
                                        <img alt="{{ $driver->first_name }} {{ $driver->last_name }}" src="{{ asset('storage/' . $driver->profile_photo) }}" class="w-full h-full object-cover"/>
                                    @else
                                        <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}</span>
                                    @endif
                                </span>
                                <div>
                                    <h3 class="sm:text-2xl font-semibold tracking-tight text-lg">{{ $driver->first_name }} {{ $driver->last_name }}</h3>
                                    <div class="text-sm text-muted-foreground">{{ $driver->employee_id }}</div>
                                </div>
                            </div>
                            <!-- 3 Dots Menu -->
                            <div class="relative">
                                <button onclick="toggleMenu({{ $driver->id }})" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8 p-0" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis h-4 w-4">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </button>
                                <div id="menu-{{ $driver->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border">
                                    <div class="py-1">
                                        <div class="px-4 py-2 text-sm font-semibold border-b">Actions</div>
                                        <button onclick="viewDriverDetails({{ $driver->id }})" class="flex items-center w-full px-4 py-2 text-sm hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            View Details
                                        </button>
                                        <button onclick="openAssignDriverModal({{ $driver->id }})" class="flex items-center w-full px-4 py-2 text-sm hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                                                <circle cx="6" cy="19" r="3"></circle>
                                                <path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"></path>
                                                <circle cx="18" cy="5" r="3"></circle>
                                            </svg>
                                            Assign Route
                                        </button>
                                        <button onclick="openEditDriverModal({{ $driver->id }})" class="flex items-center w-full px-4 py-2 text-sm hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                            </svg>
                                            Edit Driver
                                        </button>
                                        <button onclick="openSendMessageModal({{ $driver->id }})" class="flex items-center w-full px-4 py-2 text-sm hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                            </svg>
                                            Send Message
                                        </button>
                                        <button onclick="removeDriver({{ $driver->id }})" class="flex items-center w-full px-4 py-2 text-sm hover:bg-gray-100 text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg>
                                            Remove Driver
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 md:p-6 pt-0 space-y-3">
                        <div class="flex items-center justify-between">
                            @php
                                $statusClass = match($driver->status) {
                                    'available' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'on_route' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'off_duty' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                    'maintenance' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-primary/80 {{ $statusClass }} hover:text-white">
                                <span class="ml-1">{{ ucfirst(str_replace('_', ' ', $driver->status)) }}</span>
                            </span>
                            <div class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star h-4 w-4 fill-yellow-400 text-yellow-400">
                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ number_format($driver->rating, 1) }}</span>
                            </div>
                        </div> 
                        <div class="space-y-2 text-sm">
                           
<div class="flex items-center space-x-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car h-4 w-4 text-muted-foreground">
        <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path>
        <circle cx="7" cy="17" r="2"></circle>
        <path d="M9 17h6"></path>
        <circle cx="17" cy="17" r="2"></circle>
    </svg>
    <span>
        @if($driver->assignedVehicle)
            {{ $driver->assignedVehicle->vehicle_number }}
            @if($driver->assignedVehicle->vehicle_name)
                - {{ $driver->assignedVehicle->vehicle_name }}
            @endif
        @else
            No Vehicle
        @endif
    </span>
</div>
                            <div class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4 text-muted-foreground">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span>{{ $driver->city ?? 'Unknown' }}, {{ $driver->state ?? '' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4 text-muted-foreground">
                                    <path d="M12 6v6l4 2"></path>
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                                <span>{{ $driver->last_location_update ? $driver->last_location_update->diffForHumans() : 'Never' }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2 text-sm">
                            <div>
                                <span class="text-muted-foreground">Deliveries:</span>
                                <div class="font-medium">{{ $driver->total_deliveries }}</div>
                            </div>
                            <div>
                                <span class="text-muted-foreground">On-time:</span>
                                <div class="font-medium">{{ $driver->total_deliveries > 0 ? number_format(($driver->successful_deliveries / $driver->total_deliveries) * 100, 1) : 0 }}%</div>
                            </div>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button onclick="openAssignDriverModal({{ $driver->id }})" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3 flex-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-route mr-2 h-4 w-4">
                                    <circle cx="6" cy="19" r="3"></circle>
                                    <path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"></path>
                                    <circle cx="18" cy="5" r="3"></circle>
                                </svg>
                                Assign
                            </button>
                            <button onclick="viewDriverDetails({{ $driver->id }})" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye mr-2 h-4 w-4">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                Details
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Assignments Tab -->
        <div id="content-assignments" class="tab-content hidden space-y-4">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Current Assignments</h3>
                    <div class="text-sm text-muted-foreground">View and manage current driver-vehicle-route assignments</div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="space-y-4">
                        @forelse($assignments as $assignment)
                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-accent/50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <span class="relative flex h-12 w-12 shrink-0 overflow-hidden rounded-full">
                                    @if($assignment->driver->profile_photo)
                                        <img alt="{{ $assignment->driver->first_name }}" src="{{ asset('storage/' . $assignment->driver->profile_photo) }}" class="w-full h-full object-cover"/>
                                    @else
                                        <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ substr($assignment->driver->first_name, 0, 1) }}{{ substr($assignment->driver->last_name, 0, 1) }}</span>
                                    @endif
                                </span>
                                <div>
                                    <h4 class="font-semibold">{{ $assignment->driver->first_name }} {{ $assignment->driver->last_name }}</h4>
                                    <p class="text-sm text-muted-foreground">{{ $assignment->driver->employee_id }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-6">
                                <div class="text-center">
                                    <p class="text-sm text-muted-foreground">Vehicle</p>
                                    <p class="font-medium">{{ $assignment->vehicle->vehicle_number }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm text-muted-foreground">Route</p>
                                    <p class="font-medium">{{ $assignment->route_name ?? 'N/A' }}</p>
                                </div>
                                @php
                                    $statusBadge = match($assignment->status) {
                                        'available' => 'bg-green-100 text-green-800',
                                        'on_route' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                </span>
                                <button onclick="reassignDriver({{ $assignment->id }})" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                                    Reassign
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-muted-foreground">
                            No active assignments found
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Tab -->
        <div id="content-performance" class="tab-content hidden space-y-4">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($drivers as $driver)
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <span class="relative flex h-12 w-12 shrink-0 overflow-hidden rounded-full">
                            @if($driver->profile_photo)
                                <img alt="{{ $driver->first_name }}" src="{{ asset('storage/' . $driver->profile_photo) }}" class="w-full h-full object-cover"/>
                            @else
                                <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}</span>
                            @endif
                        </span>
                        <div>
                            <h4 class="font-semibold text-lg">{{ $driver->first_name }} {{ $driver->last_name }}</h4>
                            <p class="text-sm text-muted-foreground">{{ $driver->experience_years ?? 0 }} years experience</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-2xl font-bold text-green-600">{{ $driver->total_deliveries > 0 ? number_format(($driver->successful_deliveries / $driver->total_deliveries) * 100, 1) : 0 }}%</p>
                                <p class="text-sm text-muted-foreground">On-time Rate</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold">{{ number_format($driver->rating, 1) }}</p>
                                <p class="text-sm text-muted-foreground">Rating</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Total Deliveries</span>
                                <span class="font-medium">{{ $driver->total_deliveries }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Weekly Hours</span>
                                <span class="font-medium">{{ $driver->weekly_hours ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Monthly Earnings</span>
                                <span class="font-medium">${{ number_format($driver->monthly_earnings ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button onclick="viewDriverReport({{ $driver->id }})" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3 flex-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                                    <path d="M3 3v18h18"></path>
                                    <path d="m19 9-5 5-4-4-3 3"></path>
                                </svg>
                                View Report
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Add Driver Modal -->
<div id="addDriverModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto m-4">
        <div class="flex items-center justify-between p-6 border-b">
            <div>
                <h2 class="text-2xl font-bold">Add New Driver</h2>
                <p class="text-sm text-muted-foreground">Enter the driver's information to add them to the fleet.</p>
            </div>
            <button onclick="closeAddDriverModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.drivers.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">First Name *</label>
                        <input type="text" name="first_name" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="John"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Last Name *</label>
                        <input type="text" name="last_name" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Smith"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Email *</label>
                        <input type="email" name="email" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="john.smith@example.com"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Phone *</label>
                        <input type="tel" name="phone" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="+1 (555) 123-4567"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Date of Birth *</label>
                        <input type="date" name="date_of_birth" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Gender</label>
                        <select name="gender" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Profile Photo</label>
                        <input type="file" name="profile_photo" accept="image/*" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Address Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Address</label>
                        <textarea name="address" rows="2" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Street address"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">City</label>
                        <input type="text" name="city" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="City"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">State</label>
                        <input type="text" name="state" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="State"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Country</label>
                        <input type="text" name="country" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Country"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Postal Code</label>
                        <input type="text" name="postal_code" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Postal Code"/>
                    </div>
                </div>
            </div>

            <!-- License & Professional Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4">License & Professional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">License Number *</label>
                        <input type="text" name="license_number" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="DL123456789"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">License Expiry *</label>
                        <input type="date" name="license_expiry" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Driver License Document *</label>
                        <input type="file" name="driver_license" required accept=".pdf,.jpg,.jpeg,.png" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Experience (Years) *</label>
                        <input type="number" name="experience_years" required min="0" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="5"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Medical Certificate</label>
                        <input type="file" name="medical_certificate" accept=".pdf,.jpg,.jpeg,.png" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Specializations</label>
                        <input type="text" name="specializations" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Hazmat, Long Distance, etc."/>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Vehicle Information (Optional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Vehicle Type</label>
                        <select name="vehicle_type" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="">Select Vehicle Type</option>
                            <option value="truck">Truck</option>
                            <option value="van">Van</option>
                            <option value="bike">Bike</option>
                            <option value="car">Car</option>
                            <option value="bicycle">Bicycle</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Vehicle Number</label>
                        <input type="text" name="vehicle_number" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="TRK-001"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Vehicle Capacity (kg)</label>
                        <input type="number" name="vehicle_capacity" step="0.01" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="1000"/>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Emergency Contact</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Jane Doe"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Emergency Contact Phone</label>
                        <input type="tel" name="emergency_contact_phone" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="+1 (555) 987-6543"/>
                    </div>
                </div>
            </div>

            <!-- Identity Documents -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Identity Documents</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">ID Proof Type</label>
                        <select name="id_proof_type" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="">Select ID Type</option>
                            <option value="passport">Passport</option>
                            <option value="national_id">National ID</option>
                            <option value="ssn">SSN</option>
                            <option value="drivers_license">Driver's License</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">ID Proof Number</label>
                        <input type="text" name="id_proof_number" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="ID Number"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">ID Proof Document</label>
                        <input type="file" name="id_proof_document" accept=".pdf,.jpg,.jpeg,.png" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Address Proof Document</label>
                        <input type="file" name="address_proof_document" accept=".pdf,.jpg,.jpeg,.png" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Employment Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Employee ID</label>
                        <input type="text" name="employee_id" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="EMP001"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Joining Date</label>
                        <input type="date" name="joining_date" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Salary</label>
                        <input type="number" name="salary" step="0.01" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="5000"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Commission Rate (%)</label>
                        <input type="number" name="commission_rate" step="0.01" min="0" max="100" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="10"/>
                    </div>
                </div>
            </div>

            <!-- Bank Details -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Bank Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Bank Name</label>
                        <input type="text" name="bank_name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Bank Name"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Account Number</label>
                        <input type="text" name="account_number" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Account Number"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Account Holder Name</label>
                        <input type="text" name="account_holder_name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Account Holder Name"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">IFSC/Routing Code</label>
                        <input type="text" name="ifsc_code" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="IFSC/Routing Code"/>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeAddDriverModal()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-4">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 rounded-md px-4">
                    Add Driver
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Driver Modal -->
<div id="editDriverModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto m-4">
        <div class="flex items-center justify-between p-6 border-b">
            <div>
                <h2 class="text-2xl font-bold">Edit Driver</h2>
                <p class="text-sm text-muted-foreground">Update driver information</p>
            </div>
            <button onclick="closeEditDriverModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div id="editDriverForm" class="p-6">
            <!-- Form will be loaded dynamically -->
        </div>
    </div>
</div>

<!-- Driver Details Modal -->
<div id="driverDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto m-4">
        <div class="flex items-center justify-between p-6 border-b">
            <div>
                <h2 class="text-2xl font-bold">Driver Details</h2>
                <p id="driverDetailsSubtitle" class="text-sm text-muted-foreground">Comprehensive information</p>
            </div>
            <button onclick="closeDriverDetailsModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div id="driverDetailsContent" class="p-6">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>

<!-- Assign Driver Modal -->
<div id="assignDriverModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full m-4">
        <div class="flex items-center justify-between p-6 border-b">
            <div>
                <h2 class="text-2xl font-bold">Assign Driver</h2>
                <p id="assignDriverSubtitle" class="text-sm text-muted-foreground">Assign driver to a vehicle and route.</p>
            </div>
            <button onclick="closeAssignDriverModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="assignDriverForm" action="{{ route('admin.drivers.assign') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="assign_driver_id" name="driver_id">
            
            <!-- Replace the vehicle select in Assign Driver Modal -->
<div>
    <label class="block text-sm font-medium mb-2">Vehicle *</label>
    <select name="vehicle_id" id="assign_vehicle_id" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
        <option value="">Select a vehicle</option>
        @if(isset($vehicles) && count($vehicles) > 0)
            @foreach($vehicles as $vehicle)
            <option value="{{ $vehicle->id }}" 
                {{ $vehicle->assigned_driver_id ? 'disabled' : '' }}>
                {{ $vehicle->vehicle_number }} 
                - {{ $vehicle->vehicle_name ?? ucfirst($vehicle->vehicle_type) }}
                @if($vehicle->assigned_driver_id)
                    (Assigned)
                @endif
            </option>
            @endforeach
        @else
            <option value="" disabled>No vehicles available</option>
        @endif
    </select>
    <p class="text-xs text-muted-foreground mt-1">
        {{ isset($vehicles) ? count($vehicles) : 0 }} vehicle(s) available
    </p>
</div>

<!-- Add this debug section temporarily to check if vehicles are loaded -->
@if(config('app.debug'))
<script>
    console.log('Total vehicles loaded: {{ isset($vehicles) ? count($vehicles) : 0 }}');
    console.log('Vehicles data:', @json($vehicles ?? []));
</script>
@endif

            <div>
                <label class="block text-sm font-medium mb-2">Route</label>
                <input type="text" name="route_name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Route A-12"/>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Start Date</label>
                <input type="date" name="start_date" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Notes</label>
                <textarea name="notes" rows="3" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Additional notes for this assignment..."></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeAssignDriverModal()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-4">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 rounded-md px-4">
                    Assign Driver
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Send Message Modal -->
<div id="sendMessageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full m-4">
        <div class="flex items-center justify-between p-6 border-b">
            <div>
                <h2 class="text-2xl font-bold">Send Message</h2>
                <p id="sendMessageSubtitle" class="text-sm text-muted-foreground">Send a notification to the driver</p>
            </div>
            <button onclick="closeSendMessageModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="sendMessageForm" action="{{ route('admin.drivers.send-message') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="message_driver_id" name="driver_id">
            
            <div>
                <label class="block text-sm font-medium mb-2">Message Type</label>
                <select name="type" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="info">Info</option>
                    <option value="warning">Warning</option>
                    <option value="success">Success</option>
                    <option value="error">Error</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Delivery Channel</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="channels[]" value="system" checked class="mr-2">
                        <span class="text-sm">System Notification</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="channels[]" value="email" class="mr-2">
                        <span class="text-sm">Email</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="channels[]" value="sms" class="mr-2">
                        <span class="text-sm">SMS</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="channels[]" value="push" class="mr-2">
                        <span class="text-sm">Push Notification</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Title</label>
                <input type="text" name="title" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Message title"/>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Message</label>
                <textarea name="message" rows="5" required class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Type your message here..."></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeSendMessageModal()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-4">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 rounded-md px-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>









<script>
// Global variables
let currentDriverData = null;
let currentAssignmentId = null;

// Tab Switching
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('bg-background', 'text-foreground', 'shadow-sm');
    });
    
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
    document.getElementById(`tab-${tabName}`).classList.add('bg-background', 'text-foreground', 'shadow-sm');
}

// Export Dropdown
document.getElementById('exportDropdown').addEventListener('click', function() {
    document.getElementById('exportMenu').classList.toggle('hidden');
});

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('exportDropdown');
    const menu = document.getElementById('exportMenu');
    if (!dropdown.contains(event.target)) {
        menu.classList.add('hidden');
    }
});

// 3 Dots Menu Toggle
function toggleMenu(driverId) {
    const menu = document.getElementById(`menu-${driverId}`);
    
    document.querySelectorAll('[id^="menu-"]').forEach(m => {
        if (m.id !== `menu-${driverId}`) {
            m.classList.add('hidden');
        }
    });
    
    menu.classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('button')) {
        document.querySelectorAll('[id^="menu-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Search and Filter
document.getElementById('searchInput').addEventListener('input', function(e) {
    filterDrivers();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    filterDrivers();
});

function filterDrivers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const cards = document.querySelectorAll('.driver-card');
    
    cards.forEach(card => {
        const name = card.getAttribute('data-name');
        const status = card.getAttribute('data-status');
        
        const matchesSearch = name.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Add Driver Modal
function openAddDriverModal() {
    document.getElementById('addDriverModal').classList.remove('hidden');
    document.getElementById('addDriverModal').classList.add('flex');
}

function closeAddDriverModal() {
    document.getElementById('addDriverModal').classList.add('hidden');
    document.getElementById('addDriverModal').classList.remove('flex');
}

// Edit Driver Modal
function openEditDriverModal(driverId) {
    fetch(`/admin/drivers/${driverId}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editDriverForm').innerHTML = generateEditForm(data);
            document.getElementById('editDriverModal').classList.remove('hidden');
            document.getElementById('editDriverModal').classList.add('flex');
        })
        .catch(error => {
            console.error('Error loading driver data:', error);
            alert('Error loading driver data');
        });
}

function closeEditDriverModal() {
    document.getElementById('editDriverModal').classList.add('hidden');
    document.getElementById('editDriverModal').classList.remove('flex');
}

function generateEditForm(driver) {
    return `
        <form action="/admin/drivers/${driver.id}" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">First Name *</label>
                        <input type="text" name="first_name" value="${driver.first_name || ''}" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Last Name *</label>
                        <input type="text" name="last_name" value="${driver.last_name || ''}" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Email *</label>
                        <input type="email" name="email" value="${driver.email || ''}" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Phone *</label>
                        <input type="tel" name="phone" value="${driver.phone || ''}" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Status *</label>
                        <select name="status" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="active" ${driver.status === 'active' ? 'selected' : ''}>Active</option>
                            <option value="inactive" ${driver.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                            <option value="suspended" ${driver.status === 'suspended' ? 'selected' : ''}>Suspended</option>
                            <option value="on_leave" ${driver.status === 'on_leave' ? 'selected' : ''}>On Leave</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- License Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4">License Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">License Number *</label>
                        <input type="text" name="license_number" value="${driver.license_number || ''}" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">License Expiry</label>
                        <input type="date" name="license_expiry" value="${driver.license_expiry || ''}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Experience (Years)</label>
                        <input type="number" name="experience_years" value="${driver.experience_years || ''}" min="0" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Specializations</label>
                        <input type="text" name="specializations" value="${driver.specializations || ''}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeEditDriverModal()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-4">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 rounded-md px-4">
                    Save Changes
                </button>
            </div>
        </form>
    `;
}

// Driver Details Modal with Tabs
function viewDriverDetails(driverId) {
    console.log('Loading driver details for ID:', driverId);
    
    fetch(`/admin/drivers/${driverId}`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Driver data received:', data);
            
            if (data.error) {
                throw new Error(data.message || 'Unknown error');
            }
            
            currentDriverData = data;
            document.getElementById('driverDetailsSubtitle').textContent = `Comprehensive information about ${data.first_name} ${data.last_name}`;
            document.getElementById('driverDetailsContent').innerHTML = generateDetailsContent(data);
            document.getElementById('driverDetailsModal').classList.remove('hidden');
            document.getElementById('driverDetailsModal').classList.add('flex');
            
            setupDetailsModalTabs(driverId);
        })
        .catch(error => {
            console.error('Error loading driver details:', error);
            alert('Error loading driver details: ' + error.message);
        });
}

function closeDriverDetailsModal() {
    document.getElementById('driverDetailsModal').classList.add('hidden');
    document.getElementById('driverDetailsModal').classList.remove('flex');
    currentDriverData = null;
}

function setupDetailsModalTabs(driverId) {
    const tabButtons = document.querySelectorAll('.details-tab-button');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            switchDetailsTab(tabName, driverId);
        });
    });
}

function switchDetailsTab(tabName, driverId) {
    document.querySelectorAll('.details-tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.querySelectorAll('.details-tab-button').forEach(button => {
        button.classList.remove('border-primary', 'text-primary');
        button.classList.add('border-transparent');
    });
    
    const selectedContent = document.getElementById(`details-${tabName}`);
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
    
    const selectedButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (selectedButton) {
        selectedButton.classList.remove('border-transparent');
        selectedButton.classList.add('border-primary', 'text-primary');
    }
    
    if (tabName === 'performance') {
        loadPerformanceData(driverId);
    } else if (tabName === 'history') {
        loadHistoryData(driverId);
    }
}

function loadPerformanceData(driverId) {
    fetch(`/admin/drivers/${driverId}/performance`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('performance-content').innerHTML = generatePerformanceContent(data);
        })
        .catch(error => console.error('Error loading performance:', error));
}

function loadHistoryData(driverId) {
    fetch(`/admin/drivers/${driverId}/history`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('history-content').innerHTML = generateHistoryContent(data);
        })
        .catch(error => console.error('Error loading history:', error));
}

function generateDetailsContent(driver) {
    // FIXED: Safely handle all numeric values with proper parsing
    const rating = parseFloat(driver.rating) || 0;
    const totalDeliveries = parseInt(driver.total_deliveries) || 0;
    const successfulDeliveries = parseInt(driver.successful_deliveries) || 0;
    const failedDeliveries = parseInt(driver.failed_deliveries) || 0;
    const onTimeRate = totalDeliveries > 0 ? ((successfulDeliveries / totalDeliveries) * 100).toFixed(1) : '0';
    const weeklyHours = driver.weekly_hours || '0h';
    const monthlyEarnings = parseFloat(driver.monthly_earnings) || 0;
    
    return `
        <div class="flex items-center space-x-4 mb-6 pb-6 border-b">
            ${driver.profile_photo ? 
                `<img src="/storage/${driver.profile_photo}" alt="${driver.first_name}" class="w-20 h-20 rounded-full object-cover"/>` :
                `<div class="w-20 h-20 rounded-full bg-muted flex items-center justify-center text-2xl font-bold">${driver.first_name[0]}${driver.last_name[0]}</div>`
            }
            <div>
                <h3 class="text-2xl font-bold">${driver.first_name} ${driver.last_name}</h3>
                <p class="text-muted-foreground">${driver.employee_id}</p>
                <div class="flex items-center mt-2 space-x-4">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${getStatusBadgeClass(driver.status)}">
                        ${ucFirst(driver.status)}
                    </span>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-400 fill-yellow-400 mr-1">
                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                        </svg>
                        <span class="font-medium">${rating.toFixed(1)}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b mb-6">
            <div class="flex space-x-4">
                <button data-tab="overview" class="details-tab-button border-b-2 border-primary text-primary px-4 py-2 font-medium">Overview</button>
                <button data-tab="performance" class="details-tab-button border-b-2 border-transparent px-4 py-2 font-medium hover:text-primary">Performance</button>
                <button data-tab="documents" class="details-tab-button border-b-2 border-transparent px-4 py-2 font-medium hover:text-primary">Documents</button>
                <button data-tab="history" class="details-tab-button border-b-2 border-transparent px-4 py-2 font-medium hover:text-primary">History</button>
            </div>
        </div>

        <!-- Overview Tab -->
        <div id="details-overview" class="details-tab-content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold mb-3">Contact Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center"><span class="text-muted-foreground w-24">Email:</span><span>${driver.email || 'N/A'}</span></div>
                        <div class="flex items-center"><span class="text-muted-foreground w-24">Phone:</span><span>${driver.phone || 'N/A'}</span></div>
                        <div class="flex items-center"><span class="text-muted-foreground w-24">Address:</span><span>${driver.address || 'N/A'}</span></div>
                        <div class="flex items-center"><span class="text-muted-foreground w-24">Location:</span><span>${driver.city || 'N/A'}, ${driver.state || ''}</span></div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-3">Professional Details</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center"><span class="text-muted-foreground w-32">Experience:</span><span>${driver.experience_years || 0} years</span></div>
                        <div class="flex items-center"><span class="text-muted-foreground w-32">Join Date:</span><span>${driver.joining_date || 'N/A'}</span></div>
                        <div class="flex items-center"><span class="text-muted-foreground w-32">License:</span><span>${driver.license_number || 'N/A'}</span></div>
                        <div class="flex items-center"><span class="text-muted-foreground w-32">License Expiry:</span><span>${driver.license_expiry || 'N/A'}</span></div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t">
                <h4 class="font-semibold mb-3">Performance Metrics</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold">${totalDeliveries}</div>
                        <div class="text-sm text-muted-foreground">Total Deliveries</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">${onTimeRate}%</div>
                        <div class="text-sm text-muted-foreground">On-time Rate</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold">${successfulDeliveries}</div>
                        <div class="text-sm text-muted-foreground">Successful</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">${failedDeliveries}</div>
                        <div class="text-sm text-muted-foreground">Failed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Tab -->
        <div id="details-performance" class="details-tab-content hidden">
            <div id="performance-content">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
                    <p class="mt-4 text-muted-foreground">Loading performance data...</p>
                </div>
            </div>
        </div>

        <!-- Documents Tab -->
        <div id="details-documents" class="details-tab-content hidden">
            <div class="space-y-4">
                ${generateDocumentsSection(driver.documents, driver.id)}
            </div>
        </div>

        <!-- History Tab -->
        <div id="details-history" class="details-tab-content hidden">
            <div id="history-content">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
                    <p class="mt-4 text-muted-foreground">Loading history...</p>
                </div>
            </div>
        </div>
    `;
}

function generateDocumentsSection(documents, driverId) {
    let html = '';
    
    const docTypes = [
        { key: 'driver_license', name: 'Driver\'s License', icon: 'text-blue-500', expiry: true },
        { key: 'medical_certificate', name: 'Medical Certificate', icon: 'text-green-500', expiry: false },
        { key: 'id_proof_document', name: 'ID Proof Document', icon: 'text-purple-500', expiry: false },
        { key: 'address_proof_document', name: 'Address Proof Document', icon: 'text-orange-500', expiry: false }
    ];
    
    docTypes.forEach(docType => {
        if (documents[docType.key] && documents[docType.key].exists) {
            html += `
                <div class="flex items-center justify-between p-4 border rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="${docType.icon}">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <div>
                            <h4 class="font-medium">${docType.name}</h4>
                            ${docType.expiry && documents[docType.key].expiry ? 
                                `<p class="text-sm text-muted-foreground">Expires: ${documents[docType.key].expiry}</p>` : 
                                '<p class="text-sm text-muted-foreground">Document on file</p>'
                            }
                        </div>
                    </div>
                    <button onclick="viewDocument(${driverId}, '${docType.key}')" class="inline-flex items-center gap-2 px-4 py-2 border rounded-md hover:bg-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        View
                    </button>
                </div>
            `;
        }
    });
    
    if (!html) {
        html = '<div class="text-center py-8 text-muted-foreground"><p>No documents uploaded</p></div>';
    }
    
    return html;
}

function generatePerformanceContent(data) {
    // FIXED: Ensure rating is a number
    const rating = parseFloat(data.rating) || 0;
    const monthlyEarnings = parseFloat(data.monthly_earnings) || 0;
    
    return `
        <div class="space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg">
                    <div class="text-3xl font-bold text-green-600">${data.on_time_rate}%</div>
                    <div class="text-sm text-muted-foreground mt-1">On-time Rate</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600">${rating.toFixed(1)}</div>
                    <div class="text-sm text-muted-foreground mt-1">Average Rating</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg">
                    <div class="text-3xl font-bold text-purple-600">${data.weekly_hours}</div>
                    <div class="text-sm text-muted-foreground mt-1">Weekly Hours</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg">
                    <div class="text-3xl font-bold text-amber-600">$${monthlyEarnings.toLocaleString()}</div>
                    <div class="text-sm text-muted-foreground mt-1">Monthly Earnings</div>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold mb-3">Delivery Statistics</h4>
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 border rounded-lg">
                        <div class="text-2xl font-bold text-green-600">${data.successful_deliveries}</div>
                        <div class="text-sm text-muted-foreground">Successful</div>
                    </div>
                    <div class="p-4 border rounded-lg">
                        <div class="text-2xl font-bold text-red-600">${data.failed_deliveries}</div>
                        <div class="text-sm text-muted-foreground">Failed</div>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold mb-3">Monthly Performance Trend</h4>
                <div class="space-y-2">
                    ${data.monthly_performance.map(month => `
                        <div class="flex items-center justify-between p-3 border rounded">
                            <span class="font-medium">${month.month}</span>
                            <div class="flex items-center gap-4">
                                <span class="text-sm">${month.deliveries} deliveries</span>
                                <span class="text-sm font-medium text-green-600">${month.on_time_rate}% on-time</span>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>
    `;
}

function generateHistoryContent(data) {
    return `
        <div class="space-y-6">
            <div>
                <h4 class="font-semibold mb-3">Assignment History</h4>
                <div class="space-y-3">
                    ${data.assignments.length > 0 ? data.assignments.map(assignment => `
                        <div class="flex items-center justify-between p-4 border rounded-lg ${assignment.status === 'active' ? 'border-primary bg-primary/5' : ''}">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 ${assignment.status === 'active' ? 'bg-primary/10' : 'bg-gray-100'} rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path>
                                        <circle cx="7" cy="17" r="2"></circle>
                                        <path d="M9 17h6"></path>
                                        <circle cx="17" cy="17" r="2"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium">${assignment.vehicle_number} ${assignment.vehicle_name ? '- ' + assignment.vehicle_name : ''}</h5>
                                    <p class="text-sm text-muted-foreground">
                                        ${assignment.route_name ? 'Route: ' + assignment.route_name + ' • ' : ''}
                                        ${new Date(assignment.start_date).toLocaleDateString()}
                                        ${assignment.end_date ? ' - ' + new Date(assignment.end_date).toLocaleDateString() : ' - Present'}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${
                                assignment.status === 'active' ? 'bg-green-100 text-green-800' : 
                                assignment.status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                'bg-gray-100 text-gray-800'
                            }">
                                ${ucFirst(assignment.status)}
                            </span>
                        </div>
                    `).join('') : '<p class="text-center py-8 text-muted-foreground">No assignment history</p>'}
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold mb-3">Activity Log</h4>
                <div class="space-y-2">
                    ${data.activities.length > 0 ? data.activities.map(activity => `
                        <div class="flex items-start space-x-3 p-3 border rounded">
                            <div class="w-2 h-2 mt-2 rounded-full bg-primary"></div>
                            <div class="flex-1">
                                <p class="font-medium">${activity.description}</p>
                                <p class="text-sm text-muted-foreground">${new Date(activity.created_at).toLocaleString()}</p>
                            </div>
                        </div>
                    `).join('') : '<p class="text-center py-8 text-muted-foreground">No activity logs</p>'}
                </div>
            </div>
        </div>
    `;
}

// Document Viewing
function viewDocument(driverId, documentType) {
    fetch('/admin/drivers/view-document', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            driver_id: driverId,
            document_type: documentType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            openDocumentPreviewModal(data);
        } else {
            alert(data.message || 'Error loading document');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading document');
    });
}

function openDocumentPreviewModal(documentData) {
    const modal = document.createElement('div');
    modal.id = 'documentPreviewModal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    
    const isPDF = documentData.mime_type === 'application/pdf';
    const isImage = documentData.mime_type.startsWith('image/');
    
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between p-6 border-b">
                <h2 class="text-2xl font-bold">Document Preview</h2>
                <button onclick="closeDocumentPreview()" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-auto p-6">
                ${isPDF ? `
                    <iframe src="${documentData.url}" class="w-full h-[60vh] border rounded"></iframe>
                ` : isImage ? `
                    <img src="${documentData.url}" alt="Document" class="max-w-full h-auto mx-auto"/>
                ` : `
                    <div class="text-center py-8">
                        <p class="text-muted-foreground">Preview not available for this file type</p>
                        <p class="text-sm mt-2">Click download to view the file</p>
                    </div>
                `}
            </div>
            <div class="flex justify-end gap-3 p-6 border-t">
                <button onclick="closeDocumentPreview()" class="inline-flex items-center justify-center px-4 py-2 border rounded-md hover:bg-accent">
                    Close
                </button>
                <a href="${documentData.url}" download="${documentData.filename}" class="inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                        <path d="M12 15V3"></path>
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <path d="m7 10 5 5 5-5"></path>
                    </svg>
                    Download
                </a>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

function closeDocumentPreview() {
    const modal = document.getElementById('documentPreviewModal');
    if (modal) {
        modal.remove();
    }
}

// Reassignment Functionality
function reassignDriver(assignmentId) {
    currentAssignmentId = assignmentId;
    
    fetch(`/admin/drivers/assignment/${assignmentId}`)
        .then(response => response.json())
        .then(data => {
            openReassignModal(data);
        })
        .catch(error => {
            console.error('Error loading assignment:', error);
            openReassignModal({ id: assignmentId });
        });
}

function openReassignModal(assignmentData) {
    const modal = document.createElement('div');
    modal.id = 'reassignDriverModal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <div>
                    <h2 class="text-2xl font-bold">Reassign Driver</h2>
                    <p class="text-sm text-muted-foreground">Assign driver to a new vehicle and route</p>
                </div>
                <button onclick="closeReassignModal()" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="reassignForm" class="p-6 space-y-4">
                <input type="hidden" name="assignment_id" value="${assignmentData.id}">
                <input type="hidden" name="driver_id" value="${assignmentData.driver_id || ''}">
                
                <div>
                    <label class="block text-sm font-medium mb-2">New Vehicle *</label>
                    <select name="vehicle_id" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">Select a vehicle</option>
                        ${window.availableVehicles ? window.availableVehicles.map(v => 
                            `<option value="${v.id}">${v.vehicle_number} - ${v.vehicle_name || v.vehicle_type}</option>`
                        ).join('') : ''}
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">New Route</label>
                    <input type="text" name="route_name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Route name"/>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Start Date *</label>
                    <input type="date" name="start_date" required value="${new Date().toISOString().split('T')[0]}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"/>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Reason for reassignment..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeReassignModal()" class="inline-flex items-center justify-center px-4 py-2 border rounded-md hover:bg-accent">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">
                        Reassign Driver
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    document.getElementById('reassignForm').addEventListener('submit', handleReassignSubmit);
}

function closeReassignModal() {
    const modal = document.getElementById('reassignDriverModal');
    if (modal) {
        modal.remove();
    }
    currentAssignmentId = null;
}

function handleReassignSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    fetch('/admin/drivers/reassign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            closeReassignModal();
            location.reload();
        } else {
            alert(result.message || 'Error reassigning driver');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error reassigning driver');
    });
}

// Assign Driver Modal
function openAssignDriverModal(driverId) {
    document.getElementById('assign_driver_id').value = driverId;
    document.getElementById('assignDriverModal').classList.remove('hidden');
    document.getElementById('assignDriverModal').classList.add('flex');
}

function closeAssignDriverModal() {
    document.getElementById('assignDriverModal').classList.add('hidden');
    document.getElementById('assignDriverModal').classList.remove('flex');
}

// Send Message Modal
function openSendMessageModal(driverId) {
    document.getElementById('message_driver_id').value = driverId;
    document.getElementById('sendMessageModal').classList.remove('hidden');
    document.getElementById('sendMessageModal').classList.add('flex');
}

function closeSendMessageModal() {
    document.getElementById('sendMessageModal').classList.add('hidden');
    document.getElementById('sendMessageModal').classList.remove('flex');
}

// Remove Driver
function removeDriver(driverId) {
    if (confirm('Are you sure you want to remove this driver? This action cannot be undone.')) {
        fetch(`/admin/drivers/${driverId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error removing driver: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing driver');
        });
    }
}

// View Driver Report
function viewDriverReport(driverId) {
    window.location.href = `/admin/drivers/${driverId}/report`;
}

// Helper Functions
function getStatusBadgeClass(status) {
    const classes = {
        'active': 'bg-green-100 text-green-800',
        'inactive': 'bg-gray-100 text-gray-800',
        'suspended': 'bg-red-100 text-red-800',
        'on_leave': 'bg-yellow-100 text-yellow-800',
        'available': 'bg-green-100 text-green-800',
        'on_route': 'bg-blue-100 text-blue-800',
        'off_duty': 'bg-gray-100 text-gray-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function ucFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).replace(/_/g, ' ');
}

// Store available vehicles globally for reassignment
window.availableVehicles = @json($vehicles ?? []);
</script>


@endsection



