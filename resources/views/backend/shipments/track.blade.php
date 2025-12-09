@extends('admin.admin_dashboard')
@section('admin')

<div class="space-y-6">
    <div class="flex flex-col space-y-4">
        <!-- Track Shipment Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                <h3 class="sm:text-2xl font-semibold tracking-tight flex items-center gap-2 text-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-navigation h-6 w-6 hidden sm:block" aria-hidden="true">
                        <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
                    </svg>
                    Track Your Shipment
                </h3>
                <div class="text-sm text-muted-foreground">Enter your tracking number to get real-time updates on your shipment</div>
            </div>
            
            <div class="p-4 md:p-6 pt-0">
                <!-- Display Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Tracking Form -->
                <form action="{{ route('admin.shipment.track.search') }}" method="POST">
                    @csrf
                    <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
                        <div class="flex-1">
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" aria-hidden="true">
                                    <path d="m21 21-4.34-4.34"></path>
                                    <circle cx="11" cy="11" r="8"></circle>
                                </svg>
                                <input 
                                    type="text" 
                                    name="tracking_number"
                                    id="tracking_number"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-9" 
                                    placeholder="Enter tracking number (e.g., TRK-2024-001234)" 
                                    value="{{ $shipment->tracking_number ?? old('tracking_number') }}"
                                    required
                                />
                            </div>
                            @error('tracking_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 md:w-auto">
                            Track Shipment
                        </button>
                    </div>
                </form>

                <!-- Recent Tracking Numbers -->
                @if(!empty($recentTracking) && count($recentTracking) > 0)
                    <div class="mt-4">
                        <h4 class="mb-2 text-sm font-medium text-muted-foreground">Recent Tracking Numbers</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($recentTracking as $trackingNum)
                                <a href="{{ route('admin.shipment.track.show', $trackingNum) }}" 
                                   class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground cursor-pointer hover:bg-secondary {{ isset($shipment) && $trackingNum == $shipment->tracking_number ? 'bg-primary text-primary-foreground' : '' }}">
                                    {{ $trackingNum }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Show "How to Track" section only when no shipment is displayed -->
        @if(!$shipment)
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-lg border bg-card p-6">
                <div class="flex items-start gap-4">
                    <div class="rounded-full bg-primary/10 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-primary">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" x2="12" y1="15" y2="3"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-1">Step 1: Enter Tracking Number</h3>
                        <p class="text-sm text-muted-foreground">Type your tracking number in the search box above</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border bg-card p-6">
                <div class="flex items-start gap-4">
                    <div class="rounded-full bg-primary/10 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-primary">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-1">Step 2: View Status</h3>
                        <p class="text-sm text-muted-foreground">Get real-time updates on your shipment location</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border bg-card p-6">
                <div class="flex items-start gap-4">
                    <div class="rounded-full bg-primary/10 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-primary">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-1">Step 3: Stay Updated</h3>
                        <p class="text-sm text-muted-foreground">Monitor delivery progress until your package arrives</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Shipment Details Section - Only show when shipment exists -->
    @if($shipment)
    <!-- Shipment Details Card -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Shipment {{ $shipment->tracking_number }}</h2>
                    <div class="flex items-center gap-2 mt-1 text-sm text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        {{ $shipment->shipment_type == 'standard' ? 'LTL' : strtoupper($shipment->shipment_type) }} Shipment
                        <span class="mx-1">•</span>
                        {{ $shipment->number_of_items }} {{ Str::plural('package', $shipment->number_of_items) }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.shipment.track.index') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                        New Search
                    </a>
                    <button onclick="window.print()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Print
                    </button>
                    <button onclick="copyTrackingNumber()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        Copy
                    </button>
                    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                            <polyline points="16 6 12 2 8 6"></polyline>
                            <line x1="12" y1="2" x2="12" y2="15"></line>
                        </svg>
                        Share
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-6 pt-0">
            <!-- Status Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <div class="text-sm text-muted-foreground mb-1">Status</div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-{{ $shipment->status_badge_color }}-100 text-{{ $shipment->status_badge_color }}-800">
                            {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                        </span>
                    </div>
                </div>
                <div>
                    <div class="text-sm text-muted-foreground mb-1">Estimated Delivery</div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span class="font-medium">{{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('D, M d') : $shipment->preferred_delivery_date->format('D, M d') }}</span>
                    </div>
                </div>
                <!--<div>
                    <div class="text-sm text-muted-foreground mb-1">Current Location</div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="font-medium">{{ $currentLocation }}</span>
                    </div>
                </div>-->
                <div>
                    <div class="text-sm text-muted-foreground mb-1">Last Updated</div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span class="font-medium">{{ $shipment->trackingHistory->first()->created_at->format('h:i A') }} • {{ $shipment->trackingHistory->first()->created_at->format('M d') }}</span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
          @php
    $stages = [
        'pending' => ['label' => 'Pending', 'progress' => 10],
        'picked_up' => ['label' => 'Picked Up', 'progress' => 30],
        'in_transit' => ['label' => 'In Transit', 'progress' => 65],
        'out_for_delivery' => ['label' => 'Out for Delivery', 'progress' => 85],
        'delivered' => ['label' => 'Delivered', 'progress' => 100],
    ];
    
    $currentStageIndex = array_search($shipment->status, array_keys($stages));
@endphp

<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <span class="text-sm font-medium">Shipment Progress</span>
        <span class="text-sm text-muted-foreground">{{ $progress }}% Complete</span>
    </div>
    
    <div class="relative">
        <!-- Horizontal Progress Line -->
        <div class="absolute top-8 left-0 right-0 h-1 bg-gray-200">
            <div class="bg-primary h-full transition-all duration-500" style="width: {{ $progress }}%"></div>
        </div>
        
        <!-- Progress Steps -->
        <div class="relative flex justify-between">
            @foreach($stages as $stageKey => $stage)
                @php
                    $stageIndex = array_search($stageKey, array_keys($stages));
                    $isCompleted = $stageIndex < $currentStageIndex;
                    $isCurrent = $stageKey === $shipment->status;
                    $isPending = $stageIndex > $currentStageIndex;
                @endphp
                
                <div class="flex flex-col items-center" style="flex: 1;">
                    <div class="relative z-10 flex-shrink-0 mb-3">
                        @if($isCompleted)
                            <!-- Completed Step -->
                            <div class="w-16 h-16 rounded-full bg-gray-900 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                        @elseif($isCurrent)
                            <!-- Current Step -->
                            <div class="w-16 h-16 rounded-full bg-gray-900 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-gray-400"></div>
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full"></div>
                        @else
                            <!-- Pending Step -->
                            <div class="w-16 h-16 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center">
                                <span class="text-gray-400 font-medium text-lg">{{ $stageIndex + 1 }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="text-center">
                        <div class="font-medium text-sm {{ $isPending ? 'text-gray-400' : '' }}">{{ $stage['label'] }}</div>
                        <div class="text-xs text-muted-foreground mt-1"></div>
                        
                        @if($isCurrent)
                            <div class="inline-block mt-1 px-2 py-1 bg-gray-900 text-white text-xs rounded-full">Current</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Additional Info -->
    <div class="mt-6 pt-4 border-t border-gray-200">
        <div class="flex items-center justify-between text-xs text-muted-foreground">
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Shipped {{ $shipment->pickup_date ? $shipment->pickup_date->format('M d') : 'TBD' }}
            </div>
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
            </div>
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Estimated Delivery {{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('M d') : 'TBD' }}
            </div>
        </div>
    </div>
</div>






            <!-- Origin and Destination -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-muted rounded-lg">
                <div>
                    <div class="text-xs text-muted-foreground mb-1">From <span class="ml-20">Origin</span></div>
                    <div class="font-semibold text-lg">{{ $shipment->pickup_address }}, {{ $shipment->pickup_address_line2 }}, {{ $shipment->pickup_city }}, {{ $shipment->pickup_state }}, {{ $shipment->pickup_country }}</div>
                    <div class="text-sm text-muted-foreground">Shipment created on {{ $shipment->pickup_date ? $shipment->pickup_date->format('l, F d, Y') : 'Pending' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted-foreground mb-1">To <span class="ml-20">Destination</span></div>
                    <div class="font-semibold text-lg">{{ $shipment->delivery_address }}, {{ $shipment->delivery_address_line2 }}, {{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}, {{ $shipment->delivery_country }}</div>
                    <div class="text-sm text-muted-foreground">Expected by {{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('l, F d, Y') : $shipment->preferred_delivery_date->format('l, F d, Y') }}</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-4">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="showTab('timeline')" class="tab-button active border-b-2 border-primary py-4 px-1 text-sm font-medium text-primary" data-tab="timeline">
                        Timeline
                    </button>
                    <!--<button onclick="showTab('map')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-muted-foreground hover:text-foreground hover:border-gray-300" data-tab="map">
                        Map View
                    </button>--->
                    <button onclick="showTab('details')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-muted-foreground hover:text-foreground hover:border-gray-300" data-tab="details">
                        Details
                    </button>
                    <button onclick="showTab('documents')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-muted-foreground hover:text-foreground hover:border-gray-300" data-tab="documents">
                        Documents
                    </button>
                </nav>
            </div>

            <!-- Tab Content - Timeline -->
            <div id="timeline-content" class="tab-content">
                <h3 class="text-lg font-semibold mb-2">Shipment Timeline</h3>
                <p class="text-sm text-muted-foreground mb-6">Track the journey of your shipment from origin to destination</p>
                
                <div class="space-y-6">
                    @foreach($shipment->trackingHistory as $index => $tracking)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            @if($index === 0)
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="m9 12 2 2 4-4"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-muted text-muted-foreground font-medium text-sm">
                                    {{ $index + 1 }}
                                </div>
                            @endif
                            @if($index < count($shipment->trackingHistory) - 1)
                                <div class="w-0.5 h-full bg-border mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-6">
                            <div class="font-semibold">{{ $tracking->description ?? ucfirst(str_replace('_', ' ', $tracking->status)) }}</div>
                           
                            <div class="text-xs text-muted-foreground mt-1">{{ $tracking->formatted_date }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab Content - Map (Hidden by default) -->
           <!-- <div id="map-content" class="tab-content hidden">
                <h3 class="text-lg font-semibold mb-2">Map View</h3>
                <p class="text-sm text-muted-foreground mb-4">Visual representation of shipment route</p>
                <div class="bg-muted rounded-lg p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-muted-foreground">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <p class="text-muted-foreground">Map view coming soon</p>
                </div>
            </div>-->

           <!-- Tab Content - Details -->
<div id="details-content" class="tab-content hidden">
    <h3 class="text-lg font-semibold mb-2">Shipment Details</h3>
    <p class="text-sm text-muted-foreground mb-4">Complete information about this shipment</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Shipment & Pickup Info -->
        <div class="space-y-6">
            <div>
                <h4 class="font-semibold mb-3">Shipment Information</h4>
                <div class="space-y-2 text-sm">
                    @if(!empty($shipment->tracking_number))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Tracking Number:</span>
                            <span class="font-medium">{{ $shipment->tracking_number }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->shipment_type))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Type:</span>
                            <span class="font-medium">{{ ucfirst($shipment->shipment_type) }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->number_of_items))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Items:</span>
                            <span class="font-medium">{{ $shipment->number_of_items }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->total_weight))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Weight:</span>
                            <span class="font-medium">{{ $shipment->total_weight }} kg</span>
                        </div>
                    @endif
                    @if(!empty($shipment->delivery_priority))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Priority:</span>
                            <span class="font-medium">{{ ucfirst($shipment->delivery_priority) }}</span>
                        </div>
                    @endif
                    @if($shipment->signature_required)
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Signature Required:</span>
                            <span class="font-medium text-yellow-600">Yes</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pickup Info -->
            <div>
                <h4 class="font-semibold mb-3">Pickup / Sender's Information</h4>
                <div class="space-y-2 text-sm">
                    @if(!empty($shipment->pickup_company_name))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Company:</span>
                            <span class="font-medium">{{ $shipment->pickup_company_name }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->pickup_contact_name))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Contact:</span>
                            <span class="font-medium">{{ $shipment->pickup_contact_name }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->pickup_contact_phone))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Phone:</span>
                            <span class="font-medium">{{ $shipment->pickup_contact_phone }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->pickup_contact_email))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Email:</span>
                            <span class="font-medium">{{ $shipment->pickup_contact_email }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->pickup_address))
                        <div>
                            <span class="text-muted-foreground">Address:</span>
                            <p class="font-medium mt-1">{{ $shipment->pickup_address }}, {{ $shipment->pickup_address_line2 }}, {{ $shipment->pickup_city }}, {{ $shipment->pickup_state }}, {{ $shipment->pickup_country }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delivery + Pricing Info -->
        <div class="space-y-6">
            <div>
                <h4 class="font-semibold mb-3">Delivery Information</h4>
                <div class="space-y-2 text-sm">
                    @if(!empty($shipment->delivery_company_name))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Company:</span>
                            <span class="font-medium">{{ $shipment->delivery_company_name }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->delivery_contact_name))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Contact:</span>
                            <span class="font-medium">{{ $shipment->delivery_contact_name }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->delivery_contact_phone))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Phone:</span>
                            <span class="font-medium">{{ $shipment->delivery_contact_phone }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->delivery_contact_email))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Email:</span>
                            <span class="font-medium">{{ $shipment->delivery_contact_email }}</span>
                        </div>
                    @endif
                    @if(!empty($shipment->delivery_address))
                        <div>
                            <span class="text-muted-foreground">Address:</span>
                            <p class="font-medium mt-1">{{ $shipment->delivery_address }}, {{ $shipment->delivery_address_line2 }}, {{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}, {{ $shipment->delivery_country }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pricing Info -->
            <div>
                <h4 class="font-semibold mb-3">Pricing Breakdown</h4>
                <div class="space-y-2 text-sm">
                    @foreach ([
                        'base_price' => 'Base Price',
                        'weight_charge' => 'Weight Charge',
                        'distance_charge' => 'Distance Charge',
                        'priority_charge' => 'Priority Charge',
                        'tax_amount' => 'Tax',
                        'discount_amount' => 'Discount',
                        'insurance_fee' => 'Insurance Fee',
                        'additional_services_fee' => 'Additional Services',
                        'total_amount' => 'Total Amount'
                    ] as $field => $label)
                        @if(!empty($shipment->$field))
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">{{ $label }}:</span>
                                <span class="font-medium">${{ number_format($shipment->$field, 2) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Dates + Additional Info -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="font-semibold mb-3">Shipment Dates</h4>
            <div class="space-y-2 text-sm">
                @foreach ([
                    'pickup_date' => 'Pickup Date',
                    'pickup_scheduled_date' => 'Scheduled Pickup',
                    'preferred_delivery_date' => 'Preferred Delivery',
                    'expected_delivery_date' => 'Expected Delivery',
                    'actual_delivery_date' => 'Actual Delivery'
                ] as $field => $label)
                    @if(!empty($shipment->$field))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">{{ $label }}:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($shipment->$field)->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div>
            <h4 class="font-semibold mb-3">Additional Information</h4>
            <div class="space-y-2 text-sm">
                @if(!empty($shipment->special_instructions))
                    <div>
                        <span class="text-muted-foreground">Special Instructions:</span>
                        <p class="font-medium mt-1">{{ $shipment->special_instructions }}</p>
                    </div>
                @endif
                @if(!empty($shipment->delivery_signature))
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Delivery Signature:</span>
                        <span class="font-medium">{{ $shipment->delivery_signature }}</span>
                    </div>
                @endif
                @if(!empty($shipment->delivery_photo))
                    <div>
                        <span class="text-muted-foreground">Delivery Photo:</span>
                        <img src="{{ asset('storage/'.$shipment->delivery_photo) }}" alt="Delivery Photo" class="mt-2 rounded-lg shadow w-32 h-32 object-cover">
                    </div>
                @endif
                @if(!empty($shipment->delivery_notes))
                    <div>
                        <span class="text-muted-foreground">Delivery Notes:</span>
                        <p class="font-medium mt-1">{{ $shipment->delivery_notes }}</p>
                    </div>
                @endif
                @if(!empty($shipment->cancellation_reason))
                    <div>
                        <span class="text-muted-foreground">Cancellation Reason:</span>
                        <p class="font-medium mt-1 text-red-600">{{ $shipment->cancellation_reason }}</p>
                    </div>
                @endif
                @if(!empty($shipment->delivery_attempts))
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Delivery Attempts:</span>
                        <span class="font-medium">{{ $shipment->delivery_attempts }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


            <!-- Tab Content - Documents (Hidden by default) -->
            <div id="documents-content" class="tab-content hidden">
                <h3 class="text-lg font-semibold mb-2">Documents</h3>
                <p class="text-sm text-muted-foreground mb-4">Shipping documents and proof of delivery</p>
                <div class="bg-muted rounded-lg p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-muted-foreground">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <p class="text-muted-foreground">No documents available yet</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Need Assistance Section -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl font-semibold">Need Assistance?</h3>
            <p class="text-sm text-muted-foreground">Contact our support team for help with this shipment</p>
        </div>
        
        <div class="p-4 md:p-6 pt-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Carrier Support -->
                <div class="rounded-lg border p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="rounded-full bg-primary/10 p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                <path d="M7 11v8a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-8"></path>
                                <rect x="3" y="3" width="18" height="8" rx="1"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg">Carrier Support</h4>
                            <p class="text-sm text-muted-foreground">{{ $carrierSupport['name'] ?? 'FastFreight Logistics' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-muted-foreground">Phone</span>
                            <a href="tel:{{ $carrierSupport['phone'] ?? '+1 (800) 555-1234' }}" class="font-medium hover:underline">{{ $carrierSupport['phone'] ?? '+1 (800) 555-1234' }}</a>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-muted-foreground">Email</span>
                            <a href="mailto:{{ $carrierSupport['email'] ?? 'support@fastfreight.com' }}" class="font-medium hover:underline">{{ $carrierSupport['email'] ?? 'support@fastfreight.com' }}</a>
                        </div>
                    </div>
                    
                    <button class="w-full mt-4 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        Contact Carrier
                    </button>
                </div>

                <!-- Report Issue -->
                <div class="rounded-lg border p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="rounded-full bg-orange-100 p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg">Report an Issue</h4>
                            <p class="text-sm text-muted-foreground">Having problems with this shipment?</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium mb-2">Report issues such as:</p>
                        <ul class="text-sm text-muted-foreground space-y-1 ml-4">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-muted-foreground"></span>
                                Damaged packages
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-muted-foreground"></span>
                                Delivery delays
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-muted-foreground"></span>
                                Incorrect tracking information
                            </li>
                        </ul>
                    </div>
                    
                    <button onclick="openReportModal()" class="w-full mt-4 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        Report Issue
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Report Issue Modal - Only show when shipment exists -->
@if($shipment)
<div id="reportModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
            <div class="flex items-center gap-3">
                <div class="rounded-full bg-orange-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold">Report an Issue</h3>
            </div>
            <button type="button" onclick="closeReportModal()" class="text-muted-foreground hover:text-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <form id="reportIssueForm" action="{{ route('admin.shipment.track.reportIssue', $shipment->tracking_number) }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-5">
                <!-- Issue Type -->
                <div>
                    <label for="issue_type" class="block text-sm font-medium mb-2">
                        Issue Type <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="issue_type" 
                        id="issue_type" 
                        required 
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 transition-colors"
                    >
                        <option value="">Select an issue type</option>
                        <option value="damaged">Damaged Package</option>
                        <option value="delayed">Delivery Delay</option>
                        <option value="incorrect_tracking">Incorrect Tracking Information</option>
                        <option value="lost">Lost/Missing Package</option>
                        <option value="wrong_address">Wrong Address</option>
                        <option value="missing_items">Missing Items</option>
                        <option value="poor_service">Poor Service</option>
                        <option value="other">Other</option>
                    </select>
                    @error('issue_type')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4" 
                        required 
                        maxlength="1000"
                        placeholder="Please describe the issue in detail..."
                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 resize-none transition-colors"
                    ></textarea>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-xs text-muted-foreground">Be as specific as possible</p>
                        <p class="text-xs text-muted-foreground">
                            <span id="charCount">0</span>/1000
                        </p>
                    </div>
                    @error('description')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Shipment Info -->
                <div class="rounded-lg bg-muted p-4 space-y-2">
                    <p class="text-sm font-medium">Shipment Information</p>
                    <div class="space-y-1 text-sm text-muted-foreground">
                        <div class="flex items-center justify-between">
                            <span>Tracking Number:</span>
                            <span class="font-mono font-medium text-foreground">{{ $shipment->tracking_number }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Current Status:</span>
                            <span class="font-medium text-foreground capitalize">{{ str_replace('_', ' ', $shipment->status) }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Info Alert -->
                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                    <div class="flex gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 flex-shrink-0 mt-0.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <div class="text-sm text-blue-900">
                            <p class="font-medium mb-1">What happens next?</p>
                            <p class="text-blue-800">Our support team will review your report and contact you within 24-48 hours.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3 pt-2">
                    <button 
                        type="button" 
                        onclick="closeReportModal()" 
                        class="flex-1 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        id="submitReportBtn"
                        class="flex-1 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span id="submitBtnText">Submit Report</span>
                        <svg id="submitBtnLoader" class="hidden animate-spin ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<style>
    /* Smooth animations */
    #reportModal {
        animation: fadeIn 0.2s ease-out;
    }

    #reportModal > div {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Custom scrollbar */
    #reportModal > div {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }

    #reportModal > div::-webkit-scrollbar {
        width: 8px;
    }

    #reportModal > div::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    #reportModal > div::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    #reportModal > div::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endif

<script>
// Tab switching functionality
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-primary', 'text-primary');
        button.classList.add('border-transparent', 'text-muted-foreground');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active state to clicked button
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    activeButton.classList.add('active', 'border-primary', 'text-primary');
    activeButton.classList.remove('border-transparent', 'text-muted-foreground');
}

// Modal functions
function openReportModal() {
        const modal = document.getElementById('reportModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus on first input
        setTimeout(() => {
            document.getElementById('issue_type')?.focus();
        }, 100);
    }

    function closeReportModal() {
        const modal = document.getElementById('reportModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reset form
        const form = document.getElementById('reportIssueForm');
        form?.reset();
        
        // Reset character count
        const charCount = document.getElementById('charCount');
        if (charCount) charCount.textContent = '0';
    }

    // Character counter
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        
        if (textarea && charCount) {
            textarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }

        // Form submission handler
        const form = document.getElementById('reportIssueForm');
        const submitBtn = document.getElementById('submitReportBtn');
        const submitBtnText = document.getElementById('submitBtnText');
        const submitBtnLoader = document.getElementById('submitBtnLoader');
        
        form?.addEventListener('submit', function(e) {
            // Disable button and show loader
            submitBtn.disabled = true;
            submitBtnText.textContent = 'Submitting...';
            submitBtnLoader.classList.remove('hidden');
        });

        // Close modal on outside click
        document.getElementById('reportModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReportModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('reportModal');
                if (!modal.classList.contains('hidden')) {
                    closeReportModal();
                }
            }
        });

        // Auto-open modal if there are validation errors
        @if($errors->has('issue_type') || $errors->has('description'))
            openReportModal();
        @endif
    });
// Copy tracking number
function copyTrackingNumber() {
    const trackingNumber = '{{ $shipment->tracking_number ?? "" }}';
    if (trackingNumber) {
        navigator.clipboard.writeText(trackingNumber).then(() => {
            alert('Tracking number copied to clipboard!');
        });
    }
}

// Close modal when clicking outside
document.getElementById('reportModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeReportModal();
    }
});
</script>

@endsection