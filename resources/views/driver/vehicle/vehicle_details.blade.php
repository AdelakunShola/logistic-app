@extends('driver.driver_dashboard')
@section('driver')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
</style>

<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">My Vehicle Details</h1>
            <p class="text-gray-600">View comprehensive information about your assigned vehicle</p>
        </div>
        @if($vehicle)
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 mr-2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print
            </button>
            <a href="{{ route('driver.maintenance.index') }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 mr-2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                View Maintenance
            </a>
        </div>
        @endif
    </div>

    @if(!$vehicle)
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
        <p class="text-gray-600 mb-4">You currently don't have any vehicle assigned to you. Please contact your administrator.</p>
    </div>
    @else
    <!-- Vehicle Status Cards -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="stat-card rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Vehicle Status</p>
                    <p class="text-2xl font-bold">{{ ucfirst($vehicle->status) }}</p>
                </div>
                <div class="p-2 bg-{{ $vehicle->status == 'active' ? 'green' : ($vehicle->status == 'maintenance' ? 'yellow' : 'red') }}-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-{{ $vehicle->status == 'active' ? 'green' : ($vehicle->status == 'maintenance' ? 'yellow' : 'red') }}-600">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-xs text-gray-600">Last updated: {{ $vehicle->updated_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="stat-card rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Current Mileage</p>
                    <p class="text-2xl font-bold">{{ number_format($vehicle->mileage, 0) }}</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-600">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-xs text-gray-600">Total distance (km)</span>
            </div>
        </div>

        <div class="stat-card rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Fuel Level</p>
                    <p class="text-2xl font-bold">{{ $vehicle->current_fuel_level ?? 0 }}%</p>
                </div>
                <div class="p-2 bg-{{ ($vehicle->current_fuel_level ?? 0) > 50 ? 'green' : (($vehicle->current_fuel_level ?? 0) > 25 ? 'yellow' : 'red') }}-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-{{ ($vehicle->current_fuel_level ?? 0) > 50 ? 'green' : (($vehicle->current_fuel_level ?? 0) > 25 ? 'yellow' : 'red') }}-600">
                        <line x1="3" y1="22" x2="15" y2="22"></line>
                        <line x1="4" y1="9" x2="14" y2="9"></line>
                        <path d="M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18"></path>
                        <path d="M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V9.83a2 2 0 0 0-.59-1.42L18 5"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-{{ ($vehicle->current_fuel_level ?? 0) > 50 ? 'green' : (($vehicle->current_fuel_level ?? 0) > 25 ? 'yellow' : 'red') }}-500" style="width: {{ $vehicle->current_fuel_level ?? 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Active Alerts</p>
                    <p class="text-2xl font-bold">{{ $vehicle->alert_count }}</p>
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
                <span class="text-xs text-gray-600">Requires attention</span>
            </div>
        </div>
    </div>

    <!-- Vehicle Information -->
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Basic Information -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6 border-b">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                    </svg>
                    Basic Information
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Vehicle Number</span>
                        <span class="font-semibold">{{ $vehicle->vehicle_number }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Vehicle Name</span>
                        <span class="font-semibold">{{ $vehicle->vehicle_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Type</span>
                        <span class="font-semibold">{{ ucfirst($vehicle->vehicle_type) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Make & Model</span>
                        <span class="font-semibold">{{ $vehicle->make ?? 'N/A' }} {{ $vehicle->model ?? '' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Year</span>
                        <span class="font-semibold">{{ $vehicle->year ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Color</span>
                        <span class="font-semibold">{{ $vehicle->color ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">License Plate</span>
                        <span class="font-semibold">{{ $vehicle->license_plate ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 font-medium">VIN</span>
                        <span class="font-semibold text-sm">{{ $vehicle->vin ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6 border-b">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                    Specifications
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Capacity (Weight)</span>
                        <span class="font-semibold">{{ $vehicle->capacity_weight ?? 'N/A' }} kg</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Capacity (Volume)</span>
                        <span class="font-semibold">{{ $vehicle->capacity_volume ?? 'N/A' }} m³</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Current Load</span>
                        <span class="font-semibold">{{ $vehicle->current_load ?? 0 }} kg</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Utilization</span>
                        <span class="font-semibold">{{ $vehicle->utilization_percentage ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Fuel Type</span>
                        <span class="font-semibold">{{ ucfirst($vehicle->fuel_type ?? 'N/A') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Fuel Capacity</span>
                        <span class="font-semibold">{{ $vehicle->fuel_capacity ?? 'N/A' }} L</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Average Speed</span>
                        <span class="font-semibold">{{ $vehicle->avg_speed ?? 'N/A' }} km/h</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 font-medium">Total Trips</span>
                        <span class="font-semibold">{{ $vehicle->total_trips ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance & Documentation -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6 border-b">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                    </svg>
                    Maintenance Schedule
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Last Service Date</span>
                        <span class="font-semibold">{{ $vehicle->last_service_date ? \Carbon\Carbon::parse($vehicle->last_service_date)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Next Service Date</span>
                        <span class="font-semibold {{ $vehicle->next_service_date && \Carbon\Carbon::parse($vehicle->next_service_date)->isPast() ? 'text-red-600' : '' }}">
                            {{ $vehicle->next_service_date ? \Carbon\Carbon::parse($vehicle->next_service_date)->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Registration Expiry</span>
                        <span class="font-semibold {{ $vehicle->registration_expiry && \Carbon\Carbon::parse($vehicle->registration_expiry)->isPast() ? 'text-red-600' : '' }}">
                            {{ $vehicle->registration_expiry ? \Carbon\Carbon::parse($vehicle->registration_expiry)->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Insurance Expiry</span>
                        <span class="font-semibold {{ $vehicle->insurance_expiry && \Carbon\Carbon::parse($vehicle->insurance_expiry)->isPast() ? 'text-red-600' : '' }}">
                            {{ $vehicle->insurance_expiry ? \Carbon\Carbon::parse($vehicle->insurance_expiry)->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Insurance Company</span>
                        <span class="font-semibold">{{ $vehicle->insurance_company ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 font-medium">Policy Number</span>
                        <span class="font-semibold text-sm">{{ $vehicle->insurance_policy_number ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location & Assignment -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6 border-b">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Location & Assignment
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Current Location</span>
                        <span class="font-semibold">{{ $vehicle->current_location ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Warehouse</span>
                        <span class="font-semibold">{{ $vehicle->warehouse->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600 font-medium">Hub</span>
                        <span class="font-semibold">{{ $vehicle->hub->name ?? 'N/A' }}</span>
                    </div>
                    @if($vehicle->last_location_update)
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 font-medium">Last Location Update</span>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($vehicle->last_location_update)->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    @if($vehicle->notes)
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6 border-b">
            <h3 class="text-xl font-semibold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Additional Notes
            </h3>
        </div>
        <div class="p-6">
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $vehicle->notes }}</p>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>

@endsection