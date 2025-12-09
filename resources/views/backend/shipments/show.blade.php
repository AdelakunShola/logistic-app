@extends('admin.admin_dashboard')
@section('admin')

<div class="space-y-12">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.shipments.index') }}" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold tracking-tight">Shipment Details</h1>
            </div>
            <p class="text-muted-foreground">Complete information about shipment {{ $shipment->tracking_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print
            </button>
            <a href="{{ route('admin.shipments.edit', $shipment->id) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Shipment
            </a>
        </div>
    </div>

    <!-- Status and Quick Info Cards -->
    <div class="grid gap-4 md:grid-cols-4 p-8">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
            <div class="text-sm font-medium text-muted-foreground mb-2">Status</div>
            @php
                $statusColors = [
                    'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '#6b7280'],
                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => '#f59e0b'],
                    'picked_up' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '#3b82f6'],
                    'in_transit' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => '#8b5cf6'],
                    'out_for_delivery' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-800', 'icon' => '#ec4899'],
                    'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '#10b981'],
                    'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => '#ef4444'],
                    'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '#64748b'],
                ];
                $currentStatus = $statusColors[$shipment->status] ?? $statusColors['draft'];
            @endphp
            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $currentStatus['bg'] }} {{ $currentStatus['text'] }}">
                {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
            </span>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
            <div class="text-sm font-medium text-muted-foreground mb-2">Tracking Number</div>
            <div class="font-bold">{{ $shipment->tracking_number }}</div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
            <div class="text-sm font-medium text-muted-foreground mb-2">Expected Delivery</div>
            <div class="font-bold">{{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('M d, Y') : 'TBD' }}</div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
            <div class="text-sm font-medium text-muted-foreground mb-2">Total Cost</div>
            <div class="font-bold">${{ number_format($shipment->total_amount ?? 0, 2) }}</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid gap-6 lg:grid-cols-6 mt-6 p-8">
        <!-- Left Column - Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Shipment Information -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Shipment Information</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm font-medium text-muted-foreground mb-1">Shipment Type</div>
                            <div class="text-base font-semibold capitalize">{{ str_replace('_', ' ', $shipment->shipment_type) }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-muted-foreground mb-1">Service Level</div>
                            <div class="text-base font-semibold capitalize">{{ $shipment->service_level ?? 'Standard' }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-muted-foreground mb-1">Delivery Priority</div>
                            <div class="text-base font-semibold capitalize">{{ $shipment->delivery_priority }}</div>
                        </div>
                        <div>
    <div class="text-sm font-medium text-muted-foreground mb-1">Payment Mode</div>
    <div class="text-base font-semibold capitalize">
        {{ strtoupper($shipment->payment_mode ?? 'Prepaid') }}
        @if($shipment->payment_mode === 'cod')
            <span class="ml-2 text-green-600">${{ number_format($shipment->cod_amount, 2) }}</span>
        @endif
    </div>
</div>
                        <div>
                            <div class="text-sm font-medium text-muted-foreground mb-1">Number of Items</div>
                            <div class="text-base font-semibold">{{ $shipment->number_of_items }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-muted-foreground mb-1">Total Weight</div>
                            <div class="text-base font-semibold">{{ number_format($shipment->total_weight, 2) }} lbs</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-muted-foreground mb-1">Carrier</div>
                            <div class="text-base font-semibold">{{ $shipment->carrier->name ?? 'Not Assigned' }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-muted-foreground mb-1">Assigned Driver</div>
                            <div class="text-base font-semibold">{{ $shipment->assignedDriver ? $shipment->assignedDriver->first_name . ' ' . $shipment->assignedDriver->last_name : 'Not Assigned' }}</div>
                        </div>
                       
                    </div>

                    @if($shipment->special_instructions)
                    <div class="mt-6 p-4 bg-muted rounded-lg">
                        <div class="text-sm font-medium text-muted-foreground mb-2">Special Instructions</div>
                        <div class="text-sm">{{ $shipment->special_instructions }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Route Information -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Route Information</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Origin -->
                        <div>
                            <h4 class="font-semibold mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Origin
                            </h4>
                            <div class="space-y-2 text-sm">
                                @if($shipment->pickup_company_name)
                                <div>
                                    <span class="text-muted-foreground">Company:</span>
                                    <span class="font-medium ml-2">{{ $shipment->pickup_company_name }}</span>
                                </div>
                                @endif
                                @if($shipment->pickup_contact_name)
                                <div>
                                    <span class="text-muted-foreground">Contact:</span>
                                    <span class="font-medium ml-2">{{ $shipment->pickup_contact_name }}</span>
                                </div>
                                @endif
                                <div>
                                    <span class="text-muted-foreground">Address:</span>
                                    <div class="font-medium mt-1">
                                        {{ $shipment->pickup_address }}<br>
                                        @if($shipment->pickup_address_line2)
                                            {{ $shipment->pickup_address_line2 }}<br>
                                        @endif
                                        {{ $shipment->pickup_city }}, {{ $shipment->pickup_state }} {{ $shipment->pickup_postal_code }}
                                    </div>
                                </div>
                                @if($shipment->pickup_contact_phone)
                                <div>
                                    <span class="text-muted-foreground">Phone:</span>
                                    <span class="font-medium ml-2">{{ $shipment->pickup_contact_phone }}</span>
                                </div>
                                @endif
                                @if($shipment->pickup_date)
                                <div>
                                    <span class="text-muted-foreground">Pickup Date:</span>
                                    <span class="font-medium ml-2">{{ $shipment->pickup_date->format('M d, Y h:i A') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Destination -->
                        <div>
                            <h4 class="font-semibold mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Destination
                            </h4>
                            <div class="space-y-2 text-sm">
                                @if($shipment->delivery_company_name)
                                <div>
                                    <span class="text-muted-foreground">Company:</span>
                                    <span class="font-medium ml-2">{{ $shipment->delivery_company_name }}</span>
                                </div>
                                @endif
                                @if($shipment->delivery_contact_name)
                                <div>
                                    <span class="text-muted-foreground">Contact:</span>
                                    <span class="font-medium ml-2">{{ $shipment->delivery_contact_name }}</span>
                                </div>
                                @endif
                                <div>
                                    <span class="text-muted-foreground">Address:</span>
                                    <div class="font-medium mt-1">
                                        {{ $shipment->delivery_address }}<br>
                                        @if($shipment->delivery_address_line2)
                                            {{ $shipment->delivery_address_line2 }}<br>
                                        @endif
                                        {{ $shipment->delivery_city }}, {{ $shipment->delivery_state }} {{ $shipment->delivery_postal_code }}
                                    </div>
                                </div>
                                @if($shipment->delivery_contact_phone)
                                <div>
                                    <span class="text-muted-foreground">Phone:</span>
                                    <span class="font-medium ml-2">{{ $shipment->delivery_contact_phone }}</span>
                                </div>
                                @endif
                                @if($shipment->expected_delivery_date)
                                <div>
                                    <span class="text-muted-foreground">Expected:</span>
                                    <span class="font-medium ml-2">{{ $shipment->expected_delivery_date->format('M d, Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Package Items</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-4">
                        @forelse($shipment->shipmentItems as $item)
                        <div class="p-4 border rounded-lg">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-semibold">{{ $item->description }}</h4>
                                    <p class="text-sm text-muted-foreground capitalize">{{ $item->category ?? 'General Merchandise' }}</p>
                                </div>
                                @if($item->is_hazardous)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-red-100 text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                                        <path d="M12 9v4"></path>
                                        <path d="M12 17h.01"></path>
                                    </svg>
                                    Hazardous
                                </span>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-muted-foreground">Quantity:</span>
                                    <span class="font-medium ml-1">{{ $item->quantity }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Weight:</span>
                                    <span class="font-medium ml-1">{{ number_format($item->weight, 2) }} lbs</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Value:</span>
                                    <span class="font-medium ml-1">${{ number_format($item->value, 2) }}</span>
                                </div>
                                @if($item->length && $item->width && $item->height)
                                <div>
                                    <span class="text-muted-foreground">Dimensions:</span>
                                    <span class="font-medium ml-1">{{ $item->length }}" × {{ $item->width }}" × {{ $item->height }}"</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4">
                                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                <path d="M12 22V12"></path>
                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                            </svg>
                            <p>No items found for this shipment</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tracking History -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Tracking History</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-6">
                        @forelse($shipment->trackingHistory as $index => $tracking)
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
                                <div class="text-sm text-muted-foreground">{{ $tracking->location_name ?? 'N/A' }}</div>
                                <div class="text-xs text-muted-foreground mt-1">{{ $tracking->created_at->format('M d, Y h:i A') }}</div>
                                @if($tracking->updatedBy)
                                <div class="text-xs text-muted-foreground">Updated by: {{ $tracking->updatedBy->first_name }} {{ $tracking->updatedBy->last_name }}</div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-muted-foreground">
                            <p>No tracking history available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Additional Info -->
        <div class="space-y-6">
            <!-- Customer Information -->
            @if($shipment->customer)
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Customer</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-3 text-sm">
                        <div>
                            <div class="text-muted-foreground">Name</div>
                            <div class="font-semibold">{{ $shipment->customer->first_name }} {{ $shipment->customer->last_name }}</div>
                        </div>
                        <div>
                            <div class="text-muted-foreground">Email</div>
                            <div class="font-medium">{{ $shipment->customer->email }}</div>
                        </div>
                        @if($shipment->customer->phone)
                        <div>
                            <div class="text-muted-foreground">Phone</div>
                            <div class="font-medium">{{ $shipment->customer->phone }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Cost Breakdown -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Cost Breakdown</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Base Price:</span>
                            <span class="font-medium">${{ number_format($shipment->base_price ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Weight Charge:</span>
                            <span class="font-medium">${{ number_format($shipment->weight_charge ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Distance Charge:</span>
                            <span class="font-medium">${{ number_format($shipment->distance_charge ?? 0, 2) }}</span>
                        </div>
                        @if($shipment->insurance_fee > 0)
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Insurance:</span>
                            <span class="font-medium">${{ number_format($shipment->insurance_fee, 2) }}</span>
                        </div>
                        @endif
                        @if($shipment->additional_services_fee > 0)
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Additional Services:</span>
                            <span class="font-medium">${{ number_format($shipment->additional_services_fee, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Tax:</span>
                            <span class="font-medium">${{ number_format($shipment->tax_amount ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t font-semibold text-base">
                            <span>Total:</span>
                            <span class="text-primary">${{ number_format($shipment->total_amount ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Services -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Special Services</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-2">
                        @if($shipment->insurance_required)
                        <div class="flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                            <span>Insurance Coverage ({{ $shipment->insurance_amount ? '$'.number_format($shipment->insurance_amount, 2) : 'N/A' }})</span>
                        </div>
                        @endif
                        @if($shipment->signature_required)
                        <div class="flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                            <span>Signature Required</span>
                        </div>
                        @endif
                        @if($shipment->temperature_controlled)
                        <div class="flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                            <span>Temperature Controlled</span>
                        </div>
                        @endif
                        @if($shipment->fragile_handling)
                        <div class="flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                            <span>Fragile Handling</span>
                        </div>
                        @endif
                        @if(!$shipment->insurance_required && !$shipment->signature_required && !$shipment->temperature_controlled && !$shipment->fragile_handling)
                        <p class="text-sm text-muted-foreground">No special services selected</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline & Dates -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Timeline</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-3 text-sm">
                        <div>
                            <div class="text-muted-foreground">Created</div>
                            <div class="font-medium">{{ $shipment->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        @if($shipment->pickup_date)
                        <div>
                            <div class="text-muted-foreground">Pickup Date</div>
                            <div class="font-medium">{{ $shipment->pickup_date->format('M d, Y h:i A') }}</div>
                        </div>
                        @endif
                        @if($shipment->expected_delivery_date)
                        <div>
                            <div class="text-muted-foreground">Expected Delivery</div>
                            <div class="font-medium">{{ $shipment->expected_delivery_date->format('M d, Y') }}</div>
                        </div>
                        @endif
                        @if($shipment->actual_delivery_date)
                        <div>
                            <div class="text-muted-foreground">Actual Delivery</div>
                            <div class="font-medium text-green-600">{{ $shipment->actual_delivery_date->format('M d, Y h:i A') }}</div>
                        </div>
                        @endif
                        <div>
                            <div class="text-muted-foreground">Last Updated</div>
                            <div class="font-medium">{{ $shipment->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Delivery Proof (Signature & Photo) -->
@if($shipment->status === 'delivered' && ($shipment->delivery_signature || $shipment->delivery_photo))
<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-xl font-semibold leading-none tracking-tight">Delivery Proof</h3>
    </div>
    <div class="p-6 pt-0 space-y-4">
        @if($shipment->delivery_signature)
        <div>
            <div class="text-sm font-medium text-muted-foreground mb-2">Customer Signature</div>
            <div class="border rounded-lg p-4 bg-white">
                <img src="{{ Storage::url($shipment->delivery_signature) }}" alt="Customer Signature" class="max-w-full h-auto mx-auto" style="max-height: 200px;">
            </div>
        </div>
        @endif
        
        @if($shipment->delivery_photo)
        <div>
            <div class="text-sm font-medium text-muted-foreground mb-2">Delivery Photo</div>
            <div class="border rounded-lg p-4 bg-white">
                <img src="{{ Storage::url($shipment->delivery_photo) }}" alt="Delivery Photo" class="max-w-full h-auto mx-auto rounded" style="max-height: 300px;">
            </div>
            <button onclick="viewFullImage('{{ Storage::url($shipment->delivery_photo) }}')" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                View Full Size
            </button>
        </div>
        @endif
        
        @if($shipment->delivery_notes)
        <div>
            <div class="text-sm font-medium text-muted-foreground mb-2">Delivery Notes</div>
            <div class="p-3 bg-muted rounded-lg text-sm">
                {{ $shipment->delivery_notes }}
            </div>
        </div>
        @endif
    </div>
</div>
@endif



<!-- COD Collection Confirmation -->
@if($shipment->payment_mode === 'cod' && $shipment->status === 'delivered')
<div class="rounded-lg border bg-green-50 border-green-200 shadow-sm">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-xl font-semibold leading-none tracking-tight flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2 text-green-600">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m9 12 2 2 4-4"></path>
            </svg>
            COD Payment Collected
        </h3>
    </div>
    <div class="p-6 pt-0">
        <div class="bg-white rounded-lg p-4 border border-green-300">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="text-sm text-muted-foreground">Amount Collected</div>
                    <div class="text-2xl font-bold text-green-700">${{ number_format($shipment->cod_amount, 2) }}</div>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600">
                        <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                        <path d="M2 10h20"></path>
                    </svg>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-muted-foreground">Payment Method:</span>
                    <span class="font-medium">Cash on Delivery</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted-foreground">Collected By:</span>
                    <span class="font-medium">{{ $shipment->assignedDriver ? $shipment->assignedDriver->first_name . ' ' . $shipment->assignedDriver->last_name : 'Driver' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted-foreground">Collection Date:</span>
                    <span class="font-medium">{{ $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('M d, Y h:i A') : 'N/A' }}</span>
                </div>
            </div>
            <div class="mt-3 p-3 bg-green-50 rounded border border-green-200 flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 mt-0.5 flex-shrink-0">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
                <p class="text-xs text-green-800">Driver confirmed collection of full COD amount upon delivery completion.</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- COD Pending Alert -->
@if($shipment->payment_mode === 'cod' && $shipment->status !== 'delivered')
<div class="rounded-lg border bg-amber-50 border-amber-200 shadow-sm">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-xl font-semibold leading-none tracking-tight flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2 text-amber-600">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 16v-4"></path>
                <path d="M12 8h.01"></path>
            </svg>
            COD Payment Pending
        </h3>
    </div>
    <div class="p-6 pt-0">
        <div class="bg-white rounded-lg p-4 border border-amber-300">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="text-sm text-muted-foreground">Amount to Collect</div>
                    <div class="text-2xl font-bold text-amber-700">${{ number_format($shipment->cod_amount, 2) }}</div>
                </div>
                <div class="p-3 bg-amber-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-600">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                </div>
            </div>
            <div class="p-3 bg-amber-50 rounded border border-amber-200">
                <p class="text-xs text-amber-800">Driver must collect this amount from the customer upon delivery.</p>
            </div>
        </div>
    </div>
</div>
@endif

            <!-- Current Location -->
            @if($shipment->currentWarehouse)
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Current Location</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-3 text-sm">
                        @if($shipment->currentWarehouse)
                        <div>
                            <div class="text-muted-foreground">Warehouse</div>
                            <div class="font-medium">{{ $shipment->currentWarehouse->name }}</div>
                            @if($shipment->currentWarehouse->address)
                            <div class="text-xs text-muted-foreground mt-1">{{ $shipment->currentWarehouse->address }}</div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Assigned Vehicle -->
            @if($shipment->assignedVehicle)
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Assigned Vehicle</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-3 text-sm">
                        <div>
                            <div class="text-muted-foreground">Vehicle Number</div>
                            <div class="font-medium">{{ $shipment->assignedVehicle->vehicle_number }}</div>
                        </div>
                        @if($shipment->assignedVehicle->make || $shipment->assignedVehicle->model)
                        <div>
                            <div class="text-muted-foreground">Make & Model</div>
                            <div class="font-medium">{{ $shipment->assignedVehicle->make }} {{ $shipment->assignedVehicle->model }}</div>
                        </div>
                        @endif
                        @if($shipment->assignedVehicle->license_plate)
                        <div>
                            <div class="text-muted-foreground">License Plate</div>
                            <div class="font-medium">{{ $shipment->assignedVehicle->license_plate }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Actions</h3>
                </div>
                <div class="p-6 pt-0 space-y-2">
                    <a href="{{ route('admin.shipments.edit', $shipment->id) }}" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit Shipment
                    </a>
                    <button onclick="window.print()" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Print Details
                    </button>
                    <button onclick="copyTrackingNumber()" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        Copy Tracking #
                    </button>
                    <button onclick="duplicateShipment({{ $shipment->id }})" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect>
                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                        </svg>
                        Duplicate Shipment
                    </button>
                    @if(in_array($shipment->status, ['draft', 'cancelled']))
                    <button onclick="deleteShipment({{ $shipment->id }})" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-white hover:bg-red-700 h-10 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                        </svg>
                        Delete Shipment
                    </button>
                    @endif
                </div>
            </div>

            <!-- Notifications -->
            @if($shipment->notifications->count() > 0)
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Recent Notifications</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-3">
                        @foreach($shipment->notifications->take(5) as $notification)
                        <div class="p-3 bg-muted rounded-lg text-sm">
                            <div class="font-medium">{{ $notification->title ?? 'Notification' }}</div>
                            <div class="text-muted-foreground text-xs mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Copy tracking number to clipboard
function copyTrackingNumber() {
    const trackingNumber = '{{ $shipment->tracking_number }}';
    navigator.clipboard.writeText(trackingNumber).then(() => {
        alert('Tracking number copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// Duplicate Shipment
async function duplicateShipment(shipmentId) {
    if (!confirm('Are you sure you want to duplicate this shipment?')) return;
    
    try {
        const response = await fetch(`/admin/shipments/${shipmentId}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(`Shipment duplicated successfully! New tracking number: ${data.tracking_number}`);
            if (confirm('Would you like to edit the duplicated shipment now?')) {
                window.location.href = data.redirect_url;
            } else {
                window.location.href = '{{ route("admin.shipments.index") }}';
            }
        } else {
            alert('Failed to duplicate shipment: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to duplicate shipment. Please try again.');
    }
}

// Delete Shipment
async function deleteShipment(shipmentId) {
    if (!confirm('Are you sure you want to delete this shipment? This action cannot be undone.')) return;
    
    try {
        const response = await fetch(`/admin/shipments/${shipmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        if (response.ok) {
            alert('Shipment deleted successfully!');
            window.location.href = '{{ route("admin.shipments.index") }}';
        } else {
            const data = await response.json();
            alert('Failed to delete shipment: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete shipment. Please try again.');
    }
}



function viewFullImage(imageUrl) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
    modal.onclick = function() { modal.remove(); };
    
    modal.innerHTML = `
        <div class="relative max-w-7xl max-h-full">
            <button onclick="this.parentElement.parentElement.remove()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
            <img src="${imageUrl}" alt="Full Size" class="max-w-full max-h-[90vh] mx-auto rounded-lg">
        </div>
    `;
    
    document.body.appendChild(modal);
}
</script>

@endsection