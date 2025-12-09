@extends('admin.admin_dashboard')
@section('admin') 


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
    .modal {
        display: none;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .modal.show {
        display: flex;
        opacity: 1;
    }
    .modal-content {
        transform: scale(0.95);
        transition: transform 0.3s;
    }
    .modal.show .modal-content {
        transform: scale(1);
    }
</style>

	   <div hidden id="S:0">
			<div>
				<!--$?-->
				<template id="B:1"></template>
				<div class="space-y-6">
					<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
						<div>
							<div class="animate-pulse rounded-md bg-muted h-8 w-48 mb-2"></div>
							<div class="animate-pulse rounded-md bg-muted h-4 w-96"></div>
						</div>
						<div class="flex items-center gap-2">
							<div class="animate-pulse rounded-md bg-muted h-9 w-20"></div>
							<div class="animate-pulse rounded-md bg-muted h-9 w-20"></div>
							<div class="animate-pulse rounded-md bg-muted h-9 w-32"></div>
						</div>
					</div>
					<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
						<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
							<div class="p-4 md:p-6 pt-6">
								<div class="flex items-center justify-between">
									<div class="space-y-2">
										<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
										<div class="animate-pulse rounded-md bg-muted h-8 w-16"></div>
									</div>
									<div class="animate-pulse bg-muted h-10 w-10 rounded-full"></div>
								</div>
								<div class="mt-4 space-y-2">
									<div class="animate-pulse rounded-md bg-muted h-2 w-full"></div>
									<div class="animate-pulse rounded-md bg-muted h-3 w-3/4"></div>
								</div>
							</div>
						</div>
						<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
							<div class="p-4 md:p-6 pt-6">
								<div class="flex items-center justify-between">
									<div class="space-y-2">
										<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
										<div class="animate-pulse rounded-md bg-muted h-8 w-16"></div>
									</div>
									<div class="animate-pulse bg-muted h-10 w-10 rounded-full"></div>
								</div>
								<div class="mt-4 space-y-2">
									<div class="animate-pulse rounded-md bg-muted h-2 w-full"></div>
									<div class="animate-pulse rounded-md bg-muted h-3 w-3/4"></div>
								</div>
							</div>
						</div>
						<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
							<div class="p-4 md:p-6 pt-6">
								<div class="flex items-center justify-between">
									<div class="space-y-2">
										<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
										<div class="animate-pulse rounded-md bg-muted h-8 w-16"></div>
									</div>
									<div class="animate-pulse bg-muted h-10 w-10 rounded-full"></div>
								</div>
								<div class="mt-4 space-y-2">
									<div class="animate-pulse rounded-md bg-muted h-2 w-full"></div>
									<div class="animate-pulse rounded-md bg-muted h-3 w-3/4"></div>
								</div>
							</div>
						</div>
						<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
							<div class="p-4 md:p-6 pt-6">
								<div class="flex items-center justify-between">
									<div class="space-y-2">
										<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
										<div class="animate-pulse rounded-md bg-muted h-8 w-16"></div>
									</div>
									<div class="animate-pulse bg-muted h-10 w-10 rounded-full"></div>
								</div>
								<div class="mt-4 space-y-2">
									<div class="animate-pulse rounded-md bg-muted h-2 w-full"></div>
									<div class="animate-pulse rounded-md bg-muted h-3 w-3/4"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
						<div class="md:p-6 p-4">
							<div class="flex flex-col md:flex-row gap-4">
								<div class="animate-pulse rounded-md bg-muted h-10 flex-1"></div>
								<div class="flex flex-col sm:flex-row gap-2 md:w-auto">
									<div class="animate-pulse rounded-md bg-muted h-10 w-40"></div>
									<div class="animate-pulse rounded-md bg-muted h-10 w-40"></div>
									<div class="animate-pulse rounded-md bg-muted h-10 w-40"></div>
								</div>
								<div class="animate-pulse rounded-md bg-muted h-10 w-10"></div>
							</div>
						</div>
					</div>
					<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
						<div class="flex flex-col space-y-1.5 p-4 md:p-6">
							<div class="flex justify-between items-center">
								<div>
									<div class="animate-pulse rounded-md bg-muted h-6 w-32 mb-2"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-48"></div>
								</div>
								<div class="animate-pulse rounded-md bg-muted h-10 w-32"></div>
							</div>
						</div>
						<div class="p-4 md:p-6 pt-0">
							<div class="space-y-4">
								<div class="flex items-center space-x-4 p-4 border rounded-lg">
									<div class="animate-pulse rounded-md bg-muted h-4 w-4"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-28"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-8 w-8"></div>
								</div>
								<div class="flex items-center space-x-4 p-4 border rounded-lg">
									<div class="animate-pulse rounded-md bg-muted h-4 w-4"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-28"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-8 w-8"></div>
								</div>
								<div class="flex items-center space-x-4 p-4 border rounded-lg">
									<div class="animate-pulse rounded-md bg-muted h-4 w-4"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-28"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-8 w-8"></div>
								</div>
								<div class="flex items-center space-x-4 p-4 border rounded-lg">
									<div class="animate-pulse rounded-md bg-muted h-4 w-4"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-28"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-8 w-8"></div>
								</div>
								<div class="flex items-center space-x-4 p-4 border rounded-lg">
									<div class="animate-pulse rounded-md bg-muted h-4 w-4"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-28"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-8 w-8"></div>
								</div>
								<div class="flex items-center space-x-4 p-4 border rounded-lg">
									<div class="animate-pulse rounded-md bg-muted h-4 w-4"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-24"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-28"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-32"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-20"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-4 w-16"></div>
									<div class="animate-pulse rounded-md bg-muted h-8 w-8"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/$-->
			</div>
		</div>





<div class="p-6 space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Vehicle List</h1>
            <p class="text-gray-600">Manage and monitor your entire fleet with comprehensive vehicle information</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button id="refreshBtn" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M21 3v5h-5"></path>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                    <path d="M8 16H3v5"></path>
                </svg>
                Refresh
            </button>
            
            <!-- Export Dropdown -->
            <div class="relative">
                <button id="exportBtn" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
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
                <div id="exportDropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                    <div class="py-1">
                        <a href="{{ route('admin.vehicles.export', 'csv') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Export as CSV
                        </a>
                        <a href="{{ route('admin.vehicles.export', 'excel') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Export as Excel
                        </a>
                        <a href="{{ route('admin.vehicles.export', 'pdf') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Export as PDF
                        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <a href="javascript:window.print()" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                            Print
                        </a>
                    </div>
                </div>
            </div>
            
            
            <a href="{{ route('admin.vehicles.create') }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-3">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
        <path d="M5 12h14"></path>
        <path d="M12 5v14"></path>
    </svg>
    Add Vehicle
</a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-blue-600">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-500 mr-1">
                            <path d="M12 6v6l4 2"></path>
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        Inactive
                    </span>
                    <span class="font-medium">{{ $stats['inactive'] }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-500 mr-1">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m4.9 4.9 14.2 14.2"></path>
                        </svg>
                        Repair
                    </span>
                    <span class="font-medium">{{ $stats['repair'] }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Fleet Utilization</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['avg_utilization'], 0) }}%</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-600">
                        <line x1="12" x2="12" y1="20" y2="10"></line>
                        <line x1="18" x2="18" y1="20" y2="4"></line>
                        <line x1="6" x2="6" y1="20" y2="16"></line>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="relative w-full overflow-hidden rounded-full bg-gray-200 h-2">
                    <div class="h-full bg-blue-600 transition-all" style="width: {{ $stats['avg_utilization'] }}%"></div>
                </div>
                <p class="mt-2 text-sm text-gray-600">Average capacity utilization across all active vehicles</p>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Active Alerts</p>
                    <p class="text-2xl font-bold">{{ $stats['total_alerts'] }}</p>
                </div>
                <div class="p-2 bg-red-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-red-600">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600">Maintenance due, and other vehicle alerts requiring attention</p>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Maintenance Due</p>
                    <p class="text-2xl font-bold">{{ $stats['maintenance_due'] }}</p>
                </div>
                <div class="p-2 bg-yellow-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-yellow-600">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600">Vehicles requiring scheduled maintenance within the next 30 days</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border bg-white shadow-sm p-4 md:p-6">
        <form id="filterForm" method="GET" action="{{ route('admin.vehicles.index') }}">
            <div class="flex flex-col xl:flex-row gap-4">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500">
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                    <input type="search" name="search" id="searchInput" value="{{ request('search') }}" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by vehicle ID, make, model, driver, or license plate..."/>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <!-- Type Filter -->
                    <select name="type" id="typeFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm w-full sm:w-[160px] hover:bg-gray-50">
                        <option value="all">All Types</option>
                        <option value="truck" {{ request('type') == 'truck' ? 'selected' : '' }}>Truck</option>
                        <option value="van" {{ request('type') == 'van' ? 'selected' : '' }}>Van</option>
                        <option value="car" {{ request('type') == 'car' ? 'selected' : '' }}>Car</option>
                        <option value="bike" {{ request('type') == 'bike' ? 'selected' : '' }}>Bike</option>
                        <option value="bicycle" {{ request('type') == 'bicycle' ? 'selected' : '' }}>Bicycle</option>
                    </select>

                    <!-- Status Filter -->
                    <select name="status" id="statusFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm w-full sm:w-[160px] hover:bg-gray-50">
                        <option value="all">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="repair" {{ request('status') == 'repair' ? 'selected' : '' }}>Repair</option>
                    </select>

                    <!-- Branch Filter -->
                    <select name="warehouse_id" id="branchFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm w-full sm:w-[160px] hover:bg-gray-50">
                        <option value="">All Warehouses</option>
                        @foreach($warehouse as $ware)
                        <option value="{{ $ware->id }}" {{ request('warehouse_id') == $ware->id ? 'selected' : '' }}>{{ $ware->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Vehicle Table -->
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-4 md:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h3 class="text-xl sm:text-2xl font-semibold">Fleet Vehicles</h3>
                <div class="text-sm text-gray-600">{{ $vehicles->total() }} vehicles found</div>
            </div>
            <div class="flex items-center gap-2">
                <button id="bulkDeleteBtn" class="hidden inline-flex items-center justify-center text-sm font-medium border border-red-300 text-red-600 hover:bg-red-50 h-9 rounded-md px-3">
                    Delete Selected
                </button>
            </div>
        </div>
        <div class="p-4 md:p-6 pt-0">
            <div class="rounded-md border overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="h-12 px-4 text-left w-[40px]">
                                <input type="checkbox" id="selectAll" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                            </th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 w-[100px]">ID</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 w-[140px]">Type/Model</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 w-[100px]">Status</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 hidden md:table-cell">Driver</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 hidden lg:table-cell">Location</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 hidden lg:table-cell">Mileage</th>
                           <!-- <th class="h-12 px-4 text-left font-medium text-gray-600 hidden md:table-cell">Fuel</th>-->
                            <th class="h-12 px-4 text-left font-medium text-gray-600 hidden lg:table-cell">Utilization</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 w-[60px]"></th>
                        </tr>
                    </thead>
                    <tbody id="vehicleTableBody">
                        @forelse($vehicles as $vehicle)
                        <tr class="border-b hover:bg-gray-50" data-vehicle-id="{{ $vehicle->id }}">
                            <td class="p-4">
                                <input type="checkbox" class="vehicle-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $vehicle->id }}"/>
                            </td>
                            <td class="p-4 font-medium">{{ $vehicle->vehicle_number }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                        <path d="M15 18H9"></path>
                                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                        <circle cx="17" cy="18" r="2"></circle>
                                        <circle cx="7" cy="18" r="2"></circle>
                                    </svg>
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $vehicle->make ?? 'N/A' }}</span>
                                        <span class="text-xs text-gray-500">{{ $vehicle->model ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-gray-100 text-gray-800',
                                        'maintenance' => 'bg-yellow-100 text-yellow-800',
                                        'repair' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold flex items-center gap-1 {{ $statusColors[$vehicle->status] ?? 'bg-gray-100 text-gray-800' }} w-fit">
                                    {{ ucfirst($vehicle->status) }}
                                </span>
                            </td>
                            <td class="p-4 hidden md:table-cell">
                                {{ $vehicle->assignedDriver ? $vehicle->assignedDriver->first_name . ' ' . $vehicle->assignedDriver->last_name : 'N/A' }}
                            </td>
                            <td class="p-4 hidden lg:table-cell">{{ $vehicle->current_location ?? 'N/A' }}</td>
                            <td class="p-4 hidden lg:table-cell">{{ number_format($vehicle->mileage, 0) }} mi</td>
                            <!--<td class="p-4 hidden md:table-cell">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-16 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full {{ $vehicle->current_fuel_level > 60 ? 'bg-green-500' : ($vehicle->current_fuel_level > 30 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width:{{ $vehicle->current_fuel_level ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-xs">{{ $vehicle->current_fuel_level ?? 0 }}%</span>
                                </div>
                            </td>-->
                            <td class="p-4 hidden lg:table-cell">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-16 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-600" style="width:{{ $vehicle->utilization_percentage ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-xs">{{ $vehicle->utilization_percentage ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    @if($vehicle->alert_count > 0)
                                    <span class="rounded-full bg-red-500 text-white px-2 py-0.5 text-xs font-semibold min-w-[20px] text-center">{{ $vehicle->alert_count }}</span>
                                    @endif
                                    <div class="relative">
                                        <button class="action-menu-btn hover:bg-gray-100 rounded-md p-2" data-vehicle-id="{{ $vehicle->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </button>
                                        <div class="action-dropdown dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                            <div class="py-1">
                                               
                                                    
                                           
                                                <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    View Details
                                                </a>
                                                
                                              <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
    </svg>
    Edit Vehicle
</a>
                                                <button class="assign-driver-btn flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-vehicle-id="{{ $vehicle->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    Assign Driver
                                                </button>

                                                <button class="schedule-maintenance-btn flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-vehicle-id="{{ $vehicle->id }}" data-vehicle-number="{{ $vehicle->vehicle_number }}" data-vehicle-info="{{ $vehicle->vehicle_type }} {{ $vehicle->model }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
        <line x1="16" y1="2" x2="16" y2="6"></line>
        <line x1="8" y1="2" x2="8" y2="6"></line>
        <line x1="3" y1="10" x2="21" y2="10"></line>
    </svg>
    Schedule Maintenance
</button>
                                                <div class="border-t border-gray-200 my-1"></div>
                                                <button class="delete-vehicle-btn flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left" data-vehicle-id="{{ $vehicle->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 6h18"></path>
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                    </svg>
                                                    Remove Vehicle
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="p-8 text-center text-gray-500">
                                No vehicles found. Try adjusting your filters or add a new vehicle.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($vehicles->hasPages())
            <div class="mt-4">
                {{ $vehicles->links() }}
            </div>
            @endif
        </div>
    </div>

</div>


<div id="scheduleMaintenanceModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Schedule Maintenance</h2>
                <p id="scheduleVehicleInfo" class="text-sm text-gray-600 mt-1">Schedule maintenance for selected vehicle</p>
            </div>
            <button id="closeScheduleModal" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="scheduleMaintenanceForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="schedule_vehicle_id" name="vehicle_id"/>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Service Type <span class="text-red-500">*</span></label>
                    <select name="maintenance_type" required class="w-full h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                    <input type="text" name="category" class="w-full h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Engine, Brakes"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Scheduled Date <span class="text-red-500">*</span></label>
                    <input type="date" name="maintenance_date" required class="w-full h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description <span class="text-red-500">*</span></label>
                <textarea name="description" required rows="3" class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe the maintenance work needed..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Service Provider</label>
                    <input type="text" name="vendor_name" class="w-full h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Provider name"/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Technician Name</label>
                    <input type="text" name="technician_name" class="w-full h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Technician name"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Estimated Cost ($) <span class="text-red-500">*</span></label>
                    <input type="number" name="cost" required step="0.01" min="0" class="w-full h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00"/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Current Mileage</label>
                    <input type="number" name="mileage_at_maintenance" class="w-full h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Current mileage"/>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Additional Notes</label>
                <textarea name="notes" rows="3" class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional information..."></textarea>
            </div>

            <input type="hidden" name="status" value="scheduled"/>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" id="cancelScheduleBtn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Schedule Maintenance
                </button>
            </div>
        </form>
    </div>
</div>


<!-- View Details Modal -->
<div id="viewDetailsModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <path d="M15 18H9"></path>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
                <div>
                    <h2 class="text-2xl font-bold">Vehicle Details - <span id="detailsVehicleNumber"></span></h2>
                    <p class="text-sm text-gray-600 mt-1" id="detailsVehicleInfo"></p>
                </div>
            </div>
            <button id="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="p-6" id="vehicleDetailsContent">
            <!-- Content loaded dynamically -->
        </div>
        <div class="p-6 border-t flex justify-end gap-3">
            <button id="closeDetailsBtn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Close</button>
            <button id="editFromDetailsBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                </svg>
                Edit Vehicle
            </button>
        </div>
    </div>
</div>



<script>
    // CSRF Token setup for AJAX requests
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
    const vehicleCheckboxes = document.querySelectorAll('.vehicle-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            vehicleCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkActions();
        });
    }

    vehicleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(vehicleCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(vehicleCheckboxes).some(cb => cb.checked);
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
            toggleBulkActions();
        });
    });

    function toggleBulkActions() {
        const anyChecked = Array.from(vehicleCheckboxes).some(cb => cb.checked);
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
            const selectedIds = Array.from(vehicleCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedIds.length === 0) return;
            
            if (confirm(`Are you sure you want to delete ${selectedIds.length} vehicle(s)?`)) {
                fetch('{{ route("admin.vehicles.bulk.delete") }}', {
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
                    }
                });
            }
        });
    }

    // View Details Modal
    let currentDetailsVehicleId = null;

    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const vehicleId = this.dataset.vehicleId;
            currentDetailsVehicleId = vehicleId;
            loadVehicleDetails(vehicleId);
            openModal('viewDetailsModal');
            document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
        });
    });

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    function loadVehicleDetails(vehicleId) {
        fetch(`/admin/vehicles/${vehicleId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const vehicle = data.vehicle;
            document.getElementById('detailsVehicleNumber').textContent = vehicle.vehicle_number;
            document.getElementById('detailsVehicleInfo').textContent = `${vehicle.make || 'N/A'} ${vehicle.model || 'N/A'}`;
            
            const statusColors = {
                'active': 'bg-green-100 text-green-800',
                'inactive': 'bg-gray-100 text-gray-800',
                'maintenance': 'bg-yellow-100 text-yellow-800',
                'repair': 'bg-red-100 text-red-800'
            };
            
            const content = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Vehicle Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Vehicle ID:</span>
                                <span class="font-medium">${vehicle.vehicle_number}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Type:</span>
                                <span class="font-medium">${vehicle.vehicle_type || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Make/Model:</span>
                                <span class="font-medium">${vehicle.make || 'N/A'} ${vehicle.model || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Year:</span>
                                <span class="font-medium">${vehicle.year || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">VIN:</span>
                                <span class="font-medium">${vehicle.vin || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">License Plate:</span>
                                <span class="font-medium">${vehicle.license_plate || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusColors[vehicle.status] || 'bg-gray-100 text-gray-800'}">${vehicle.status}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Operational Status</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mileage:</span>
                                <span class="font-medium">${vehicle.mileage || 0} km</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Fuel Level:</span>
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-24 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-${vehicle.current_fuel_level > 60 ? 'green' : (vehicle.current_fuel_level > 30 ? 'yellow' : 'red')}-500" style="width:${vehicle.current_fuel_level || 0}%"></div>
                                    </div>
                                    <span class="font-medium">${vehicle.current_fuel_level || 0}%</span>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Insurance Expiry:</span>
                                <span class="font-medium">${vehicle.insurance_expiry || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Registration Expiry:</span>
                                <span class="font-medium">${vehicle.registration_expiry || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Assignment & Location</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Driver:</span>
                                <span class="font-medium">${vehicle.assigned_driver ? vehicle.assigned_driver.first_name + ' ' + vehicle.assigned_driver.last_name : 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Warehouse:</span>
                                <span class="font-medium">${vehicle.warehouse ? vehicle.warehouse.name : 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Branch:</span>
                                <span class="font-medium">${vehicle.branch ? vehicle.branch.name : 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Current Location:</span>
                                <span class="font-medium">${vehicle.current_location || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Capacity Weight:</span>
                                <span class="font-medium">${vehicle.capacity_weight || 0} kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Current Load:</span>
                                <span class="font-medium">${vehicle.current_load || 0} kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Utilization:</span>
                                <span class="font-medium">${vehicle.utilization_percentage || 0}%</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Alerts</h3>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Active Alerts: <span class="font-semibold">${vehicle.alert_count || 0}</span></p>
                        </div>
                    </div>
                </div>

                ${vehicle.notes ? `
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold mb-3">Notes</h3>
                    <div class="bg-gray-50 rounded-md p-4">
                        <p class="text-gray-700">${vehicle.notes}</p>
                    </div>
                </div>
                ` : ''}
            `;
            
            document.getElementById('vehicleDetailsContent').innerHTML = content;
        });
    }

    const closeDetailsModal = document.getElementById('closeDetailsModal');
    const closeDetailsBtn = document.getElementById('closeDetailsBtn');
    const editFromDetailsBtn = document.getElementById('editFromDetailsBtn');

    if (closeDetailsModal) {
        closeDetailsModal.addEventListener('click', () => closeModal('viewDetailsModal'));
    }
    
    if (closeDetailsBtn) {
        closeDetailsBtn.addEventListener('click', () => closeModal('viewDetailsModal'));
    }
    
    if (editFromDetailsBtn) {
        editFromDetailsBtn.addEventListener('click', () => {
            if (currentDetailsVehicleId) {
                window.location.href = `/admin/vehicles/${currentDetailsVehicleId}/edit`;
            }
        });
    }

    // Delete Vehicle
    document.querySelectorAll('.delete-vehicle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const vehicleId = this.dataset.vehicleId;
            
            if (confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')) {
                fetch(`/admin/vehicles/${vehicleId}`, {
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
                        alert('Error: ' + (data.message || 'Failed to delete vehicle'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the vehicle');
                });
            }
            
            document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
        });
    });

    // Close modals when clicking outside
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
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
    document.querySelectorAll('#typeFilter, #statusFilter, #branchFilter').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Search with debounce
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        });
    }
</script>









<script>
// Schedule Maintenance Modal handlers
document.querySelectorAll('.schedule-maintenance-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const vehicleId = this.dataset.vehicleId;
        const vehicleNumber = this.dataset.vehicleNumber;
        const vehicleInfo = this.dataset.vehicleInfo;
        
        document.getElementById('schedule_vehicle_id').value = vehicleId;
        document.getElementById('scheduleVehicleInfo').textContent = `Schedule maintenance for ${vehicleNumber} - ${vehicleInfo}`;
        
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('#scheduleMaintenanceForm input[name="maintenance_date"]').setAttribute('min', today);
        
        openModal('scheduleMaintenanceModal');
        document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
    });
});

const closeScheduleModal = document.getElementById('closeScheduleModal');
const cancelScheduleBtn = document.getElementById('cancelScheduleBtn');

if (closeScheduleModal) {
    closeScheduleModal.addEventListener('click', () => {
        closeModal('scheduleMaintenanceModal');
        document.getElementById('scheduleMaintenanceForm').reset();
    });
}

if (cancelScheduleBtn) {
    cancelScheduleBtn.addEventListener('click', () => {
        closeModal('scheduleMaintenanceModal');
        document.getElementById('scheduleMaintenanceForm').reset();
    });
}

// Handle schedule maintenance form submission
const scheduleMaintenanceForm = document.getElementById('scheduleMaintenanceForm');
if (scheduleMaintenanceForm) {
    scheduleMaintenanceForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        
        // Disable submit button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Scheduling...';
        
        try {
            const response = await fetch('{{ route("admin.maintenance.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50';
                successDiv.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span class="font-medium">Maintenance scheduled successfully!</span>
                    </div>
                `;
                document.body.appendChild(successDiv);
                
                // Remove success message after 3 seconds
                setTimeout(() => {
                    successDiv.remove();
                }, 3000);
                
                closeModal('scheduleMaintenanceModal');
                this.reset();
                
                // Optionally reload to update alerts count
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to schedule maintenance');
            }
        } catch (error) {
            console.error('Error:', error);
            
            // Show error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg z-50';
            errorDiv.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span class="font-medium">${error.message}</span>
                </div>
            `;
            document.body.appendChild(errorDiv);
            
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
}
</script>

@endsection
                   

	
		