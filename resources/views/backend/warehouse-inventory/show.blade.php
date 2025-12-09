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

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-3 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                            Inventory Details
                        </h1>
                        <p class="text-gray-600 mt-1">{{ $inventory->shipment->tracking_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.warehouse-inventory.index') }}?inventory={{ $inventory->id }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Details
                </a>
                
                @if($inventory->is_in_storage)
                <form action="{{ route('admin.warehouse-inventory.check-out', $inventory->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-lg hover:from-emerald-700 hover:to-teal-700 shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Check Out Package
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Storage Status -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 {{ $inventory->is_in_storage ? 'bg-blue-100' : 'bg-gray-100' }} rounded-xl">
                    <svg class="w-7 h-7 {{ $inventory->is_in_storage ? 'text-blue-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Status</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ $inventory->is_in_storage ? 'In Storage' : 'Checked Out' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Condition -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 {{ $inventory->package_condition === 'good' ? 'bg-green-100' : ($inventory->package_condition === 'damaged' ? 'bg-red-100' : 'bg-amber-100') }} rounded-xl">
                    <svg class="w-7 h-7 {{ $inventory->package_condition === 'good' ? 'text-green-600' : ($inventory->package_condition === 'damaged' ? 'text-red-600' : 'text-amber-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Condition</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ ucfirst(str_replace('_', ' ', $inventory->package_condition)) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Storage Duration -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 {{ $inventory->is_overdue ? 'bg-red-100' : 'bg-purple-100' }} rounded-xl">
                    <svg class="w-7 h-7 {{ $inventory->is_overdue ? 'text-red-600' : 'text-purple-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Duration</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($inventory->storage_duration) }}h</p>
                    @if($inventory->is_overdue)
                    <span class="text-xs text-red-600 font-medium">Overdue</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Priority Status -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 {{ $inventory->is_priority ? 'bg-amber-100' : 'bg-gray-100' }} rounded-xl">
                    <svg class="w-7 h-7 {{ $inventory->is_priority ? 'text-amber-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Priority</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ $inventory->is_priority ? 'High Priority' : 'Standard' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-3">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Package Information -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Package Information
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tracking Number</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $inventory->shipment->tracking_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Shipment ID</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">#{{ $inventory->shipment_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Recipient</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $inventory->shipment->recipient_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Sender</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $inventory->shipment->sender_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warehouse & Location -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Warehouse & Location
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Warehouse</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $inventory->warehouse->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $inventory->warehouse->warehouse_code ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Warehouse Address</label>
                            <p class="mt-1 text-base text-gray-900">{{ $inventory->warehouse->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Storage Location</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $inventory->storage_location ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Bin Number</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $inventory->bin_number ?? 'Not assigned' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Check-in/Check-out Details -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Timeline
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Checked In At</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $inventory->checked_in_at->format('M d, Y h:i A') }}</p>
                            <p class="text-sm text-gray-600">{{ $inventory->checked_in_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Checked In By</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $inventory->checkedInBy->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Checked Out At</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">
                                {{ $inventory->checked_out_at ? $inventory->checked_out_at->format('M d, Y h:i A') : 'Still in storage' }}
                            </p>
                            @if($inventory->checked_out_at)
                            <p class="text-sm text-gray-600">{{ $inventory->checked_out_at->diffForHumans() }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Checked Out By</label>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $inventory->checkedOutBy->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Handling Notes -->
            @if($inventory->handling_notes)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Handling Notes
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $inventory->handling_notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Quick Actions</h2>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('admin.warehouse-inventory.index') }}?inventory={{ $inventory->id }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Details
                    </a>
                    
                    @if($inventory->shipment)
                    <a href="{{ route('admin.shipments.show', $inventory->shipment_id) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        View Shipment
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.warehouse-inventory.by-warehouse', $inventory->warehouse_id) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        View Warehouse
                    </a>
                </div>
            </div>

            <!-- Flags & Status -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Flags & Status</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Priority Package</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $inventory->is_priority ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $inventory->is_priority ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Special Handling</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $inventory->requires_special_handling ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $inventory->requires_special_handling ? 'Required' : 'Not Required' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Storage Status</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $inventory->is_in_storage ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $inventory->is_in_storage ? 'Active' : 'Checked Out' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Record Info</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <label class="text-xs font-medium text-gray-500">Created</label>
                        <p class="text-sm text-gray-900">{{ $inventory->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Last Updated</label>
                        <p class="text-sm text-gray-900">{{ $inventory->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Inventory ID</label>
                        <p class="text-sm text-gray-900">#{{ $inventory->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection