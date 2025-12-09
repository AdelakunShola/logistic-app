@extends('admin.admin_dashboard')
@section('admin') 


<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="p-6 space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('admin.vehicles.index') }}" class="hover:text-blue-600">Vehicles</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
        <span class="text-gray-900 font-medium">Edit Vehicle - {{ $vehicle->vehicle_number }}</span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Edit Vehicle</h1>
            <p class="text-gray-600">Update vehicle information for {{ $vehicle->vehicle_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                View Details
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

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Vehicle Status Alert -->
    @if($vehicle->status == 'maintenance' || $vehicle->status == 'repair')
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative flex items-center gap-2" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
            <path d="M12 9v4"></path>
            <path d="M12 17h.01"></path>
        </svg>
        <span class="block sm:inline">This vehicle is currently under {{ $vehicle->status }}.</span>
    </div>
    @endif

    <!-- Form Card -->
    <form method="POST" action="{{ route('admin.vehicles.update', $vehicle->id) }}">
        @csrf
        @method('PUT')
        <div class="rounded-lg border bg-white shadow-sm">
            <!-- Basic Information Section -->
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Vehicle Number <span class="text-red-500">*</span></label>
                        <input type="text" name="vehicle_number" value="{{ old('vehicle_number', $vehicle->vehicle_number) }}" required placeholder="e.g., TRK-004" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_number') border-red-500 @enderror"/>
                        @error('vehicle_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Vehicle Name</label>
                        <input type="text" name="vehicle_name" value="{{ old('vehicle_name', $vehicle->vehicle_name) }}" placeholder="e.g., Delivery Truck 4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Vehicle Type <span class="text-red-500">*</span></label>
                        <select name="vehicle_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Type</option>
                            <option value="truck" {{ old('vehicle_type', $vehicle->vehicle_type) == 'truck' ? 'selected' : '' }}>Truck</option>
                            <option value="van" {{ old('vehicle_type', $vehicle->vehicle_type) == 'van' ? 'selected' : '' }}>Van</option>
                            <option value="car" {{ old('vehicle_type', $vehicle->vehicle_type) == 'car' ? 'selected' : '' }}>Car</option>
                            <option value="bike" {{ old('vehicle_type', $vehicle->vehicle_type) == 'bike' ? 'selected' : '' }}>Bike</option>
                            <option value="bicycle" {{ old('vehicle_type', $vehicle->vehicle_type) == 'bicycle' ? 'selected' : '' }}>Bicycle</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active" {{ old('status', $vehicle->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $vehicle->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ old('status', $vehicle->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="repair" {{ old('status', $vehicle->status) == 'repair' ? 'selected' : '' }}>Repair</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Make</label>
                        <input type="text" name="make" value="{{ old('make', $vehicle->make) }}" placeholder="e.g., Toyota" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Model</label>
                        <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" placeholder="e.g., Hilux" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Year</label>
                        <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" min="1900" max="{{ date('Y') + 1 }}" placeholder="e.g., 2023" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Color</label>
                        <input type="text" name="color" value="{{ old('color', $vehicle->color) }}" placeholder="e.g., White" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                </div>
            </div>

            <!-- Identification Section -->
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold mb-4">Identification</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">VIN (17 characters)</label>
                        <input type="text" name="vin" value="{{ old('vin', $vehicle->vin) }}" maxlength="17" placeholder="Vehicle Identification Number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">License Plate</label>
                        <input type="text" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" placeholder="e.g., ABC-1234" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                </div>
            </div>

            <!-- Capacity Section -->
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold mb-4">Capacity</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Capacity Weight (kg)</label>
                        <input type="number" name="capacity_weight" value="{{ old('capacity_weight', $vehicle->capacity_weight) }}" step="0.01" min="0" placeholder="e.g., 5000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Capacity Volume (m³)</label>
                        <input type="number" name="capacity_volume" value="{{ old('capacity_volume', $vehicle->capacity_volume) }}" step="0.01" min="0" placeholder="e.g., 20" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                </div>
            </div>

            <!-- Current Status Section -->
            <div class="p-6 border-b bg-gray-50">
                <h2 class="text-xl font-semibold mb-4">Current Status</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Mileage (km)</label>
                        <input type="number" name="mileage" value="{{ old('mileage', $vehicle->mileage) }}" step="0.01" min="0" placeholder="e.g., 45000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <!--<div>
                        <label class="block text-sm font-medium mb-2">Current Fuel Level (%)</label>
                        <input type="number" name="current_fuel_level" value="{{ old('current_fuel_level', $vehicle->current_fuel_level) }}" min="0" max="100" placeholder="e.g., 75" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>-->

                    <div>
                        <label class="block text-sm font-medium mb-2">Current Load (kg)</label>
                        <input type="number" name="current_load" value="{{ old('current_load', $vehicle->current_load) }}" step="0.01" min="0" placeholder="e.g., 2500" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        <p class="text-xs text-gray-500 mt-1">Current: {{ number_format($vehicle->current_load ?? 0, 2) }} kg</p>
                    </div>

                    <div class="">
                        <label class="block text-sm font-medium mb-2">Current Location</label>
                        <input type="text" name="current_location" value="{{ old('current_location', $vehicle->current_location) }}" placeholder="e.g., Warehouse A, Lagos" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                </div>
            </div>

            <!-- Assignment Section -->
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold mb-4">Assignment</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Assigned Driver</label>
                        <select name="assigned_driver_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select driver</option>
                            @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('assigned_driver_id', $vehicle->assigned_driver_id) == $driver->id ? 'selected' : '' }}>
                                {{ $driver->first_name }} {{ $driver->last_name }}
                            </option>
                            @endforeach
                        </select>
                        @if($vehicle->assignedDriver)
                        <p class="text-xs text-gray-500 mt-1">Currently: {{ $vehicle->assignedDriver->first_name }} {{ $vehicle->assignedDriver->last_name }}</p>
                        @endif
                    </div>

                  

                    <div>
                        <label class="block text-sm font-medium mb-2">Warehouse</label>
                        <select name="warehouse_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select warehouse</option>
                            @foreach($warehouse as $ware)
                            <option value="{{ $ware->id }}" {{ old('warehouse_id', $vehicle->warehouse_id) == $ware->id ? 'selected' : '' }}>
                                {{ $ware->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                   
                </div>
            </div>

            <!-- Registration & Insurance Section -->
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold mb-4">Registration & Insurance</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Registration Date</label>
                        <input type="date" name="registration_date" value="{{ old('registration_date', $vehicle->registration_date) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Registration Expiry</label>
                        <input type="date" name="registration_expiry" value="{{ old('registration_expiry', $vehicle->registration_expiry) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        @if($vehicle->registration_expiry && \Carbon\Carbon::parse($vehicle->registration_expiry)->isPast())
                        <p class="text-red-500 text-xs mt-1">⚠️ Registration has expired!</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Insurance Expiry</label>
                        <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry', $vehicle->insurance_expiry) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        @if($vehicle->insurance_expiry && \Carbon\Carbon::parse($vehicle->insurance_expiry)->isPast())
                        <p class="text-red-500 text-xs mt-1">⚠️ Insurance has expired!</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Insurance Company</label>
                        <input type="text" name="insurance_company" value="{{ old('insurance_company', $vehicle->insurance_company) }}" placeholder="e.g., State Farm" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Insurance Policy Number</label>
                        <input type="text" name="insurance_policy_number" value="{{ old('insurance_policy_number', $vehicle->insurance_policy_number) }}" placeholder="e.g., POL-123456" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                </div>
            </div>

            <!-- Maintenance Section -->
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold mb-4">Maintenance Schedule</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Last Service Date</label>
                        <input type="date" name="last_service_date" value="{{ old('last_service_date', $vehicle->last_service_date) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Next Service Date</label>
                        <input type="date" name="next_service_date" value="{{ old('next_service_date', $vehicle->next_service_date) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        @if($vehicle->next_service_date && \Carbon\Carbon::parse($vehicle->next_service_date)->diffInDays(now()) <= 30)
                        <p class="text-yellow-600 text-xs mt-1">⚠️ Service due soon!</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fuel Information Section -->
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold mb-4">Fuel Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Fuel Type</label>
                        <select name="fuel_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select fuel type</option>
                            <option value="petrol" {{ old('fuel_type', $vehicle->fuel_type) == 'petrol' ? 'selected' : '' }}>Petrol</option>
                            <option value="diesel" {{ old('fuel_type', $vehicle->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="electric" {{ old('fuel_type', $vehicle->fuel_type) == 'electric' ? 'selected' : '' }}>Electric</option>
                            <option value="hybrid" {{ old('fuel_type', $vehicle->fuel_type) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Fuel Capacity (Liters)</label>
                        <input type="number" name="fuel_capacity" value="{{ old('fuel_capacity', $vehicle->fuel_capacity) }}" step="0.01" min="0" placeholder="e.g., 80" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                  <!--  <div>
                        <label class="block text-sm font-medium mb-2">Fuel Efficiency (MPG)</label>
                        <input type="number" name="fuel_efficiency_mpg" value="{{ old('fuel_efficiency_mpg', $vehicle->fuel_efficiency_mpg) }}" step="0.01" min="0" placeholder="e.g., 15.5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Last Fuel Date</label>
                        <input type="date" name="last_fuel_date" value="{{ old('last_fuel_date', $vehicle->last_fuel_date) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>-->
                </div>
            </div>

            <!-- Additional Notes Section -->
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Additional Information</h2>
                <div>
                    <label class="block text-sm font-medium mb-2">Notes</label>
                    <textarea name="notes" rows="4" placeholder="Any additional notes about the vehicle..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $vehicle->notes) }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="p-6 border-t bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-sm text-gray-500">
                    Last updated: {{ $vehicle->updated_at->format('M d, Y h:i A') }}
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.vehicles.index') }}" class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-100 font-medium">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Update Vehicle
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Quick Actions Card -->
  <!--  <div class="rounded-lg border bg-white shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <a href="{{ route('admin.vehicles.track', $vehicle->id) }}" class="flex items-center gap-3 p-3 border rounded-md hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span class="font-medium">Track Vehicle</span>
            </a>
            
            <button onclick="scheduleMaintenanceModal()" class="flex items-center gap-3 p-3 border rounded-md hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                <span class="font-medium">Schedule Maintenance</span>
            </button>
            
            <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full flex items-center gap-3 p-3 border border-red-300 rounded-md hover:bg-red-50 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
<path d="M3 6h18"></path>
<path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
<path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
</svg>
<span class="font-medium">Delete Vehicle</span>
</button>
</form>
</div>
</div>-->
</div>
@endsection