@extends('driver.driver_dashboard')
@section('driver')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Delayed Deliveries</h1>
            <p class="text-muted-foreground">Manage and resolve delivery delays</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="refreshPage()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M21 3v5h-5"></path>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                    <path d="M8 16H3v5"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Total Delayed</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-red-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-red-500">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Escalated</p>
                    <p class="text-2xl font-bold">{{ $stats['escalated'] }}</p>
                </div>
                <div class="p-2 bg-orange-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-orange-500">
                        <path d="M12 2v4"></path>
                        <path d="m16.2 7.8 2.9-2.9"></path>
                        <path d="M18 12h4"></path>
                        <path d="m16.2 16.2 2.9 2.9"></path>
                        <path d="M12 18v4"></path>
                        <path d="m4.9 19.1 2.9-2.9"></path>
                        <path d="M2 12h4"></path>
                        <path d="m4.9 4.9 2.9 2.9"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Customer Notified</p>
                    <p class="text-2xl font-bold">{{ $stats['customer_notified'] }}</p>
                </div>
                <div class="p-2 bg-blue-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-500">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Avg Delay</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['avg_delay_hours'], 1) }}h</p>
                </div>
                <div class="p-2 bg-purple-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-purple-500">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-lg border bg-card shadow-sm p-6">
        <form action="{{ route('driver.delayed-deliveries') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="search" name="search" value="{{ $filters['search'] }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm pl-8" placeholder="Search by tracking number, customer, address..."/>
                </div>
                <button type="button" onclick="toggleFilters()" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent rounded-md px-3 h-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                        <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"></path>
                    </svg>
                    Filters
                </button>
            </div>

            <!-- Filter Panel -->
            <div id="filter-panel" class="hidden mt-4 p-4 border rounded-lg bg-muted/50">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm font-medium mb-2 block">Delay Reason</label>
                        <select name="delay_reason" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="">All Reasons</option>
                            <option value="traffic_congestion" {{ ($filters['delay_reason'] ?? '') === 'traffic_congestion' ? 'selected' : '' }}>Traffic Congestion</option>
                            <option value="weather_conditions" {{ ($filters['delay_reason'] ?? '') === 'weather_conditions' ? 'selected' : '' }}>Weather Conditions</option>
                            <option value="vehicle_issues" {{ ($filters['delay_reason'] ?? '') === 'vehicle_issues' ? 'selected' : '' }}>Vehicle Issues</option>
                            <option value="address_issues" {{ ($filters['delay_reason'] ?? '') === 'address_issues' ? 'selected' : '' }}>Address Issues</option>
                            <option value="customer_unavailable" {{ ($filters['delay_reason'] ?? '') === 'customer_unavailable' ? 'selected' : '' }}>Customer Unavailable</option>
                            <option value="mechanical_failure" {{ ($filters['delay_reason'] ?? '') === 'mechanical_failure' ? 'selected' : '' }}>Mechanical Failure</option>
                            <option value="road_closure" {{ ($filters['delay_reason'] ?? '') === 'road_closure' ? 'selected' : '' }}>Road Closure</option>
                            <option value="other" {{ ($filters['delay_reason'] ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">Escalated</label>
                        <select name="escalated" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="">All</option>
                            <option value="1" {{ ($filters['escalated'] ?? '') === '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ ($filters['escalated'] ?? '') === '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">From Date</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">To Date</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="flex gap-2 mt-4">
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-4">
                        Apply Filters
                    </button>
                    <a href="{{ route('driver.delayed-deliveries') }}" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-4">
                        Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Deliveries List -->
    <div class="rounded-lg border bg-card shadow-sm">
        <div class="p-6">
            <h3 class="text-xl font-semibold mb-4">Delayed Deliveries</h3>
            <div class="space-y-4">
                @forelse($shipments as $shipment)
                @php
                    $activeDelay = $shipment->delays->first();
                @endphp
                <div class="border-2 border-red-200 rounded-lg p-4 bg-red-50/50">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="font-semibold text-lg">{{ $shipment->tracking_number }}</h4>
                                    <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-red-100 text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1">
                                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
<path d="M12 9v4"></path>
<path d="M12 17h.01"></path>
</svg>
Delayed
</span>
@if($activeDelay && $activeDelay->escalated)
<span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-orange-100 text-orange-800">
Escalated
</span>
@endif
@if($activeDelay && $activeDelay->customer_notified)
<span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800">
Customer Notified
</span>
@endif
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm mb-3">
                                <div>
                                    <span class="text-muted-foreground">Customer:</span>
                                    <span class="font-medium ml-2">{{ $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Contact:</span>
                                    <span class="font-medium ml-2">{{ $shipment->delivery_contact_phone }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Destination:</span>
                                    <span class="ml-2">{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Original ETA:</span>
                                    <span class="ml-2">{{ $activeDelay && $activeDelay->original_delivery_date ? \Carbon\Carbon::parse($activeDelay->original_delivery_date)->format('M d, Y H:i') : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">New ETA:</span>
                                    <span class="ml-2 font-semibold text-red-600">{{ $activeDelay && $activeDelay->new_delivery_date ? \Carbon\Carbon::parse($activeDelay->new_delivery_date)->format('M d, Y H:i') : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-muted-foreground">Delay:</span>
                                    <span class="ml-2 font-semibold text-red-600">{{ $activeDelay ? $activeDelay->delay_hours . ' hours' : 'N/A' }}</span>
                                </div>
                            </div>

                            @if($activeDelay)
                            <div class="bg-white border border-red-200 rounded-md p-3 mt-2">
                                <div class="flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-600 flex-shrink-0 mt-0.5">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" x2="12" y1="8" y2="12"></line>
                                        <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold mb-1">Reason: {{ ucfirst(str_replace('_', ' ', $activeDelay->delay_reason)) }}</p>
                                        <p class="text-sm text-muted-foreground">{{ $activeDelay->delay_description }}</p>
                                        <p class="text-xs text-muted-foreground mt-1">Reported: {{ $activeDelay->delayed_at ? $activeDelay->delayed_at->format('M d, Y H:i') : 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex flex-col gap-2 md:w-48">
                            <button onclick="viewDelivery({{ $shipment->id }})" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                View Details
                            </button>
                            
                            <button onclick="resolveDelay({{ $shipment->id }})" class="inline-flex items-center justify-center text-sm font-medium bg-green-600 text-white hover:bg-green-700 h-9 rounded-md px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                                Resolve Delay
                            </button>

                            <button onclick="updateDelayInfo({{ $shipment->id }})" class="inline-flex items-center justify-center text-sm font-medium border border-orange-200 bg-orange-50 text-orange-700 hover:bg-orange-100 h-9 rounded-md px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Update Info
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto text-muted-foreground mb-4">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
                <p class="text-muted-foreground">No delayed deliveries found.</p>
                <p class="text-sm text-muted-foreground mt-2">Great job keeping deliveries on time!</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($shipments->hasPages())
        <div class="flex items-center justify-between mt-6 pt-4 border-t">
            <div class="text-sm text-muted-foreground">
                Showing {{ $shipments->firstItem() ?? 0 }} to {{ $shipments->lastItem() ?? 0 }} of {{ $shipments->total() }}
            </div>
            <div>
                {{ $shipments->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
</div>
<!-- Quick View Modal -->
<div id="quick-view-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-semibold">Delivery Details</h3>
                <button onclick="closeQuickView()" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="quick-view-content"></div>
        </div>
    </div>
</div>
<!-- Resolve Delay Modal -->
<div id="resolve-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">Resolve Delay</h3>
                <button onclick="closeResolveModal()" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="resolve-form" class="space-y-4">
                <div>
                    <label class="text-sm font-medium mb-2 block">Resolution Notes *</label>
                    <textarea name="resolution_notes" rows="4" required class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Explain how the delay was resolved..."></textarea>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-md p-3 text-sm text-green-800">
                    <p class="font-medium mb-1">Marking as Resolved</p>
                    <p class="text-xs">This will remove the delivery from the delayed list and log the resolution.</p>
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="button" onclick="closeResolveModal()" class="flex-1 inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-4">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center text-sm font-medium bg-green-600 text-white hover:bg-green-700 h-9 rounded-md px-4">
                        Resolve Delay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Update Delay Info Modal -->
<div id="update-delay-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">Update Delay Information</h3>
                <button onclick="closeUpdateDelayModal()" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="update-delay-form" class="space-y-4">
                <div>
                    <label class="text-sm font-medium mb-2 block">Additional Delay (hours)</label>
                    <input type="number" name="additional_hours" min="1" max="48" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Enter additional hours">
                </div>
                <div>
                    <label class="text-sm font-medium mb-2 block">Update Notes *</label>
                    <textarea name="update_notes" rows="3" required class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Provide update on the situation..."></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="notify_customer" id="notify_customer_update" class="h-4 w-4 rounded border-primary">
                    <label for="notify_customer_update" class="ml-2 text-sm">Notify customer about update</label>
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="button" onclick="closeUpdateDelayModal()" class="flex-1 inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-4">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-4">
                        Update Delay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
let currentShipmentId = null;

function toggleFilters() {
    document.getElementById('filter-panel').classList.toggle('hidden');
}

function refreshPage() {
    window.location.reload();
}

async function viewDelivery(shipmentId) {
    try {
        const response = await fetch(`/driver/deliveries/${shipmentId}/quick-view`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayQuickView(data.shipment);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load delivery details.');
    }
}

function displayQuickView(shipment) {
    const content = document.getElementById('quick-view-content');
    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-muted-foreground">Tracking Number</p>
                    <p class="font-medium">${shipment.tracking_number}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Status</p>
                    <p class="font-medium">${shipment.status}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Customer</p>
                    <p class="font-medium">${shipment.customer}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Contact</p>
                    <p class="font-medium">${shipment.contact_phone}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-muted-foreground">Pickup Address</p>
                    <p class="font-medium">${shipment.pickup_address}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-muted-foreground">Delivery Address</p>
                    <p class="font-medium">${shipment.delivery_address}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Expected Delivery</p>
                    <p class="font-medium">${shipment.expected_delivery}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Priority</p>
                    <p class="font-medium">${shipment.priority}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Items</p>
                    <p class="font-medium">${shipment.items_count}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Weight</p>
                    <p class="font-medium">${shipment.total_weight}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-muted-foreground">Special Instructions</p>
                    <p class="font-medium">${shipment.special_instructions}</p>
                </div>
            </div>
        </div>
    `;
    document.getElementById('quick-view-modal').classList.remove('hidden');
}

function closeQuickView() {
    document.getElementById('quick-view-modal').classList.add('hidden');
}

function resolveDelay(shipmentId) {
    currentShipmentId = shipmentId;
    document.getElementById('resolve-modal').classList.remove('hidden');
}

function closeResolveModal() {
    document.getElementById('resolve-modal').classList.add('hidden');
    document.getElementById('resolve-form').reset();
    currentShipmentId = null;
}

document.getElementById('resolve-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        resolution_notes: formData.get('resolution_notes'),
    };
    
    try {
        const response = await fetch(`/driver/deliveries/${currentShipmentId}/resolve-delay`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            closeResolveModal();
            window.location.reload();
        } else {
            alert('Failed to resolve delay: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to resolve delay.');
    }
});

function updateDelayInfo(shipmentId) {
    currentShipmentId = shipmentId;
    document.getElementById('update-delay-modal').classList.remove('hidden');
}

function closeUpdateDelayModal() {
    document.getElementById('update-delay-modal').classList.add('hidden');
    document.getElementById('update-delay-form').reset();
    currentShipmentId = null;
}

document.getElementById('update-delay-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const additionalHours = formData.get('additional_hours');
    
    const data = {
        delay_reason: 'other', // Keep existing reason
        delay_hours: additionalHours ? parseInt(additionalHours) : 0,
        delay_description: formData.get('update_notes'),
        notify_customer: formData.get('notify_customer') === 'on',
    };
    
    try {
        const response = await fetch(`/driver/deliveries/${currentShipmentId}/report-delay`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Delay information updated successfully!');
            closeUpdateDelayModal();
            window.location.reload();
        } else {
            alert('Failed to update delay: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update delay information.');
    }
});
</script>
@endsection