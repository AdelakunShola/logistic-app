@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.warehouse-inventory.show', $inventory->id) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="font-medium">Back to Details</span>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    Edit Inventory
                </h1>
                <p class="text-gray-600 mt-1">Update storage details and package information</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.warehouse-inventory.update', $inventory->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Storage Location -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Storage Location
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
                                       value="{{ old('storage_location', $inventory->storage_location) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                       placeholder="e.g., Aisle 3, Shelf B2">
                                @error('storage_location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="bin_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    Bin Number
                                </label>
                                <input type="text" 
                                       id="bin_number" 
                                       name="bin_number" 
                                       value="{{ old('bin_number', $inventory->bin_number) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                       placeholder="e.g., BIN-001">
                                @error('bin_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                            Package Condition
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
                            <option value="good" {{ old('package_condition', $inventory->package_condition) == 'good' ? 'selected' : '' }}>
                                Good Condition
                            </option>
                            <option value="damaged" {{ old('package_condition', $inventory->package_condition) == 'damaged' ? 'selected' : '' }}>
                                Damaged
                            </option>
                            <option value="requires_attention" {{ old('package_condition', $inventory->package_condition) == 'requires_attention' ? 'selected' : '' }}>
                                Requires Attention
                            </option>
                        </select>
                        @error('package_condition')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Handling Notes -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Handling Notes
                        </h2>
                    </div>
                    <div class="p-6">
                        <label for="handling_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Special Instructions
                        </label>
                        <textarea id="handling_notes" 
                                  name="handling_notes" 
                                  rows="5"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                  placeholder="Add any special handling instructions or notes about this package...">{{ old('handling_notes', $inventory->handling_notes) }}</textarea>
                        @error('handling_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Package Info (Read-only) -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                        <h2 class="text-lg font-bold text-white">Package Info</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Tracking Number</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $inventory->shipment->tracking_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Warehouse</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $inventory->warehouse->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Checked In</label>
                            <p class="text-sm text-gray-900">{{ $inventory->checked_in_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Flags & Status -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4">
                        <h2 class="text-lg font-bold text-white">Flags & Status</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Priority Package -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="is_priority" 
                                       name="is_priority" 
                                       value="1"
                                       {{ old('is_priority', $inventory->is_priority) ? 'checked' : '' }}
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
                                       {{ old('requires_special_handling', $inventory->requires_special_handling) ? 'checked' : '' }}
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

                <!-- Action Buttons -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-6 space-y-3">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-lg hover:from-emerald-700 hover:to-teal-700 shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                        <a href="{{ route('admin.warehouse-inventory.show', $inventory->id) }}" class="w-full inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Help Info -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg overflow-hidden border border-blue-200">
                    <div class="p-6">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-blue-900 mb-2">Editing Tips</h3>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li>• Update storage location for better organization</li>
                                    <li>• Mark damaged packages for special attention</li>
                                    <li>• Add handling notes for warehouse staff</li>
                                    <li>• Use priority flag for time-sensitive items</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection