@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.warehouse-inventory.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="font-medium">Back to Inventory</span>
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-r-lg shadow-sm flex items-center gap-3" role="alert">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-r-lg shadow-sm flex items-center gap-3" role="alert">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-r-lg shadow-sm" role="alert">
        <div class="flex items-center gap-3 mb-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">Please correct the following errors:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 ml-8">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    Check In Package
                </h1>
                <p class="text-gray-600 mt-1">Add a new package to warehouse inventory</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.warehouse-inventory.store') }}" method="POST" id="checkInForm">
        @csrf
        
        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Warehouse & Shipment Selection -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Warehouse & Package Selection
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Warehouse Selection -->
                        <div>
                            <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Warehouse <span class="text-red-500">*</span>
                            </label>
                            <select id="warehouse_id" 
                                    name="warehouse_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
                                <option value="">-- Choose a warehouse --</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" 
                                        data-capacity="{{ $warehouse->storage_capacity }}"
                                        data-current="{{ $warehouse->current_occupancy }}"
                                        {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }} ({{ $warehouse->warehouse_code }}) - 
                                    {{ $warehouse->current_occupancy }}/{{ $warehouse->storage_capacity }} occupied
                                </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="warehouseCapacityWarning" class="hidden mt-2 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <div class="flex items-center gap-2 text-amber-800 text-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">This warehouse is near or at capacity!</span>
                                </div>
                            </div>
                        </div>

                        <!-- Shipment Selection -->
                        <div>
                            <label for="shipment_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Shipment/Package <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="shipmentSearch" 
                                       placeholder="Search by tracking number, sender, or recipient..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 mb-2">
                                <select id="shipment_id" 
                                        name="shipment_id" 
                                        required
                                        size="8"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
                                    <option value="">-- Choose a shipment --</option>
                                    @forelse($availableShipments as $shipment)
                                    <option value="{{ $shipment->id }}" 
                                            data-tracking="{{ $shipment->tracking_number }}"
                                            data-sender="{{ $shipment->sender_name }}"
                                            data-recipient="{{ $shipment->recipient_name }}"
                                            {{ old('shipment_id') == $shipment->id ? 'selected' : '' }}>
                                        [{{ $shipment->tracking_number }}] {{ $shipment->sender_name }} → {{ $shipment->recipient_name }}
                                    </option>
                                    @empty
                                    <option value="" disabled>No available shipments found</option>
                                    @endforelse
                                </select>
                            </div>
                            @error('shipment_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($availableShipments->isEmpty())
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center gap-2 text-blue-800 text-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>No shipments available for check-in. All shipments are either already in warehouses or not ready.</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Selected Shipment Info -->
                        <div id="selectedShipmentInfo" class="hidden p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-lg">
                            <h3 class="text-sm font-semibold text-emerald-900 mb-2">Selected Package Details:</h3>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-600">Tracking:</span>
                                    <span id="infoTracking" class="ml-2 font-semibold text-gray-900"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Sender:</span>
                                    <span id="infoSender" class="ml-2 font-semibold text-gray-900"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Recipient:</span>
                                    <span id="infoRecipient" class="ml-2 font-semibold text-gray-900"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Storage Location -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Storage Location Details
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="storage_location" class="block text-sm font-medium text-gray-700 mb-2">
                                    Storage Location
                                </label>
                                <input type="text" 
                                       id="storage_location" 
                                       name="storage_location" 
                                       value="{{ old('storage_location') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                       placeholder="e.g., Aisle 3, Shelf B2">
                                @error('storage_location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Optional: Specify exact location in warehouse</p>
                            </div>
                            <div>
                                <label for="bin_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    Bin Number
                                </label>
                                <input type="text" 
                                       id="bin_number" 
                                       name="bin_number" 
                                       value="{{ old('bin_number') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                       placeholder="e.g., BIN-001">
                                @error('bin_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Optional: Specific bin identifier</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Condition -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Package Condition Assessment
                        </h2>
                    </div>
                    <div class="p-6">
                        <label for="package_condition" class="block text-sm font-medium text-gray-700 mb-2">
                            Condition Status <span class="text-red-500">*</span>
                        </label>
                        <select id="package_condition" 
                                name="package_condition" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
                            <option value="good" {{ old('package_condition') == 'good' ? 'selected' : '' }}>
                                ✓ Good Condition - Package is intact and undamaged
                            </option>
                            <option value="damaged" {{ old('package_condition') == 'damaged' ? 'selected' : '' }}>
                                ⚠ Damaged - Package has visible damage
                            </option>
                            <option value="requires_attention" {{ old('package_condition') == 'requires_attention' ? 'selected' : '' }}>
                                ⚡ Requires Attention - Package needs inspection or care
                            </option>
                        </select>
                        @error('package_condition')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Handling Notes -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Handling Instructions
                        </h2>
                    </div>
                    <div class="p-6">
                        <label for="handling_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Special Instructions or Notes
                        </label>
                        <textarea id="handling_notes" 
                                  name="handling_notes" 
                                  rows="5"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                  placeholder="Add any special handling instructions, damage descriptions, or important notes about this package...">{{ old('handling_notes') }}</textarea>
                        @error('handling_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">This information will be visible to all warehouse staff</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Flags & Status -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                        <h2 class="text-lg font-bold text-white">Package Flags</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Priority Package -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="is_priority" 
                                       name="is_priority" 
                                       value="1"
                                       {{ old('is_priority') ? 'checked' : '' }}
                                       class="w-5 h-5 text-amber-600 border-gray-300 rounded focus:ring-amber-500 focus:ring-2 cursor-pointer">
                            </div>
                            <div class="ml-3">
                                <label for="is_priority" class="font-medium text-gray-900 cursor-pointer flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                    Priority Package
                                </label>
                                <p class="text-sm text-gray-600">Mark as high priority for expedited handling</p>
                            </div>
                        </div>

                        <!-- Special Handling -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="requires_special_handling" 
                                       name="requires_special_handling" 
                                       value="1"
                                       {{ old('requires_special_handling') ? 'checked' : '' }}
                                       class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 cursor-pointer">
                            </div>
                            <div class="ml-3">
                                <label for="requires_special_handling" class="font-medium text-gray-900 cursor-pointer flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                    Special Handling Required
                                </label>
                                <p class="text-sm text-gray-600">Fragile or requires careful handling</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-6 space-y-3">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-lg hover:from-emerald-700 hover:to-teal-700 shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Check In Package
                        </button>
                        <a href="{{ route('admin.warehouse-inventory.index') }}" class="w-full inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg overflow-hidden border border-blue-200">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-blue-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Check-in Information
                        </h3>
                        <ul class="text-xs text-blue-800 space-y-2">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Select warehouse with available capacity</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Choose an available shipment to check in</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Assess package condition upon arrival</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Add storage location for easy retrieval</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Document any damage or special requirements</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Warehouse Capacity Info -->
                @if($warehouses->count() > 0)
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-lg overflow-hidden border border-purple-200">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-purple-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Available Warehouses
                        </h3>
                        <div class="space-y-2 text-xs text-purple-800">
                            @foreach($warehouses as $warehouse)
                            <div class="p-2 bg-white rounded-lg border border-purple-100">
                                <div class="font-semibold text-purple-900">{{ $warehouse->name }}</div>
                                <div class="text-purple-700">{{ $warehouse->current_occupancy ?? 0 }}/{{ $warehouse->storage_capacity ?? 0 }} occupied</div>
                                <div class="mt-1 w-full bg-purple-200 rounded-full h-1.5">
                                    <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ ($warehouse->storage_capacity ?? 0) > 0 ? (($warehouse->current_occupancy ?? 0) / $warehouse->storage_capacity * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>

<script>
// Warehouse capacity check
document.getElementById('warehouse_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const capacity = parseInt(selectedOption.dataset.capacity);
    const current = parseInt(selectedOption.dataset.current);
    const warning = document.getElementById('warehouseCapacityWarning');
    
    if (capacity && current >= capacity * 0.9) {
        warning.classList.remove('hidden');
    } else {
        warning.classList.add('hidden');
    }
});

// Shipment search functionality
document.getElementById('shipmentSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const shipmentSelect = document.getElementById('shipment_id');
    const options = shipmentSelect.getElementsByTagName('option');
    
    for (let i = 1; i < options.length; i++) {
        const option = options[i];
        const tracking = option.dataset.tracking?.toLowerCase() || '';
        const sender = option.dataset.sender?.toLowerCase() || '';
        const recipient = option.dataset.recipient?.toLowerCase() || '';
        const text = option.textContent.toLowerCase();
        
        if (tracking.includes(searchTerm) || 
            sender.includes(searchTerm) || 
            recipient.includes(searchTerm) || 
            text.includes(searchTerm)) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    }
});

// Show selected shipment info
document.getElementById('shipment_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const infoBox = document.getElementById('selectedShipmentInfo');
    
    if (this.value && selectedOption.dataset.tracking) {
        document.getElementById('infoTracking').textContent = selectedOption.dataset.tracking;
        document.getElementById('infoSender').textContent = selectedOption.dataset.sender;
        document.getElementById('infoRecipient').textContent = selectedOption.dataset.recipient;
        infoBox.classList.remove('hidden');
    } else {
        infoBox.classList.add('hidden');
    }
});

// Form validation
document.getElementById('checkInForm').addEventListener('submit', function(e) {
    const warehouseId = document.getElementById('warehouse_id').value;
    const shipmentId = document.getElementById('shipment_id').value;
    
    if (!warehouseId) {
        e.preventDefault();
        alert('Please select a warehouse');
        document.getElementById('warehouse_id').focus();
        return false;
    }
    
    if (!shipmentId) {
        e.preventDefault();
        alert('Please select a shipment/package');
        document.getElementById('shipment_id').focus();
        return false;
    }
});

// Auto-suggest handling notes based on condition
document.getElementById('package_condition').addEventListener('change', function() {
    const notesField = document.getElementById('handling_notes');
    if (notesField.value === '') {
        if (this.value === 'damaged') {
            notesField.placeholder = 'Describe the damage: e.g., "Box crushed on left side, contents may be affected"';
        } else if (this.value === 'requires_attention') {
            notesField.placeholder = 'Describe what attention is needed: e.g., "Package appears wet, needs inspection before delivery"';
        } else {
            notesField.placeholder = 'Add any special handling instructions, damage descriptions, or important notes about this package...';
        }
    }
});
</script>

@endsection