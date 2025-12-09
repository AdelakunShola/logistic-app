@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .form-section {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .form-section:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .form-section h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .checkbox-card {
        padding: 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .checkbox-card:hover {
        background-color: #eff6ff;
        border-color: #3b82f6;
    }
    .checkbox-card input:checked + span {
        color: #2563eb;
        font-weight: 600;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.warehouses.index') }}" class="p-2 hover:bg-white rounded-lg transition-colors duration-200">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Create New Warehouse
                </h1>
                <p class="text-gray-600 mt-1">Add a new warehouse facility to your network</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form id="createWarehouseForm" method="POST" action="{{ route('admin.warehouses.store') }}">
        @csrf

        <!-- Basic Information -->
        <div class="form-section">
            <h3>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Basic Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">Warehouse Code <span class="text-red-500">*</span></label>
                    <input type="text" name="warehouse_code" value="{{ old('warehouse_code') }}" required 
                           placeholder="e.g., WH-LAG-001" class="form-input @error('warehouse_code') border-red-500 @enderror"/>
                    @error('warehouse_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Warehouse Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           placeholder="e.g., Lagos Main Warehouse" class="form-input @error('name') border-red-500 @enderror"/>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="form-input @error('type') border-red-500 @enderror">
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="form-input @error('status') border-red-500 @enderror">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', 'active') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="form-section">
            <h3>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Address Information
            </h3>

            <div class="form-group">
                <label class="form-label">Full Address <span class="text-red-500">*</span></label>
                <textarea name="address" required rows="3" placeholder="Enter complete warehouse address" 
                          class="form-input @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group">
                    <label class="form-label">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city') }}" required 
                           placeholder="e.g., Lagos" class="form-input @error('city') border-red-500 @enderror"/>
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">State <span class="text-red-500">*</span></label>
                    <input type="text" name="state" value="{{ old('state') }}" required 
                           placeholder="e.g., Lagos" class="form-input @error('state') border-red-500 @enderror"/>
                    @error('state')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Postal Code</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" 
                           placeholder="e.g., 100001" class="form-input @error('postal_code') border-red-500 @enderror"/>
                    @error('postal_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="0.00000001" name="latitude" value="{{ old('latitude') }}" 
                           placeholder="e.g., 6.5244" class="form-input @error('latitude') border-red-500 @enderror"/>
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="0.00000001" name="longitude" value="{{ old('longitude') }}" 
                           placeholder="e.g., 3.3792" class="form-input @error('longitude') border-red-500 @enderror"/>
                    @error('longitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="form-section">
            <h3>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Contact Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" 
                           placeholder="e.g., +234 123 456 7890" class="form-input @error('phone') border-red-500 @enderror"/>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           placeholder="e.g., warehouse@company.com" class="form-input @error('email') border-red-500 @enderror"/>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Manager Name</label>
                    <input type="text" name="manager_name" value="{{ old('manager_name') }}" 
                           placeholder="e.g., John Doe" class="form-input @error('manager_name') border-red-500 @enderror"/>
                    @error('manager_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Manager Phone</label>
                    <input type="tel" name="manager_phone" value="{{ old('manager_phone') }}" 
                           placeholder="e.g., +234 123 456 7890" class="form-input @error('manager_phone') border-red-500 @enderror"/>
                    @error('manager_phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Capacity & Operations -->
        <div class="form-section">
            <h3>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Capacity & Operations
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="form-group">
                    <label class="form-label">Storage Capacity <span class="text-red-500">*</span></label>
                    <input type="number" name="storage_capacity" value="{{ old('storage_capacity') }}" required min="0" 
                           placeholder="e.g., 10000" class="form-input @error('storage_capacity') border-red-500 @enderror"/>
                    <p class="text-xs text-gray-500 mt-1">Max packages</p>
                    @error('storage_capacity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Area (sqm)</label>
                    <input type="number" step="0.01" name="area_sqm" value="{{ old('area_sqm') }}" min="0" 
                           placeholder="e.g., 5000" class="form-input @error('area_sqm') border-red-500 @enderror"/>
                    @error('area_sqm')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Loading Docks</label>
                    <input type="number" name="loading_docks" value="{{ old('loading_docks') }}" min="0" 
                           placeholder="e.g., 10" class="form-input @error('loading_docks') border-red-500 @enderror"/>
                    @error('loading_docks')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Staff Count</label>
                    <input type="number" name="staff_count" value="{{ old('staff_count') }}" min="0" 
                           placeholder="e.g., 50" class="form-input @error('staff_count') border-red-500 @enderror"/>
                    @error('staff_count')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Operating Hours -->
        <div class="form-section">
            <h3>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Operating Hours
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">Opening Time</label>
                    <input type="time" name="opening_time" value="{{ old('opening_time') }}" 
                           class="form-input @error('opening_time') border-red-500 @enderror"/>
                    @error('opening_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Closing Time</label>
                    <input type="time" name="closing_time" value="{{ old('closing_time') }}" 
                           class="form-input @error('closing_time') border-red-500 @enderror"/>
                    @error('closing_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Operating Days</label>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                    @foreach($days as $day)
                        <label class="checkbox-card flex items-center">
                            <input type="checkbox" name="operating_days[]" value="{{ $day }}" 
                                   {{ is_array(old('operating_days')) && in_array($day, old('operating_days')) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"/>
                            <span class="ml-2 text-sm">{{ $day }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Features & Capabilities -->
        <div class="form-section">
            <h3>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Features & Capabilities
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <label class="checkbox-card flex items-center">
                    <input type="checkbox" name="is_pickup_point" value="1" 
                           {{ old('is_pickup_point') ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"/>
                    <span class="ml-3 text-sm font-medium">Pickup Point</span>
                </label>

                <label class="checkbox-card flex items-center">
                    <input type="checkbox" name="is_delivery_point" value="1" 
                           {{ old('is_delivery_point') ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"/>
                    <span class="ml-3 text-sm font-medium">Delivery Point</span>
                </label>

                <label class="checkbox-card flex items-center">
                    <input type="checkbox" name="accepts_cod" value="1" 
                           {{ old('accepts_cod') ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"/>
                    <span class="ml-3 text-sm font-medium">Accepts COD</span>
                </label>

                <label class="checkbox-card flex items-center">
                    <input type="checkbox" name="has_cold_storage" value="1" 
                           {{ old('has_cold_storage') ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"/>
                    <span class="ml-3 text-sm font-medium">Cold Storage</span>
                </label>

                <label class="checkbox-card flex items-center">
                    <input type="checkbox" name="has_24h_security" value="1" 
                           {{ old('has_24h_security') ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"/>
                    <span class="ml-3 text-sm font-medium">24/7 Security</span>
                </label>
            </div>
        </div>

        <!-- Financial & Compliance -->
        <div class="form-section">
            <h3>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Financial & Compliance
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">Monthly Rent</label>
                    <input type="number" step="0.01" name="monthly_rent" value="{{ old('monthly_rent') }}" min="0" 
                           placeholder="e.g., 500000" class="form-input @error('monthly_rent') border-red-500 @enderror"/>
                    @error('monthly_rent')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Utility Cost</label>
                    <input type="number" step="0.01" name="utility_cost" value="{{ old('utility_cost') }}" min="0" 
                           placeholder="e.g., 50000" class="form-input @error('utility_cost') border-red-500 @enderror"/>
                    @error('utility_cost')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">License Number</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}" 
                           placeholder="e.g., WH-LIC-2024-001" class="form-input @error('license_number') border-red-500 @enderror"/>
                    @error('license_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">License Expiry</label>
                    <input type="date" name="license_expiry" value="{{ old('license_expiry') }}" 
                           class="form-input @error('license_expiry') border-red-500 @enderror"/>
                    @error('license_expiry')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Last Inspection Date</label>
                    <input type="date" name="last_inspection_date" value="{{ old('last_inspection_date') }}" 
                           class="form-input @error('last_inspection_date') border-red-500 @enderror"/>
                    @error('last_inspection_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Safety Rating</label>
                    <select name="safety_rating" class="form-input @error('safety_rating') border-red-500 @enderror">
                        <option value="">Select Rating</option>
                        @foreach($safetyRatings as $rating)
                            <option value="{{ $rating }}" {{ old('safety_rating') == $rating ? 'selected' : '' }}>
                                {{ $rating }} - {{ $rating == 'A' ? 'Excellent' : ($rating == 'B' ? 'Good' : ($rating == 'C' ? 'Average' : ($rating == 'D' ? 'Below Average' : 'Poor'))) }}
                            </option>
                        @endforeach
                    </select>
                    @error('safety_rating')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="form-section">
            <h3>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Additional Information
            </h3>
            <div class="space-y-6">
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" placeholder="Brief description of the warehouse facility..." 
                              class="form-input @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Special Instructions</label>
                    <textarea name="special_instructions" rows="3" placeholder="Any special handling instructions or notes..." 
                              class="form-input @error('special_instructions') border-red-500 @enderror">{{ old('special_instructions') }}</textarea>
                    @error('special_instructions')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="sticky bottom-0 bg-white rounded-2xl shadow-lg p-6 border border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('admin.warehouses.index') }}" class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 text-center">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-200">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Create Warehouse
                </span>
            </button>
        </div>
    </form>
</div>

@endsection