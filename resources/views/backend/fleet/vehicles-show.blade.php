@extends('admin.admin_dashboard')
@section('admin') 

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="p-6 space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('admin.vehicles.index') }}" class="hover:text-blue-600">Vehicles</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
        <span class="text-gray-900 font-medium">{{ $vehicle->vehicle_number }}</span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <path d="M15 18H9"></path>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold tracking-tight">{{ $vehicle->vehicle_number }}</h1>
                <p class="text-gray-600">{{ $vehicle->make ?? 'N/A' }} {{ $vehicle->model ?? 'N/A' }} @if($vehicle->year) ({{ $vehicle->year }}) @endif</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                </svg>
                Edit Vehicle
            </a>
            <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div>
        @php
            $statusColors = [
                'active' => 'bg-green-100 text-green-800 border-green-200',
                'inactive' => 'bg-gray-100 text-gray-800 border-gray-200',
                'maintenance' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'repair' => 'bg-red-100 text-red-800 border-red-200',
            ];
        @endphp
        <span class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-sm font-semibold {{ $statusColors[$vehicle->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
            <span class="h-2 w-2 rounded-full {{ $vehicle->status == 'active' ? 'bg-green-600' : ($vehicle->status == 'maintenance' ? 'bg-yellow-600' : ($vehicle->status == 'repair' ? 'bg-red-600' : 'bg-gray-600')) }}"></span>
            {{ ucfirst($vehicle->status) }}
        </span>
    </div>

    <!-- Alert Messages -->
    @if($vehicle->alert_count > 0)
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start gap-3" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mt-0.5 flex-shrink-0">
            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
            <path d="M12 9v4"></path>
            <path d="M12 17h.01"></path>
        </svg>
        <div>
            <p class="font-semibold">{{ $vehicle->alert_count }} Active Alert(s)</p>
            @if($vehicle->alert_message)
            <p class="text-sm mt-1">{{ $vehicle->alert_message }}</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Quick Stats Cards -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-lg border bg-white shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Mileage</p>
                    <p class="text-2xl font-bold">{{ number_format($vehicle->mileage ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500">kilometers</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
        </div>

       <!-- <div class="rounded-lg border bg-white shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Fuel Level</p>
                    <p class="text-2xl font-bold">{{ $vehicle->current_fuel_level ?? 0 }}%</p>
                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                        <div class="h-full rounded-full {{ $vehicle->current_fuel_level > 60 ? 'bg-green-500' : ($vehicle->current_fuel_level > 30 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $vehicle->current_fuel_level ?? 0 }}%"></div>
                    </div>
                </div>
                <div class="p-2 bg-yellow-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600">
                        <line x1="3" x2="15" y1="22" y2="22"></line>
                        <line x1="4" x2="14" y1="9" y2="9"></line>
                        <path d="M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18"></path>
                        <path d="M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V9.83a2 2 0 0 0-.59-1.42L18 5"></path>
                    </svg>
                </div>
            </div>
        </div>-->

        <div class="rounded-lg border bg-white shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Utilization</p>
                    <p class="text-2xl font-bold">{{ number_format($vehicle->utilization_percentage ?? 0, 0) }}%</p>
                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                        <div class="h-full bg-blue-600 rounded-full" style="width: {{ $vehicle->utilization_percentage ?? 0 }}%"></div>
                    </div>
                </div>
                <div class="p-2 bg-purple-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-purple-600">
                        <line x1="12" x2="12" y1="20" y2="10"></line>
                        <line x1="18" x2="18" y1="20" y2="4"></line>
                        <line x1="6" x2="6" y1="20" y2="16"></line>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Trips</p>
                    <p class="text-2xl font-bold">{{ number_format($vehicle->total_trips ?? 0) }}</p>
                    <p class="text-xs text-gray-500">completed</p>
                </div>
                <div class="p-2 bg-green-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Information (Left Column - 2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Vehicle Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Vehicle Number</label>
                            <p class="text-base font-semibold mt-1">{{ $vehicle->vehicle_number }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Vehicle Name</label>
                            <p class="text-base mt-1">{{ $vehicle->vehicle_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Type</label>
                            <p class="text-base mt-1 capitalize">{{ $vehicle->vehicle_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Make & Model</label>
                            <p class="text-base mt-1">{{ $vehicle->make ?? 'N/A' }} {{ $vehicle->model ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Year</label>
                            <p class="text-base mt-1">{{ $vehicle->year ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Color</label>
                            <p class="text-base mt-1">{{ $vehicle->color ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">VIN</label>
                            <p class="text-base mt-1 font-mono text-sm">{{ $vehicle->vin ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">License Plate</label>
                            <p class="text-base mt-1 font-semibold">{{ $vehicle->license_plate ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Capacity Information -->
           <!--  <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Capacity & Load</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Capacity Weight</label>
                            <p class="text-2xl font-bold mt-1">{{ number_format($vehicle->capacity_weight ?? 0, 2) }}</p>
                            <p class="text-xs text-gray-500">kilograms</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Current Load</label>
                            <p class="text-2xl font-bold mt-1">{{ number_format($vehicle->current_load ?? 0, 2) }}</p>
                            <p class="text-xs text-gray-500">kilograms</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Capacity Volume</label>
                            <p class="text-2xl font-bold mt-1">{{ number_format($vehicle->capacity_volume ?? 0, 2) }}</p>
                            <p class="text-xs text-gray-500">cubic meters</p>
                        </div>
                    </div>
                    @if($vehicle->capacity_weight > 0)
                    <div class="mt-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Load Utilization</span>
                            <span class="font-medium">{{ number_format(($vehicle->current_load / $vehicle->capacity_weight) * 100, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-full bg-blue-600 rounded-full" style="width: {{ min(($vehicle->current_load / $vehicle->capacity_weight) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>-->

            <!-- Registration & Insurance -->
            <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Registration & Insurance</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Registration Date</p>
                                <p class="text-base font-semibold mt-1">{{ $vehicle->registration_date ? \Carbon\Carbon::parse($vehicle->registration_date)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Expiry Date</p>
                                <p class="text-base font-semibold mt-1">{{ $vehicle->registration_expiry ? \Carbon\Carbon::parse($vehicle->registration_expiry)->format('M d, Y') : 'N/A' }}</p>
                                @if($vehicle->registration_expiry && \Carbon\Carbon::parse($vehicle->registration_expiry)->isPast())
                                <span class="text-xs text-red-600 font-semibold">⚠️ Expired</span>
                                @elseif($vehicle->registration_expiry && \Carbon\Carbon::parse($vehicle->registration_expiry)->diffInDays(now()) <= 30)
                                <span class="text-xs text-yellow-600 font-semibold">⚠️ Expires Soon</span>
                                @endif
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Insurance Company</label>
                                    <p class="text-base mt-1">{{ $vehicle->insurance_company ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Policy Number</label>
                                    <p class="text-base mt-1 font-mono text-sm">{{ $vehicle->insurance_policy_number ?? 'N/A' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium text-gray-600">Insurance Expiry</label>
                                    <p class="text-base font-semibold mt-1">{{ $vehicle->insurance_expiry ? \Carbon\Carbon::parse($vehicle->insurance_expiry)->format('M d, Y') : 'N/A' }}</p>
                                    @if($vehicle->insurance_expiry && \Carbon\Carbon::parse($vehicle->insurance_expiry)->isPast())
                                    <span class="text-xs text-red-600 font-semibold">⚠️ Expired</span>
                                    @elseif($vehicle->insurance_expiry && \Carbon\Carbon::parse($vehicle->insurance_expiry)->diffInDays(now()) <= 30)
                                    <span class="text-xs text-yellow-600 font-semibold">⚠️ Expires Soon</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fuel Information -->
            <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Fuel Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Fuel Type</label>
                            <p class="text-base mt-1 capitalize">{{ $vehicle->fuel_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Fuel Capacity</label>
                            <p class="text-base mt-1">{{ $vehicle->fuel_capacity ? number_format($vehicle->fuel_capacity, 2) . ' L' : 'N/A' }}</p>
                        </div>
                       <!-- <div>
                            <label class="text-sm font-medium text-gray-600">Fuel Efficiency</label>
                            <p class="text-base mt-1">{{ $vehicle->fuel_efficiency_mpg ? number_format($vehicle->fuel_efficiency_mpg, 2) . ' MPG' : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Fuel Date</label>
                            <p class="text-base mt-1">{{ $vehicle->last_fuel_date ? \Carbon\Carbon::parse($vehicle->last_fuel_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Total Fuel Consumed</label>
                            <p class="text-base mt-1">{{ number_format($vehicle->total_fuel_consumed ?? 0, 2) }} L</p>
                        </div>-->
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <!--<div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Performance Metrics</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-600">Total Distance</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($vehicle->total_distance ?? 0, 0) }}</p>
                            <p class="text-xs text-gray-500 mt-1">kilometers</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-600">Average Speed</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($vehicle->avg_speed ?? 0, 1) }}</p>
                            <p class="text-xs text-gray-500 mt-1">km/h</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-600">Current Speed</p>
                            <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($vehicle->speed ?? 0, 1) }}</p>
                            <p class="text-xs text-gray-500 mt-1">km/h</p>
                        </div>
                    </div>
                </div>
            </div>-->

            <!-- Notes -->
            @if($vehicle->notes)
            <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Notes</h2>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $vehicle->notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar (Right Column - 1/3) -->
        <div class="space-y-6">
            <!-- Assignment Information -->
            <div class="rounded-lg border bg-white shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Assignment</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Assigned Driver</label>
                        @if($vehicle->assignedDriver)
                        <div class="flex items-center gap-3 mt-2 p-3 bg-blue-50 rounded-lg">
                            <div class="h-10 w-10 rounded-full bg-blue-200 flex items-center justify-center">
                                <span class="text-blue-700 font-semibold">{{ substr($vehicle->assignedDriver->first_name, 0, 1) }}{{ substr($vehicle->assignedDriver->last_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $vehicle->assignedDriver->first_name }} {{ $vehicle->assignedDriver->last_name }}</p>
                                <p class="text-xs text-gray-600">{{ $vehicle->assignedDriver->email }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-base mt-2 text-gray-500 italic">No driver assigned</p>
                        @endif
                    </div>

                  
                    <div>
                        <label class="text-sm font-medium text-gray-600">Location</label>
                        <p class="text-base mt-1">{{ $vehicle->warehouse->name ?? 'N/A' }}</p>
                    </div>

                   
                </div>
            </div>

            <!-- Location Information -->
          

            <!-- Maintenance Schedule -->
           <div class="rounded-lg border bg-white shadow-sm">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold">Maintenance</h2>
    </div>
    <div class="p-6 space-y-4">
        @php
            // Get last completed maintenance (most recent updated_at where status is completed)
            $lastCompletedMaintenance = \App\Models\MaintenanceLog::where('vehicle_id', $vehicle->id)
                ->where('status', 'completed')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            // Get all uncompleted maintenance (everything where status is NOT completed)
            $uncompletedMaintenance = \App\Models\MaintenanceLog::where('vehicle_id', $vehicle->id)
                ->where('status', '!=', 'completed')
                ->orderBy('maintenance_date', 'asc')
                ->get();
            
            // Get the next scheduled maintenance (earliest uncompleted)
            $nextScheduledMaintenance = $uncompletedMaintenance->first();
        @endphp
        
        <div>
            <label class="text-sm font-medium text-gray-600">Last Completed Service</label>
            <p class="text-base mt-1">
                {{ $lastCompletedMaintenance ? \Carbon\Carbon::parse($lastCompletedMaintenance->completion_date)->format('M d, Y') : 'N/A' }}
            </p>
            @if($lastCompletedMaintenance)
                <p class="text-xs text-gray-500 mt-1">
                    {{ $lastCompletedMaintenance->description }} 
                    @if($lastCompletedMaintenance->cost > 0)
                        <span class="font-medium">(${{ number_format($lastCompletedMaintenance->cost, 2) }})</span>
                    @endif
                </p>
            @endif
        </div>
        
        <div>
            <label class="text-sm font-medium text-gray-600">Next Scheduled Service</label>
            <p class="text-base mt-1 font-semibold">
                {{ $nextScheduledMaintenance ? \Carbon\Carbon::parse($nextScheduledMaintenance->maintenance_date)->format('M d, Y') : 'Not scheduled' }}
            </p>
            @if($nextScheduledMaintenance)
                @php
                    $daysUntilService = \Carbon\Carbon::parse($nextScheduledMaintenance->maintenance_date)->diffInDays(now(), false);
                @endphp
                @if($daysUntilService > 0)
                    <span class="text-xs text-red-600 font-semibold">⚠️ Overdue by {{ abs($daysUntilService) }} days</span>
                @elseif(abs($daysUntilService) <= 30)
                    <span class="text-xs text-yellow-600 font-semibold">⚠️ Due in {{ abs($daysUntilService) }} days</span>
                @else
                    <span class="text-xs text-green-600">✓ Up to date</span>
                @endif
                <p class="text-xs text-gray-500 mt-1">
                    {{ ucfirst($nextScheduledMaintenance->maintenance_type) }}: {{ $nextScheduledMaintenance->description }}
                </p>
            @endif
        </div>
        
        @if($uncompletedMaintenance->count() > 0)
            <div class="pt-3 border-t">
                <label class="text-sm font-medium text-gray-600 mb-2 block">
                    Pending Maintenance ({{ $uncompletedMaintenance->count() }})
                </label>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($uncompletedMaintenance as $maintenance)
                        <div class="flex items-start gap-2 p-2 bg-gray-50 rounded text-xs">
                            <div class="flex-shrink-0 mt-0.5">
                                @if($maintenance->status === 'in_progress')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-500">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-500">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('M d, Y') }}</span>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-{{ $maintenance->status === 'in_progress' ? 'blue' : 'yellow' }}-100 text-{{ $maintenance->status === 'in_progress' ? 'blue' : 'yellow' }}-800">
                                        {{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}
                                    </span>
                                    @if($maintenance->priority === 'critical' || $maintenance->priority === 'high')
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-800">
                                            {{ ucfirst($maintenance->priority) }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-600 mt-0.5 truncate">{{ $maintenance->description }}</p>
                                @if($maintenance->category)
                                    <span class="text-gray-500 text-xs">{{ $maintenance->category }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <button onclick="scheduleMaintenanceModal()" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium">
            Schedule Maintenance
        </button>
    </div>
</div>


        <!-- Quick Actions -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Quick Actions</h2>
            </div>
            <div class="p-6 space-y-2">
                <button onclick="assignDriverModal()" class="w-full flex items-center gap-3 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Assign Driver</span>
                </button>
                
              

                <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 border border-red-300 text-red-600 rounded-md hover:bg-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                        </svg>
                        <span>Delete Vehicle</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="rounded-lg border bg-gray-50 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-600 mb-3">Record Information</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Created:</span>
                    <span class="font-medium">{{ $vehicle->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Last Updated:</span>
                    <span class="font-medium">{{ $vehicle->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
function scheduleMaintenanceModal() {
    alert('Schedule Maintenance feature - To be implemented');
    // You can integrate with your maintenance scheduling system here
}

function assignDriverModal() {
    window.location.href = "{{ route('admin.vehicles.edit', $vehicle->id) }}#assignment";
}

function updateLocationModal() {
    alert('Update Location feature - To be implemented');
    // You can integrate with your location update system here
}
</script>
@endsection