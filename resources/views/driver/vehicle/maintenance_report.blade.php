@extends('driver.driver_dashboard')
@section('driver')

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
    @media print {
        .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
    }
</style>

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
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 no-print">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Maintenance Reports</h1>
            <p class="text-gray-600">View and report maintenance for your assigned vehicle</p>
            @if(isset($vehicle))
            <p class="text-sm text-gray-500 mt-1">Vehicle: {{ $vehicle->vehicle_number }} - {{ $vehicle->vehicle_type }} {{ $vehicle->model ?? '' }}</p>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <button onclick="toggleExportMenu()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 mr-2">
                        <path d="M12 15V3"></path>
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <path d="m7 10 5 5 5-5"></path>
                    </svg>
                    Export
                </button>
                <div id="exportDropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                    <div class="py-1">
                        <button onclick="window.print()" class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                            Print Report
                        </button>
                        @if(isset($vehicle))
                        <a href="{{ route('driver.maintenance.export', 'csv') }}" class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Export CSV
                        </a>
                        <a href="{{ route('driver.maintenance.export', 'pdf') }}" class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Export PDF
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @if(isset($vehicle))
            <button onclick="openReportModal()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 mr-2">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                Report Issue
            </button>
            @endif
        </div>
    </div>

    @if(!isset($vehicle))
    <!-- No Vehicle Assigned -->
    <div class="rounded-lg border bg-white shadow-sm p-12 text-center">
        <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-400">
                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                <path d="M15 18H9"></path>
                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                <circle cx="17" cy="18" r="2"></circle>
                <circle cx="7" cy="18" r="2"></circle>
            </svg>
        </div>
        <h3 class="text-xl font-semibold mb-2">No Vehicle Assigned</h3>
        <p class="text-gray-600">You currently don't have any vehicle assigned to you. Please contact your administrator.</p>
    </div>
    @else
    <!-- Statistics Cards -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 no-print">
        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Records</p>
                    <p class="text-2xl font-bold">{{ $stats['total_records'] }}</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-600">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-600">All maintenance records</p>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Scheduled</p>
                    <p class="text-2xl font-bold">{{ $stats['scheduled'] }}</p>
                </div>
                <div class="p-2 bg-yellow-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-yellow-600">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-600">Upcoming maintenance</p>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">In Progress</p>
                    <p class="text-2xl font-bold">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="p-2 bg-orange-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-orange-600">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-600">Currently being serviced</p>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold">{{ $stats['completed'] }}</p>
                </div>
                <div class="p-2 bg-green-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-green-600">
                        <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                        <path d="m9 11 3 3L22 4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-600">Finished services</p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="rounded-lg border bg-white shadow-sm no-print">
        <div class="p-4 md:p-6">
            <form id="filterForm" method="GET" action="{{ route('driver.maintenance.index') }}" class="space-y-4">
                <div class="flex flex-col gap-4 md:flex-row">
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8" placeholder="Search by description, type, or provider..."/>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                    <select name="status" id="statusFilter" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>

                    <select name="maintenance_type" id="typeFilter" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                        <option value="">All Types</option>
                        @foreach($maintenanceTypes as $type)
                            <option value="{{ $type }}" {{ request('maintenance_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>

                    <select name="priority" id="priorityFilter" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                        <option value="">All Priorities</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>

                    <button type="button" onclick="clearFilters()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-10 rounded-md px-4">
                        Clear
                    </button>

                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-10 rounded-md px-4">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Maintenance Records Table -->
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-4 md:p-6">
            <h3 class="text-xl font-semibold mb-4">Maintenance Records <span class="text-gray-500">({{ $maintenanceLogs->count() }})</span></h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Log Number</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Type</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Description</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Status</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Priority</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Date</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Cost</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 w-12 no-print"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenanceLogs as $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">{{ $log->log_number }}</td>
                            <td class="p-4">{{ ucfirst($log->maintenance_type) }}</td>
                            <td class="p-4 max-w-xs truncate" title="{{ $log->description }}">{{ $log->description }}</td>
                            <td class="p-4">
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusColors[$log->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="p-4">
                                @php
                                    $priorityColors = [
                                        'low' => 'bg-gray-100 text-gray-800',
                                        'medium' => 'bg-blue-100 text-blue-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'critical' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $priorityColors[$log->priority ?? 'medium'] }}">
                                    {{ ucfirst($log->priority ?? 'medium') }}
                                </span>
                            </td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($log->maintenance_date)->format('M d, Y') }}</td>
                            <td class="p-4">${{ number_format($log->cost, 2) }}</td>
                            <td class="p-4 no-print">
                                <button onclick="viewDetails({{ $log->id }})" class="text-blue-600 hover:text-blue-800" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-300">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                    </svg>
                                    <p class="font-medium">No maintenance records found</p>
                                    <p class="text-sm">Report an issue to create a new record</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Report Issue Modal -->
@if(isset($vehicle))
<div id="reportModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Report Maintenance Issue</h2>
                <p class="text-sm text-gray-600 mt-1">Submit a maintenance request or report an issue with your vehicle</p>
            </div>
            <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="reportForm" method="POST" action="{{ route('driver.maintenance.report') }}" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Issue Type <span class="text-red-500">*</span></label>
                        <select name="maintenance_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select type</option>
                            <option value="breakdown">Breakdown</option>
                            <option value="repair">Repair Needed</option>
                            <option value="inspection">Inspection Request</option>
                            <option value="service">Service Request</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                            <option value="high">High</option>
                            <option value="critical">Critical (Immediate Attention)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Category</label>
                    <input type="text" name="category" placeholder="e.g., Engine, Brakes, Tires, Electrical" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" required rows="4" placeholder="Describe the issue in detail... Include when it started, symptoms, and any relevant information." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Current Mileage</label>
                    <input type="number" name="mileage_at_maintenance" placeholder="Current odometer reading (km)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Attach Photo (Optional)</label>
                    <input type="file" name="invoice_document" accept="image/*,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    <p class="text-xs text-gray-500 mt-1">Max file size: 5MB. Supported formats: JPG, PNG, PDF</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Additional Notes</label>
                    <textarea name="notes" rows="3" placeholder="Any additional information that might be helpful..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600 flex-shrink-0">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                        <p class="text-sm text-blue-800">Your report will be submitted to the admin team for review. You'll receive a notification once action is taken.</p>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 15V3"></path>
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <path d="m7 10 5 5 5-5"></path>
                    </svg>
                    Submit Report
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex items-center justify-between">
            <h2 class="text-2xl font-bold">Maintenance Record Details</h2>
            <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div id="detailsContent" class="p-6">
            <!-- Content loaded dynamically -->
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-gray-600">Loading details...</p>
            </div>
        </div>
        <div class="p-6 border-t flex justify-end">
            <button onclick="closeDetailsModal()" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Close</button>
        </div>
    </div>
</div>
@endif

<script>
// Toggle export menu
function toggleExportMenu() {
    const menu = document.getElementById('exportDropdown');
    menu.classList.toggle('show');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('button[onclick="toggleExportMenu()"]')) {
        const dropdown = document.getElementById('exportDropdown');
        if (dropdown) {
            dropdown.classList.remove('show');
        }
    }
});

// Modal functions
function openReportModal() {
    document.getElementById('reportModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeReportModal() {
    document.getElementById('reportModal').classList.remove('show');
    document.body.style.overflow = '';
    document.getElementById('reportForm').reset();
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.remove('show');
    document.body.style.overflow = '';
}

// Clear filters
function clearFilters() {
    window.location.href = '{{ route("driver.maintenance.index") }}';
}

// Real-time search with debounce
let searchTimeout;
document.getElementById('searchInput')?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});

// Auto-submit on filter change
document.querySelectorAll('#statusFilter, #typeFilter, #priorityFilter').forEach(select => {
    select.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// View details function
async function viewDetails(id) {
    const modal = document.getElementById('detailsModal');
    const content = document.getElementById('detailsContent');
    
    // Show modal with loading state
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    content.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading details...</p>
        </div>
    `;
    
    try {
        const response = await fetch(`{{ url('/driver/maintenance') }}/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to load details');
        }
        
        const data = await response.json();
        
        if (data.success) {
            const log = data.data;
            
            // Status color mapping
            const statusColors = {
                'scheduled': 'bg-yellow-100 text-yellow-800',
                'in_progress': 'bg-blue-100 text-blue-800',
                'completed': 'bg-green-100 text-green-800',
                'cancelled': 'bg-red-100 text-red-800'
            };
            
            // Priority color mapping
            const priorityColors = {
                'low': 'bg-gray-100 text-gray-800',
                'medium': 'bg-blue-100 text-blue-800',
                'high': 'bg-orange-100 text-orange-800',
                'critical': 'bg-red-100 text-red-800'
            };
            
            content.innerHTML = `
                <div class="space-y-6">
                    <!-- Header Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Log Number</h3>
                            <p class="text-lg font-bold">${log.log_number}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusColors[log.status] || 'bg-gray-100 text-gray-800'}">
                                ${log.status.charAt(0).toUpperCase() + log.status.slice(1).replace('_', ' ')}
                            </span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Priority</h3>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${priorityColors[log.priority || 'medium']}">
                                ${(log.priority || 'medium').charAt(0).toUpperCase() + (log.priority || 'medium').slice(1)}
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">${log.description}</p>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm font-medium text-gray-600">Type</span>
                                <span class="text-sm font-semibold">${log.maintenance_type.charAt(0).toUpperCase() + log.maintenance_type.slice(1)}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm font-medium text-gray-600">Category</span>
                                <span class="text-sm font-semibold">${log.category || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm font-medium text-gray-600">Cost</span>
                                <span class="text-sm font-bold text-green-600">${parseFloat(log.cost).toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm font-medium text-gray-600">Mileage</span>
                                <span class="text-sm font-semibold">${log.mileage_at_maintenance ? log.mileage_at_maintenance + ' km' : 'N/A'}</span>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm font-medium text-gray-600">Scheduled Date</span>
                                <span class="text-sm font-semibold">${new Date(log.maintenance_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm font-medium text-gray-600">Service Provider</span>
                                <span class="text-sm font-semibold">${log.vendor_name || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm font-medium text-gray-600">Technician</span>
                                <span class="text-sm font-semibold">${log.technician_name || (log.performed_by ? log.performed_by.name : 'N/A')}</span>
                            </div>
                            ${log.next_maintenance_date ? `
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm font-medium text-gray-600">Next Service</span>
                                <span class="text-sm font-semibold">${new Date(log.next_maintenance_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</span>
                            </div>
                            ` : ''}
                        </div>
                    </div>

                    ${log.parts_replaced ? `
                    <!-- Parts Replaced -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Parts Replaced</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">${log.parts_replaced}</p>
                        </div>
                    </div>
                    ` : ''}

                    ${log.notes ? `
                    <!-- Additional Notes -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Additional Notes</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 whitespace-pre-wrap">${log.notes}</p>
                        </div>
                    </div>
                    ` : ''}

                    <!-- Timeline -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Timeline</h3>
                        <div class="space-y-2">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium">Created</p>
                                    <p class="text-xs text-gray-500">${new Date(log.created_at).toLocaleString()}</p>
                                </div>
                            </div>
                            ${log.updated_at && log.updated_at !== log.created_at ? `
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-yellow-500 mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium">Last Updated</p>
                                    <p class="text-xs text-gray-500">${new Date(log.updated_at).toLocaleString()}</p>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        } else {
            throw new Error(data.message || 'Failed to load details');
        }
    } catch (error) {
        console.error('Error:', error);
        content.innerHTML = `
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-500 mx-auto mb-4">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <p class="text-red-600 font-medium">Failed to load details</p>
                <p class="text-sm text-gray-600 mt-2">${error.message}</p>
            </div>
        `;
    }
}

// Close modals when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    });
});

// Handle form submission
document.getElementById('reportForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Submitting...
    `;
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50';
            successDiv.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                        <path d="m9 11 3 3L22 4"></path>
                    </svg>
                    <span>${data.message || 'Maintenance issue reported successfully!'}</span>
                </div>
            `;
            document.body.appendChild(successDiv);
            
            // Close modal and reload after delay
            setTimeout(() => {
                successDiv.remove();
                closeReportModal();
                location.reload();
            }, 2000);
        } else {
            throw new Error(data.message || 'Failed to submit report');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error: ' + error.message);
        
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>

@endsection