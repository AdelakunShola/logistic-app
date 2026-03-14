@extends('admin.admin_dashboard')
@section('admin')

@php
    $statusColors = [
        'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '#6b7280', 'dot' => 'bg-gray-500'],
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => '#f59e0b', 'dot' => 'bg-yellow-500'],
        'picked_up' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '#3b82f6', 'dot' => 'bg-blue-500'],
        'in_transit' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => '#8b5cf6', 'dot' => 'bg-purple-500'],
        'out_for_delivery' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-800', 'icon' => '#ec4899', 'dot' => 'bg-pink-500'],
        'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '#10b981', 'dot' => 'bg-green-500'],
        'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => '#ef4444', 'dot' => 'bg-red-500'],
        'returned' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'icon' => '#f97316', 'dot' => 'bg-orange-500'],
        'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '#64748b', 'dot' => 'bg-gray-500'],
    ];
    $currentStatus = $statusColors[$shipment->status] ?? $statusColors['draft'];
@endphp

<div class="space-y-6 p-4 md:p-8">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.shipments.index') }}" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <h1 class="text-2xl font-bold tracking-tight">Shipment #{{ $shipment->tracking_number }}</h1>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $currentStatus['bg'] }} {{ $currentStatus['text'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $currentStatus['dot'] }} mr-1.5"></span>
                    {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                </span>
            </div>
            <p class="text-sm text-muted-foreground">
                Created {{ $shipment->created_at->format('M d, Y h:i A') }}
                @if($shipment->updatedBy)
                    &middot; Last updated by {{ $shipment->updatedBy->first_name }} {{ $shipment->updatedBy->last_name }}
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="copyTrackingNumber()" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                Copy Tracking #
            </button>
            <button onclick="window.print()" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print
            </button>
            <a href="{{ route('admin.shipments.edit', $shipment->id) }}" class="inline-flex items-center justify-center text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </a>
        </div>
    </div>

    {{-- ===== QUICK STATS CARDS ===== --}}
    <div class="grid gap-3 grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
        <div class="rounded-lg border bg-card shadow-sm p-4">
            <div class="text-xs font-medium text-muted-foreground mb-1">Shipment Type</div>
            <div class="text-sm font-bold capitalize">{{ str_replace('_', ' ', $shipment->shipment_type) }}</div>
        </div>
        <div class="rounded-lg border bg-card shadow-sm p-4">
            <div class="text-xs font-medium text-muted-foreground mb-1">Priority</div>
            <div class="text-sm font-bold capitalize">{{ $shipment->delivery_priority ?? 'Standard' }}</div>
        </div>
        <div class="rounded-lg border bg-card shadow-sm p-4">
            <div class="text-xs font-medium text-muted-foreground mb-1">Shipping Zone</div>
            <div class="text-sm font-bold capitalize">{{ $shipment->shipping_zone ?? 'N/A' }}</div>
        </div>
        <div class="rounded-lg border bg-card shadow-sm p-4">
            <div class="text-xs font-medium text-muted-foreground mb-1">Items</div>
            <div class="text-sm font-bold">{{ $shipment->number_of_items ?? 0 }}</div>
        </div>
        <div class="rounded-lg border bg-card shadow-sm p-4">
            <div class="text-xs font-medium text-muted-foreground mb-1">Total Weight</div>
            <div class="text-sm font-bold">{{ number_format($shipment->total_weight ?? 0, 2) }} lbs</div>
        </div>
        <div class="rounded-lg border bg-card shadow-sm p-4">
            <div class="text-xs font-medium text-muted-foreground mb-1">Total Cost</div>
            <div class="text-sm font-bold text-primary">${{ number_format($shipment->total_amount ?? 0, 2) }}</div>
        </div>
    </div>

    {{-- ===== PROGRESS BAR ===== --}}
    <div class="rounded-lg border bg-card shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold">Shipment Progress</h3>
            <span class="text-xs font-medium text-muted-foreground">{{ $shipment->progress }}% Complete</span>
        </div>
        @php
            $progressSteps = ['draft' => 0, 'pending' => 1, 'picked_up' => 2, 'in_transit' => 3, 'out_for_delivery' => 4, 'delivered' => 5];
            $currentStep = $progressSteps[$shipment->status] ?? 0;
            $isFailed = in_array($shipment->status, ['failed', 'returned', 'cancelled']);
            $stepLabels = [
                ['key' => 'pending', 'label' => 'Pending'],
                ['key' => 'picked_up', 'label' => 'Picked Up'],
                ['key' => 'in_transit', 'label' => 'In Transit'],
                ['key' => 'out_for_delivery', 'label' => 'Out for Delivery'],
                ['key' => 'delivered', 'label' => 'Delivered'],
            ];
        @endphp

        @if($isFailed)
            <div class="flex items-center gap-3 p-3 rounded-lg {{ $shipment->status === 'failed' ? 'bg-red-50 border border-red-200' : ($shipment->status === 'returned' ? 'bg-orange-50 border border-orange-200' : 'bg-gray-50 border border-gray-200') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $shipment->status === 'failed' ? 'text-red-600' : ($shipment->status === 'returned' ? 'text-orange-600' : 'text-gray-600') }}"><circle cx="12" cy="12" r="10"></circle><path d="m15 9-6 6"></path><path d="m9 9 6 6"></path></svg>
                <div>
                    <p class="font-semibold text-sm {{ $shipment->status === 'failed' ? 'text-red-800' : ($shipment->status === 'returned' ? 'text-orange-800' : 'text-gray-800') }}">Shipment {{ ucfirst($shipment->status) }}</p>
                    @if($shipment->cancellation_reason)
                        <p class="text-xs text-muted-foreground mt-0.5">Reason: {{ $shipment->cancellation_reason }}</p>
                    @endif
                </div>
            </div>
        @else
            <div class="relative">
                <div class="flex items-center justify-between">
                    @foreach($stepLabels as $index => $step)
                        @php $stepNum = $index + 1; $isCompleted = $currentStep >= $stepNum; $isActive = $currentStep == $stepNum; @endphp
                        <div class="flex flex-col items-center flex-1 relative">
                            @if($index > 0)
                                <div class="absolute top-4 right-1/2 w-full h-0.5 {{ $currentStep >= $stepNum ? 'bg-primary' : 'bg-gray-200' }}"></div>
                            @endif
                            <div class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full border-2 {{ $isCompleted ? 'bg-primary border-primary text-primary-foreground' : ($isActive ? 'border-primary text-primary bg-background' : 'border-gray-300 text-gray-400 bg-background') }}">
                                @if($isCompleted && !$isActive)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"></path></svg>
                                @else
                                    <span class="text-xs font-bold">{{ $stepNum }}</span>
                                @endif
                            </div>
                            <span class="mt-1.5 text-xs font-medium text-center {{ $isCompleted ? 'text-primary' : 'text-muted-foreground' }}">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-primary h-1.5 rounded-full transition-all duration-500" style="width: {{ $shipment->progress }}%"></div>
                </div>
            </div>
        @endif
    </div>

    {{-- ===== MAIN CONTENT: 3-COLUMN GRID ===== --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- == COLUMN 1: Sender, Customer, Assignment == --}}
        <div class="space-y-6">

            {{-- Sender / Pickup Information --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        Sender / Pickup
                    </h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    @if($shipment->pickup_company_name)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Company</span>
                        <span class="font-medium text-right">{{ $shipment->pickup_company_name }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Contact</span>
                        <span class="font-medium text-right">{{ $shipment->pickup_contact_name ?? 'N/A' }}</span>
                    </div>
                    @if($shipment->pickup_contact_phone)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Phone</span>
                        <span class="font-medium">{{ $shipment->pickup_contact_phone }}</span>
                    </div>
                    @endif
                    @if($shipment->pickup_contact_email)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Email</span>
                        <span class="font-medium text-right truncate ml-2">{{ $shipment->pickup_contact_email }}</span>
                    </div>
                    @endif
                    <div class="pt-2 border-t">
                        <div class="text-muted-foreground mb-1">Address</div>
                        <div class="font-medium">
                            {{ $shipment->pickup_address }}
                            @if($shipment->pickup_address_line2)<br>{{ $shipment->pickup_address_line2 }}@endif
                            <br>{{ $shipment->pickup_city }}, {{ $shipment->pickup_state }} {{ $shipment->pickup_postal_code }}
                            @if($shipment->pickup_country)<br>{{ $shipment->pickup_country }}@endif
                        </div>
                    </div>
                    @if($shipment->pickup_scheduled_date)
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-muted-foreground">Scheduled Pickup</span>
                        <span class="font-medium">{{ $shipment->pickup_scheduled_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($shipment->pickup_date)
                    <div class="flex justify-between {{ !$shipment->pickup_scheduled_date ? 'pt-2 border-t' : '' }}">
                        <span class="text-muted-foreground">Actual Pickup</span>
                        <span class="font-medium">{{ $shipment->pickup_date->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Customer Information --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Customer (Account)
                    </h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    @if($shipment->customer)
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Name</span>
                            <span class="font-medium">{{ $shipment->customer->first_name }} {{ $shipment->customer->last_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Email</span>
                            <span class="font-medium text-right truncate ml-2">{{ $shipment->customer->email }}</span>
                        </div>
                        @if($shipment->customer->phone)
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Phone</span>
                            <span class="font-medium">{{ $shipment->customer->phone }}</span>
                        </div>
                        @endif
                    @else
                        <p class="text-muted-foreground italic">No registered customer linked</p>
                    @endif
                </div>
            </div>

            {{-- Assignment & Operations --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Assignment & Operations
                    </h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Carrier</span>
                        <span class="font-medium">{{ $shipment->carrier->name ?? 'Not Assigned' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Driver</span>
                        <span class="font-medium">{{ $shipment->assignedDriver ? $shipment->assignedDriver->first_name . ' ' . $shipment->assignedDriver->last_name : 'Not Assigned' }}</span>
                    </div>
                    @if($shipment->assignedVehicle)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Vehicle</span>
                        <span class="font-medium">{{ $shipment->assignedVehicle->vehicle_number }}</span>
                    </div>
                    @if($shipment->assignedVehicle->make || $shipment->assignedVehicle->model)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Make / Model</span>
                        <span class="font-medium">{{ $shipment->assignedVehicle->make }} {{ $shipment->assignedVehicle->model }}</span>
                    </div>
                    @endif
                    @if($shipment->assignedVehicle->license_plate)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">License Plate</span>
                        <span class="font-medium">{{ $shipment->assignedVehicle->license_plate }}</span>
                    </div>
                    @endif
                    @endif
                    @if($shipment->currentBranch)
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-muted-foreground">Current Branch</span>
                        <span class="font-medium">{{ $shipment->currentBranch->name }}</span>
                    </div>
                    @endif
                    @if($shipment->currentHub)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Current Hub</span>
                        <span class="font-medium">{{ $shipment->currentHub->name }}</span>
                    </div>
                    @endif
                    @if($shipment->currentWarehouse)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Warehouse</span>
                        <span class="font-medium">{{ $shipment->currentWarehouse->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Special Services --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path></svg>
                        Special Services
                    </h3>
                </div>
                <div class="p-4 space-y-2 text-sm">
                    @php $hasServices = $shipment->insurance_required || $shipment->signature_required || $shipment->temperature_controlled || $shipment->fragile_handling; @endphp
                    @if($hasServices)
                        @if($shipment->insurance_required)
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                            <span>Insurance — {{ $shipment->insurance_amount ? '$'.number_format($shipment->insurance_amount, 2) : 'Included' }}</span>
                        </div>
                        @endif
                        @if($shipment->signature_required)
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                            <span>Signature Required</span>
                        </div>
                        @endif
                        @if($shipment->temperature_controlled)
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                            <span>Temperature Controlled</span>
                        </div>
                        @endif
                        @if($shipment->fragile_handling)
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                            <span>Fragile Handling</span>
                        </div>
                        @endif
                    @else
                        <p class="text-muted-foreground italic">No special services</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- == COLUMN 2: Recipient, Shipment Details, Dates, Cost == --}}
        <div class="space-y-6">

            {{-- Recipient / Delivery Information --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        Recipient / Delivery
                    </h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    @if($shipment->delivery_company_name)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Company</span>
                        <span class="font-medium text-right">{{ $shipment->delivery_company_name }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Contact</span>
                        <span class="font-medium text-right">{{ $shipment->delivery_contact_name ?? 'N/A' }}</span>
                    </div>
                    @if($shipment->delivery_contact_phone)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Phone</span>
                        <span class="font-medium">{{ $shipment->delivery_contact_phone }}</span>
                    </div>
                    @endif
                    @if($shipment->delivery_contact_email)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Email</span>
                        <span class="font-medium text-right truncate ml-2">{{ $shipment->delivery_contact_email }}</span>
                    </div>
                    @endif
                    <div class="pt-2 border-t">
                        <div class="text-muted-foreground mb-1">Address</div>
                        <div class="font-medium">
                            {{ $shipment->delivery_address }}
                            @if($shipment->delivery_address_line2)<br>{{ $shipment->delivery_address_line2 }}@endif
                            <br>{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }} {{ $shipment->delivery_postal_code }}
                            @if($shipment->delivery_country)<br>{{ $shipment->delivery_country }}@endif
                        </div>
                    </div>
                    @if($shipment->delivery_attempts)
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-muted-foreground">Delivery Attempts</span>
                        <span class="font-medium">{{ $shipment->delivery_attempts }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Shipment Details --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path><path d="M12 22V12"></path><polyline points="3.29 7 12 12 20.71 7"></polyline></svg>
                        Shipment Details
                    </h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    @if($shipment->reference_number)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Reference #</span>
                        <span class="font-medium">{{ $shipment->reference_number }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Type</span>
                        <span class="font-medium capitalize">{{ str_replace('_', ' ', $shipment->shipment_type) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Priority</span>
                        <span class="font-medium capitalize">{{ $shipment->delivery_priority ?? 'Standard' }}</span>
                    </div>
                    @if($shipment->service_level)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Service Level</span>
                        <span class="font-medium capitalize">{{ $shipment->service_level }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Shipping Zone</span>
                        <span class="font-medium capitalize">{{ $shipment->shipping_zone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Payment Mode</span>
                        <span class="font-medium uppercase">{{ $shipment->payment_mode ?? 'Prepaid' }}
                            @if($shipment->payment_mode === 'cod')
                                <span class="text-green-600 ml-1">${{ number_format($shipment->cod_amount, 2) }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-muted-foreground">Items</span>
                        <span class="font-medium">{{ $shipment->number_of_items ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Total Weight</span>
                        <span class="font-medium">{{ number_format($shipment->total_weight ?? 0, 2) }} lbs</span>
                    </div>
                    @if($shipment->total_value)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Declared Value</span>
                        <span class="font-medium">${{ number_format($shipment->total_value, 2) }}</span>
                    </div>
                    @endif
                    @if($shipment->route_distance)
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-muted-foreground">Route Distance</span>
                        <span class="font-medium">{{ number_format($shipment->route_distance, 1) }} km</span>
                    </div>
                    @endif
                    @if($shipment->estimated_duration)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Est. Duration</span>
                        <span class="font-medium">{{ floor($shipment->estimated_duration / 60) }}h {{ $shipment->estimated_duration % 60 }}m</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Key Dates & Timeline --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                        Key Dates
                    </h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Created</span>
                        <span class="font-medium">{{ $shipment->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($shipment->pickup_scheduled_date)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Scheduled Pickup</span>
                        <span class="font-medium">{{ $shipment->pickup_scheduled_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($shipment->pickup_date)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Picked Up</span>
                        <span class="font-medium">{{ $shipment->pickup_date->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                    @if($shipment->preferred_delivery_date)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Preferred Delivery</span>
                        <span class="font-medium">{{ $shipment->preferred_delivery_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($shipment->expected_delivery_date)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Expected Delivery</span>
                        <span class="font-medium">{{ $shipment->expected_delivery_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($shipment->actual_delivery_date)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Delivered</span>
                        <span class="font-medium text-green-600">{{ $shipment->actual_delivery_date->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                    @if($shipment->driver_started_at)
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-muted-foreground">Driver Started</span>
                        <span class="font-medium">{{ $shipment->driver_started_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                    @if($shipment->driver_arrived_at)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Driver Arrived</span>
                        <span class="font-medium">{{ $shipment->driver_arrived_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-muted-foreground">Last Updated</span>
                        <span class="font-medium">{{ $shipment->updated_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            {{-- Cost Breakdown --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><line x1="12" x2="12" y1="2" y2="22"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        Cost Breakdown
                    </h3>
                </div>
                <div class="p-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Base Price</span>
                        <span class="font-medium">${{ number_format($shipment->base_price ?? 0, 2) }}</span>
                    </div>
                    @if(($shipment->weight_charge ?? 0) > 0)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Weight Charge</span>
                        <span class="font-medium">${{ number_format($shipment->weight_charge, 2) }}</span>
                    </div>
                    @endif
                    @if(($shipment->distance_charge ?? 0) > 0)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Distance Charge</span>
                        <span class="font-medium">${{ number_format($shipment->distance_charge, 2) }}</span>
                    </div>
                    @endif
                    @if(($shipment->priority_charge ?? 0) > 0)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Priority Charge</span>
                        <span class="font-medium">${{ number_format($shipment->priority_charge, 2) }}</span>
                    </div>
                    @endif
                    @if(($shipment->insurance_fee ?? 0) > 0)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Insurance Fee</span>
                        <span class="font-medium">${{ number_format($shipment->insurance_fee, 2) }}</span>
                    </div>
                    @endif
                    @if(($shipment->additional_services_fee ?? 0) > 0)
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Additional Services</span>
                        <span class="font-medium">${{ number_format($shipment->additional_services_fee, 2) }}</span>
                    </div>
                    @endif
                    @if(($shipment->discount_amount ?? 0) > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Discount</span>
                        <span class="font-medium">-${{ number_format($shipment->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Tax</span>
                        <span class="font-medium">${{ number_format($shipment->tax_amount ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t font-semibold text-base">
                        <span>Total</span>
                        <span class="text-primary">${{ number_format($shipment->total_amount ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- == COLUMN 3: Items, Tracking History, Delivery Proof, COD, Actions == --}}
        <div class="space-y-6">

            {{-- Package Items --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path><path d="M12 22V12"></path><polyline points="3.29 7 12 12 20.71 7"></polyline></svg>
                        Package Items
                    </h3>
                </div>
                <div class="p-4">
                    @forelse($shipment->shipmentItems as $item)
                    <div class="p-3 border rounded-lg mb-3 last:mb-0">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="font-semibold text-sm">{{ $item->description }}</span>
                                <span class="text-xs text-muted-foreground ml-1 capitalize">{{ $item->category ?? '' }}</span>
                            </div>
                            @if($item->is_hazardous)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-800">Hazardous</span>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div><span class="text-muted-foreground">Qty:</span> <span class="font-medium">{{ $item->quantity }}</span></div>
                            <div><span class="text-muted-foreground">Weight:</span> <span class="font-medium">{{ number_format($item->weight, 2) }} lbs</span></div>
                            <div><span class="text-muted-foreground">Value:</span> <span class="font-medium">${{ number_format($item->value, 2) }}</span></div>
                            @if($item->length && $item->width && $item->height)
                            <div><span class="text-muted-foreground">Dims:</span> <span class="font-medium">{{ $item->length }}" x {{ $item->width }}" x {{ $item->height }}"</span></div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-muted-foreground text-center py-4 italic">No items recorded</p>
                    @endforelse
                </div>
            </div>

            {{-- Tracking History --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        Tracking History
                    </h3>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        @forelse($shipment->trackingHistory as $index => $tracking)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                @if($index === 0)
                                <div class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                </div>
                                @else
                                <div class="flex items-center justify-center w-6 h-6 rounded-full bg-muted text-muted-foreground text-xs font-medium">
                                    {{ $index + 1 }}
                                </div>
                                @endif
                                @if($index < count($shipment->trackingHistory) - 1)
                                <div class="w-0.5 h-full bg-border mt-1"></div>
                                @endif
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="font-medium text-sm">{{ $tracking->description ?? ucfirst(str_replace('_', ' ', $tracking->status)) }}</div>
                                @if($tracking->location_name)<div class="text-xs text-muted-foreground">{{ $tracking->location_name }}</div>@endif
                                <div class="text-xs text-muted-foreground mt-0.5">{{ $tracking->created_at->format('M d, Y h:i A') }}</div>
                                @if($tracking->updatedBy)
                                <div class="text-xs text-muted-foreground">By: {{ $tracking->updatedBy->first_name }} {{ $tracking->updatedBy->last_name }}</div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-muted-foreground text-center py-4 italic">No tracking history</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Delivery Proof --}}
            @if($shipment->status === 'delivered' && ($shipment->delivery_signature || $shipment->delivery_photo || $shipment->delivery_notes))
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        Delivery Proof
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    @if($shipment->delivery_signature)
                    <div>
                        <div class="text-xs font-medium text-muted-foreground mb-1">Signature</div>
                        <div class="border rounded-lg p-3 bg-white">
                            <img src="{{ Storage::url($shipment->delivery_signature) }}" alt="Signature" class="max-w-full h-auto mx-auto" style="max-height: 150px;">
                        </div>
                    </div>
                    @endif
                    @if($shipment->delivery_photo)
                    <div>
                        <div class="text-xs font-medium text-muted-foreground mb-1">Photo</div>
                        <div class="border rounded-lg p-3 bg-white">
                            <img src="{{ Storage::url($shipment->delivery_photo) }}" alt="Delivery Photo" class="max-w-full h-auto mx-auto rounded cursor-pointer" style="max-height: 200px;" onclick="viewFullImage('{{ Storage::url($shipment->delivery_photo) }}')">
                        </div>
                    </div>
                    @endif
                    @if($shipment->delivery_notes)
                    <div>
                        <div class="text-xs font-medium text-muted-foreground mb-1">Notes</div>
                        <div class="p-3 bg-muted rounded-lg text-sm">{{ $shipment->delivery_notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- COD Section --}}
            @if($shipment->payment_mode === 'cod')
            <div class="rounded-lg border shadow-sm {{ $shipment->status === 'delivered' ? 'bg-green-50 border-green-200' : 'bg-amber-50 border-amber-200' }}">
                <div class="p-4 border-b {{ $shipment->status === 'delivered' ? 'border-green-200' : 'border-amber-200' }}">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        @if($shipment->status === 'delivered')
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                            COD Payment Collected
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                            COD Payment Pending
                        @endif
                    </h3>
                </div>
                <div class="p-4">
                    <div class="text-2xl font-bold {{ $shipment->status === 'delivered' ? 'text-green-700' : 'text-amber-700' }} mb-2">
                        ${{ number_format($shipment->cod_amount, 2) }}
                    </div>
                    @if($shipment->status === 'delivered')
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Collected By</span>
                            <span class="font-medium">{{ $shipment->assignedDriver ? $shipment->assignedDriver->first_name . ' ' . $shipment->assignedDriver->last_name : 'Driver' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Date</span>
                            <span class="font-medium">{{ $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                    @else
                    <p class="text-xs {{ $shipment->status === 'delivered' ? 'text-green-700' : 'text-amber-700' }}">Driver must collect this amount upon delivery.</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Customer Feedback --}}
            @if($shipment->customer_rating || $shipment->customer_feedback)
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-500"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        Customer Feedback
                    </h3>
                </div>
                <div class="p-4 text-sm">
                    @if($shipment->customer_rating)
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-muted-foreground">Rating:</span>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="{{ $i <= $shipment->customer_rating ? '#f59e0b' : 'none' }}" stroke="{{ $i <= $shipment->customer_rating ? '#f59e0b' : '#d1d5db' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            @endfor
                            <span class="ml-1 font-medium">{{ $shipment->customer_rating }}/5</span>
                        </div>
                    </div>
                    @endif
                    @if($shipment->customer_feedback)
                    <div class="p-3 bg-muted rounded-lg text-sm">{{ $shipment->customer_feedback }}</div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Delays --}}
            @if($shipment->delays && $shipment->delays->count() > 0)
            <div class="rounded-lg border border-red-200 bg-red-50 shadow-sm">
                <div class="p-4 border-b border-red-200">
                    <h3 class="text-sm font-semibold flex items-center gap-2 text-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                        Delays ({{ $shipment->delays->count() }})
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($shipment->delays as $delay)
                    <div class="p-3 bg-white rounded-lg border border-red-100 text-sm">
                        <div class="font-medium text-red-800">{{ $delay->delay_reason }}</div>
                        @if($delay->delay_description)
                        <p class="text-xs text-muted-foreground mt-1">{{ $delay->delay_description }}</p>
                        @endif
                        <div class="flex gap-4 mt-2 text-xs text-muted-foreground">
                            @if($delay->delay_duration_minutes)
                            <span>Duration: {{ $delay->delay_duration_minutes }}min</span>
                            @endif
                            @if($delay->delayed_at)
                            <span>{{ $delay->delayed_at->format('M d, Y') }}</span>
                            @endif
                            @if($delay->resolved_at)
                            <span class="text-green-600">Resolved</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Notifications --}}
            @if($shipment->notifications && $shipment->notifications->count() > 0)
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                        Recent Notifications
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    @foreach($shipment->notifications->take(5) as $notification)
                    <div class="p-2.5 bg-muted rounded-lg text-sm">
                        <div class="font-medium text-xs">{{ $notification->title ?? 'Notification' }}</div>
                        <div class="text-muted-foreground text-xs mt-0.5">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="rounded-lg border bg-card shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-semibold">Actions</h3>
                </div>
                <div class="p-4 grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.shipments.edit', $shipment->id) }}" class="inline-flex items-center justify-center text-xs font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit
                    </a>
                    <button onclick="window.print()" class="inline-flex items-center justify-center text-xs font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        Print
                    </button>
                    <button onclick="duplicateShipment({{ $shipment->id }})" class="inline-flex items-center justify-center text-xs font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path></svg>
                        Duplicate
                    </button>
                    @if(in_array($shipment->status, ['draft', 'cancelled']))
                    <button onclick="deleteShipment({{ $shipment->id }})" class="inline-flex items-center justify-center text-xs font-medium bg-red-600 text-white hover:bg-red-700 h-9 rounded-md px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                        Delete
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===== SPECIAL INSTRUCTIONS (Full Width) ===== --}}
    @if($shipment->special_instructions)
    <div class="rounded-lg border bg-amber-50 border-amber-200 shadow-sm p-4">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600 mt-0.5 flex-shrink-0"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
            <div>
                <div class="text-sm font-semibold text-amber-800 mb-1">Special Instructions</div>
                <div class="text-sm text-amber-900">{{ $shipment->special_instructions }}</div>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== WAREHOUSE JOURNEY (Full Width) ===== --}}
    @if($shipment->arrived_at_origin_warehouse || $shipment->departed_origin_warehouse || $shipment->arrived_at_destination_warehouse || $shipment->departed_for_delivery)
    <div class="rounded-lg border bg-card shadow-sm">
        <div class="p-4 border-b">
            <h3 class="text-sm font-semibold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600"><rect width="20" height="8" x="2" y="14" rx="2"></rect><rect width="20" height="8" x="2" y="2" rx="2"></rect></svg>
                Warehouse Journey
            </h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div class="text-center p-3 rounded-lg {{ $shipment->arrived_at_origin_warehouse ? 'bg-blue-50' : 'bg-gray-50' }}">
                    <div class="text-xs text-muted-foreground mb-1">Arrived at Origin WH</div>
                    <div class="font-medium text-xs">{{ $shipment->arrived_at_origin_warehouse ? $shipment->arrived_at_origin_warehouse->format('M d, h:i A') : '---' }}</div>
                </div>
                <div class="text-center p-3 rounded-lg {{ $shipment->departed_origin_warehouse ? 'bg-blue-50' : 'bg-gray-50' }}">
                    <div class="text-xs text-muted-foreground mb-1">Departed Origin WH</div>
                    <div class="font-medium text-xs">{{ $shipment->departed_origin_warehouse ? $shipment->departed_origin_warehouse->format('M d, h:i A') : '---' }}</div>
                </div>
                <div class="text-center p-3 rounded-lg {{ $shipment->arrived_at_destination_warehouse ? 'bg-green-50' : 'bg-gray-50' }}">
                    <div class="text-xs text-muted-foreground mb-1">Arrived at Dest. WH</div>
                    <div class="font-medium text-xs">{{ $shipment->arrived_at_destination_warehouse ? $shipment->arrived_at_destination_warehouse->format('M d, h:i A') : '---' }}</div>
                </div>
                <div class="text-center p-3 rounded-lg {{ $shipment->departed_for_delivery ? 'bg-green-50' : 'bg-gray-50' }}">
                    <div class="text-xs text-muted-foreground mb-1">Departed for Delivery</div>
                    <div class="font-medium text-xs">{{ $shipment->departed_for_delivery ? $shipment->departed_for_delivery->format('M d, h:i A') : '---' }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function copyTrackingNumber() {
    const trackingNumber = '{{ $shipment->tracking_number }}';
    navigator.clipboard.writeText(trackingNumber).then(() => {
        alert('Tracking number copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

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
            alert(`Shipment duplicated! New tracking: ${data.tracking_number}`);
            if (confirm('Edit the duplicated shipment now?')) {
                window.location.href = data.redirect_url;
            } else {
                window.location.href = '{{ route("admin.shipments.index") }}';
            }
        } else {
            alert('Failed: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to duplicate shipment.');
    }
}

async function deleteShipment(shipmentId) {
    if (!confirm('Are you sure you want to delete this shipment? This cannot be undone.')) return;
    try {
        const response = await fetch(`/admin/shipments/${shipmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        if (response.ok) {
            alert('Shipment deleted!');
            window.location.href = '{{ route("admin.shipments.index") }}';
        } else {
            const data = await response.json();
            alert('Failed: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete shipment.');
    }
}

function viewFullImage(imageUrl) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
    modal.onclick = function() { modal.remove(); };
    modal.innerHTML = `
        <div class="relative max-w-7xl max-h-full">
            <button onclick="this.parentElement.parentElement.remove()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </button>
            <img src="${imageUrl}" alt="Full Size" class="max-w-full max-h-[90vh] mx-auto rounded-lg">
        </div>
    `;
    document.body.appendChild(modal);
}
</script>

@endsection
