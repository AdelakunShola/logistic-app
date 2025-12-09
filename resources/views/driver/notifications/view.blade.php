@extends('driver.driver_dashboard')
@section('driver')

<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('driver.notifications.all') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Notifications
        </a>
    </div>

    <!-- Notification Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $notification->title }}</h1>
                <p class="text-gray-600 mb-4">{{ $notification->message }}</p>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        {{ $notification->created_at->diffForHumans() }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize {{ $notification->color_class }}">
                        {{ $notification->type }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Data Details -->
    @if($relatedData)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
        
        @if($notification->related_type === 'Maintenance')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Log Number -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Log Number</label>
                <p class="text-gray-900">{{ $relatedData->log_number }}</p>
            </div>

            <!-- Vehicle -->
            @if($relatedData->vehicle)
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Vehicle</label>
                <p class="text-gray-900">{{ $relatedData->vehicle->plate_number ?? 'N/A' }} - {{ $relatedData->vehicle->model ?? '' }}</p>
            </div>
            @endif

            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Maintenance Type</label>
                <p class="text-gray-900 capitalize">{{ $relatedData->maintenance_type }}</p>
            </div>

            <!-- Date -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Maintenance Date</label>
                <p class="text-gray-900">{{ \Carbon\Carbon::parse($relatedData->maintenance_date)->format('M d, Y') }}</p>
            </div>

            <!-- Cost -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Cost</label>
                <p class="text-gray-900">${{ number_format($relatedData->cost, 2) }}</p>
            </div>

            <!-- Priority -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Priority</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                    @if($relatedData->priority === 'high') bg-red-100 text-red-800
                    @elseif($relatedData->priority === 'medium') bg-yellow-100 text-yellow-800
                    @else bg-green-100 text-green-800
                    @endif">
                    {{ $relatedData->priority }}
                </span>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                    @if($relatedData->status === 'completed') bg-green-100 text-green-800
                    @elseif($relatedData->status === 'pending') bg-blue-100 text-blue-800
                    @else bg-yellow-100 text-yellow-800
                    @endif">
                    {{ $relatedData->status }}
                </span>
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                <p class="text-gray-900 capitalize">{{ $relatedData->category }}</p>
            </div>

            <!-- Description -->
            @if($relatedData->description)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                <p class="text-gray-900">{{ $relatedData->description }}</p>
            </div>
            @endif

            <!-- Vendor -->
            @if($relatedData->vendor_name)
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Vendor</label>
                <p class="text-gray-900">{{ $relatedData->vendor_name }}</p>
            </div>
            @endif

            <!-- Technician -->
            @if($relatedData->technician_name)
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Technician</label>
                <p class="text-gray-900">{{ $relatedData->technician_name }}</p>
            </div>
            @endif

            <!-- Mileage -->
            @if($relatedData->mileage_at_maintenance)
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Mileage</label>
                <p class="text-gray-900">{{ number_format($relatedData->mileage_at_maintenance) }} km</p>
            </div>
            @endif

            <!-- Notes -->
            @if($relatedData->notes)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                <p class="text-gray-900">{{ $relatedData->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex gap-3">
            <a href="" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Maintenance Record
            </a>
            <a href="" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                View All Maintenance
            </a>
        </div>
        @endif
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <p class="text-yellow-800">No additional details available for this notification.</p>
    </div>
    @endif
</div>
@endsection