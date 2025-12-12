@extends('driver.driver_dashboard')
@section('driver') 

<script>
// Safety check - remove any stuck modals on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded - checking for stuck elements');
    
    // Force close all modals
    const modals = document.querySelectorAll('[id$="-modal"]');
    modals.forEach(modal => {
        if (modal && !modal.classList.contains('hidden')) {
            console.log('Found open modal:', modal.id);
            modal.classList.add('hidden');
        }
    });
    
    // Ensure body is interactive
    document.body.style.pointerEvents = '';
    document.body.style.overflow = '';
    
    console.log('Cleanup complete');
});
</script>

<main class="flex-1 overflow-y-auto p-4 md:p-6">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-wrap gap-3 items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Welcome Back, {{ Auth::user()->first_name }}!</h1>
                <p class="text-muted-foreground">Your delivery dashboard for {{ date('l, F j, Y') }}</p>
            </div>
            <div class="flex gap-2">
                <!-- Status Toggle -->
                <button onclick="toggleStatus()" id="statusBtn" class="inline-flex items-center justify-center gap-2 rounded-full px-4 py-2 text-sm font-semibold bg-green-500 text-white hover:bg-green-600 transition-colors">
                    <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                    <span id="statusText">Available for Delivery</span>
                </button>
            </div>
        </div>

        <!-- Driver Stats Cards -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <!-- Today's Deliveries -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Today's Deliveries</h3>
                    <div class="p-2 rounded-full text-blue-600 bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold">{{ $todayDeliveries ?? 8 }}</div>
                            <p class="text-xs text-muted-foreground mt-1">{{ $completedToday ?? 5 }} completed</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-green-600">{{ $pendingDeliveries ?? 3 }}</span>
                            <p class="text-xs text-muted-foreground">pending</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Earnings -->
            <!--<div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Today's Earnings</h3>
                    <div class="p-2 rounded-full text-green-600 bg-green-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <line x1="12" x2="12" y1="2" y2="22"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">${{ number_format($todayEarnings ?? 156.50, 2) }}</div>
                    <p class="text-xs text-muted-foreground mt-1">This week: ${{ number_format($weekEarnings ?? 892.00, 2) }}</p>
                </div>
            </div> -->

            <!-- Distance Covered -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Completed Trips </h3>
                    <div class="p-2 rounded-full text-purple-600 bg-purple-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">{{ $totalTrips ?? 12 }} trips</div>
                  
                </div>
            </div>

            <!-- My Rating -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">My Rating</h3>
                    <div class="p-2 rounded-full text-yellow-600 bg-yellow-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">{{ number_format(Auth::user()->rating ?? 4.8, 1) }}/5.0</div>
                    <p class="text-xs text-muted-foreground mt-1">Based on {{ $totalRatings ?? 124 }} ratings</p>
                </div>
            </div>
        </div>

        <!-- Current Route & My Vehicle -->
        <div class="grid gap-6 md:grid-cols-2">
          <!-- Performance Metrics Card -->
<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
        <h3 class="text-xl font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-500">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
            </svg>
            Today's Performance
        </h3>
    </div>
    <div class="p-4 md:p-6 pt-0 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-2xl font-bold text-green-600">{{ $completedToday }}</p>
                <p class="text-sm text-muted-foreground">Completed</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-2xl font-bold text-blue-600">{{ $pendingDeliveries }}</p>
                <p class="text-sm text-muted-foreground">Pending</p>
            </div>
        </div>

        @php
            $onTimeRate = $completedToday > 0 ? round(($completedToday / $todayDeliveries) * 100) : 0;
            $avgRating = $averageRating ?? 0;
        @endphp

        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>On-Time Delivery</span>
                    <span class="font-semibold">{{ $onTimeRate }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $onTimeRate }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Customer Rating</span>
                    <span class="font-semibold flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-400">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        {{ number_format($avgRating, 1) }}/5.0
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ ($avgRating / 5) * 100 }}%"></div>
                </div>
            </div>
        </div>

       
    </div>
</div>

           <!-- My Vehicle Card -->
<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
        <h3 class="text-xl font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-purple-500">
                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                <path d="M15 18H9"></path>
                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                <circle cx="17" cy="18" r="2"></circle>
                <circle cx="7" cy="18" r="2"></circle>
            </svg>
            My Vehicle
        </h3>
    </div>
    <div class="p-4 md:p-6 pt-0 space-y-4">
        @if($vehicle)
            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                <div>
                    <p class="font-semibold">{{ $vehicle->vehicle_number }}</p>
                    <p class="text-sm text-muted-foreground">
                        {{ ucfirst($vehicle->vehicle_type) }}
                        @if($vehicle->make && $vehicle->model)
                            - {{ $vehicle->make }} {{ $vehicle->model }}
                        @endif
                    </p>
                    @if($vehicle->license_plate)
                        <p class="text-xs text-muted-foreground mt-1">{{ $vehicle->license_plate }}</p>
                    @endif
                </div>
                <span class="bg-{{ $vehicle->status === 'active' ? 'green' : ($vehicle->status === 'maintenance' ? 'yellow' : 'red') }}-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                    {{ ucfirst($vehicle->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4">
               
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-muted-foreground">Mileage</p>
                    <p class="text-lg font-bold mt-1">
                        {{ number_format($vehicle->mileage, 0) }} km
                    </p>
                </div>

                 @if($vehicle->capacity_weight || $vehicle->capacity_volume)
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                @if($vehicle->capacity_weight)
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-muted-foreground">Capacity</p>
                    <p class="text-sm font-bold mt-1">{{ number_format($vehicle->capacity_weight, 0) }} kg</p>
                </div>
                @endif
               
            </div>
            @endif
            </div>

           

            @if($vehicle->alert_type !== 'none' && $vehicle->alert_message)
            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600 flex-shrink-0">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800">{{ ucfirst(str_replace('_', ' ', $vehicle->alert_type)) }}</p>
                        <p class="text-xs text-yellow-700 mt-1">{{ $vehicle->alert_message }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($vehicle->next_service_date)
            <div class="text-sm text-muted-foreground text-center">
                Next Service: {{ \Carbon\Carbon::parse($vehicle->next_service_date)->format('M d, Y') }}
            </div>
            @endif

          
        @else
            <div class="text-center py-8">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto text-muted-foreground">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                </div>
                <p class="text-muted-foreground font-medium">No vehicle assigned</p>
                <p class="text-sm text-muted-foreground mt-2">Contact dispatch for vehicle assignment</p>
            </div>
        @endif
    </div>
</div>
        </div>

        <!-- Today's Delivery Schedule -->
     <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
        <h3 class="text-xl font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
            </svg>
            Today's Delivery Schedule
        </h3>
    </div>
    <div class="p-4 md:p-6 pt-0">
        <div class="space-y-4">
            @forelse($todaySchedule as $shipment)
                @php
                    // Determine status styling
                    $statusConfig = match($shipment->status) {
                        'delivered' => ['bg' => 'bg-green-50', 'badge' => 'bg-green-500', 'icon' => 'check-circle'],
                        'in_transit', 'out_for_delivery' => ['bg' => 'bg-blue-50', 'badge' => 'bg-blue-500', 'icon' => 'truck'],
                        default => ['bg' => 'bg-gray-50', 'badge' => 'bg-gray-200', 'icon' => 'clock']
                    };
                @endphp

                <div class="flex items-start gap-4 p-4 rounded-lg border {{ $statusConfig['bg'] }}">
                    <div class="p-2 rounded-full {{ $statusConfig['badge'] }} text-white">
                        @if($statusConfig['icon'] === 'check-circle')
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                        @elseif($statusConfig['icon'] === 'truck')
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                <path d="M15 18H9"></path>
                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                <circle cx="17" cy="18" r="2"></circle>
                                <circle cx="7" cy="18" r="2"></circle>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 6v6l4 2"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold">#{{ $shipment->tracking_number }}</p>
                            <span class="text-xs {{ $statusConfig['badge'] }} text-white px-2 py-1 rounded-full">
                                {{ ucwords(str_replace('_', ' ', $shipment->status)) }}
                            </span>
                        </div>
                        <p class="text-sm text-muted-foreground mt-1">
                            {{ $shipment->delivery_address }}, {{ $shipment->delivery_city }}
                        </p>
                        <p class="text-xs text-muted-foreground mt-1">
                            @if($shipment->status === 'delivered')
                                Delivered at {{ $shipment->actual_delivery_date?->format('g:i A') }}
                            @elseif($shipment->status === 'in_transit' || $shipment->status === 'out_for_delivery')
                                @if($shipment->estimated_duration)
                                    ETA: {{ $shipment->estimated_duration }} minutes
                                @else
                                    Expected: {{ $shipment->expected_delivery_date?->format('g:i A') }}
                                @endif
                            @else
                                Scheduled for {{ $shipment->pickup_date?->format('g:i A') }}
                            @endif
                        </p>

                        @if($shipment->status !== 'delivered')
                            <div class="mt-2 flex gap-2">
                                @if($shipment->status === 'in_transit' || $shipment->status === 'out_for_delivery')
                                    <form action="{{ route('driver.shipments.deliver', $shipment->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                            Mark as Delivered
                                        </button>
                                    </form>
                                @endif
                                <a href="tel:{{ $shipment->delivery_contact_phone }}" class="text-xs border px-3 py-1 rounded hover:bg-gray-100">
                                    Contact Customer
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto mb-3 opacity-50">
                        <path d="M12 6v6l4 2"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <p>No deliveries scheduled for today</p>
                </div>
            @endforelse
        </div>

        @if($todaySchedule->count() > 0)
            <a href="{{ route('driver.shipments.index') }}" class="block w-full mt-4 border border-input bg-background hover:bg-accent py-2 px-4 rounded-lg font-medium text-center">
                View Full Schedule
            </a>
        @endif
    </div>
</div>

        <!-- Quick Actions -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                <h3 class="text-xl font-semibold">Quick Actions</h3>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <button class="flex flex-col items-center gap-2 p-4 border rounded-lg hover:bg-accent hover:shadow-md transition-all">
                        <div class="p-2 rounded-full bg-blue-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                                <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                                <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                                <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Scan Package</span>
                    </button>

                    <button class="flex flex-col items-center gap-2 p-4 border rounded-lg hover:bg-accent hover:shadow-md transition-all">
                        <div class="p-2 rounded-full bg-green-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Proof of Delivery</span>
                    </button>

                    <button class="flex flex-col items-center gap-2 p-4 border rounded-lg hover:bg-accent hover:shadow-md transition-all">
                        <div class="p-2 rounded-full bg-purple-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Contact Support</span>
                    </button>

                    <button class="flex flex-col items-center gap-2 p-4 border rounded-lg hover:bg-accent hover:shadow-md transition-all">
                        <div class="p-2 rounded-full bg-red-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Report Issue</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function toggleStatus() {
    const btn = document.getElementById('statusBtn');
    const text = document.getElementById('statusText');
    
    if (btn.classList.contains('bg-green-500')) {
        btn.classList.remove('bg-green-500', 'hover:bg-green-600');
        btn.classList.add('bg-gray-500', 'hover:bg-gray-600');
        text.textContent = 'Offline';
        
        // Update status in backend
        fetch('/driver/update-status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: 'offline' })
        });
    } else {
        btn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
        btn.classList.add('bg-green-500', 'hover:bg-green-600');
        text.textContent = 'Available for Delivery';
        
        fetch('/driver/update-status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: 'available' })
        });
    }
}
</script>



@endsection