@extends('driver.driver_dashboard')
@section('driver')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Completed Deliveries</h1>
            <p class="text-muted-foreground">View your delivery history and performance</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.location.href='{{ route('driver.deliveries.export', 'pdf') }}'" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M12 15V3"></path>
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <path d="m7 10 5 5 5-5"></path>
                </svg>
                Export
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Total Completed</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-green-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-green-500">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Today</p>
                    <p class="text-2xl font-bold">{{ $stats['today'] }}</p>
                </div>
                <div class="p-2 bg-blue-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-500">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                        <line x1="16" x2="16" y1="2" y2="6"></line>
                        <line x1="8" x2="8" y1="2" y2="6"></line>
                        <line x1="3" x2="21" y1="10" y2="10"></line>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">This Week</p>
                    <p class="text-2xl font-bold">{{ $stats['this_week'] }}</p>
                </div>
                <div class="p-2 bg-purple-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-purple-500">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">This Month</p>
                    <p class="text-2xl font-bold">{{ $stats['this_month'] }}</p>
                </div>
                <div class="p-2 bg-orange-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-orange-500">
<path d="M21 10H3"></path>
<path d="M21 6H3"></path>
<path d="M21 14H3"></path>
<path d="M21 18H3"></path>
</svg>
</div>
</div>
</div>
<div class="rounded-lg border bg-card shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-muted-foreground">On-Time</p>
                <p class="text-2xl font-bold">{{ $stats['on_time'] }}</p>
                <p class="text-xs text-muted-foreground">{{ $stats['total'] > 0 ? round(($stats['on_time'] / $stats['total']) * 100, 1) : 0 }}%</p>
            </div>
            <div class="p-2 bg-green-500/10 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-green-500">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="rounded-lg border bg-card shadow-sm p-6">
    <form action="{{ route('driver.completed-deliveries') }}" method="GET">
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-medium mb-2 block">From Date</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium mb-2 block">To Date</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium mb-2 block">Sort By</label>
                    <select name="sort_by" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="actual_delivery_date">Delivery Date</option>
                        <option value="tracking_number">Tracking Number</option>
                        <option value="delivery_city">Location</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="inline-flex items-center justify-center text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-4">
                    Apply Filters
                </button>
                <a href="{{ route('driver.completed-deliveries') }}" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-4">
                    Clear Filters
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Deliveries List -->
<div class="rounded-lg border bg-card shadow-sm">
    <div class="p-6">
        <h3 class="text-xl font-semibold mb-4">Completed Deliveries</h3>
        <div class="space-y-4">
            @forelse($shipments as $shipment)
            <div class="border rounded-lg p-4 bg-green-50/50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="font-semibold text-lg">{{ $shipment->tracking_number }}</h4>
                            <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="m9 12 2 2 4-4"></path>
                                </svg>
                                Delivered
                            </span>
                            @if($shipment->actual_delivery_date && $shipment->expected_delivery_date && $shipment->actual_delivery_date <= $shipment->expected_delivery_date)
                            <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800">
                                On Time
                            </span>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-muted-foreground">Customer:</span>
                                <span class="font-medium ml-2">{{ $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-muted-foreground">Delivered To:</span>
                                <span class="ml-2">{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}</span>
                            </div>
                            <div>
                                <span class="text-muted-foreground">Delivered On:</span>
                                <span class="ml-2">{{ $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('M d, Y H:i') : 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-muted-foreground">Items:</span>
                                <span class="ml-2">{{ $shipment->number_of_items }} ({{ number_format($shipment->total_weight, 1) }} lbs)</span>
                            </div>
                        </div>

                        @if($shipment->delivery_notes)
                        <div class="mt-2 text-sm">
                            <span class="text-muted-foreground">Notes:</span>
                            <span class="ml-2">{{ $shipment->delivery_notes }}</span>
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
                        
                       
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto text-muted-foreground mb-4">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
                <p class="text-muted-foreground">No completed deliveries found.</p>
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
<script>
function toggleFilters() {
    document.getElementById('filter-panel').classList.toggle('hidden');
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
    // Payment Information HTML
    let paymentHtml = '';
    if (shipment.payment_mode === 'cod') {
        paymentHtml = `
            <div class="col-span-2 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 mt-1">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-800">COD Payment Collected</p>
                        <p class="text-green-700 text-sm mt-1">Amount: <span class="font-bold">$${parseFloat(shipment.cod_amount).toFixed(2)}</span></p>
                    </div>
                </div>
            </div>
        `;
    } else {
        paymentHtml = `
            <div class="col-span-2">
                <p class="text-muted-foreground">Payment Mode</p>
                <p class="font-medium">${shipment.payment_mode.toUpperCase()}</p>
            </div>
        `;
    }
    
    // Delivery Proof Section (Signature & Photo)
    let deliveryProofHtml = '';
    if (shipment.delivery_signature || shipment.delivery_photo) {
        deliveryProofHtml = `
            <div class="col-span-2 mt-4 pt-4 border-t">
                <h4 class="font-semibold mb-3 text-lg">Delivery Proof</h4>
                <div class="space-y-4">
        `;
        
        if (shipment.delivery_signature) {
            deliveryProofHtml += `
                <div>
                    <p class="text-sm text-muted-foreground mb-2">Customer Signature</p>
                    <div class="border rounded-lg p-3 bg-white">
                        <img src="${shipment.delivery_signature}" alt="Customer Signature" class="max-w-full h-auto mx-auto" style="max-height: 150px;">
                    </div>
                </div>
            `;
        }
        
        if (shipment.delivery_photo) {
            deliveryProofHtml += `
                <div>
                    <p class="text-sm text-muted-foreground mb-2">Delivery Photo</p>
                    <div class="border rounded-lg p-3 bg-white">
                        <img src="${shipment.delivery_photo}" alt="Delivery Photo" class="max-w-full h-auto mx-auto rounded cursor-pointer" style="max-height: 200px;" onclick="viewFullImage('${shipment.delivery_photo}')">
                    </div>
                    <button onclick="viewFullImage('${shipment.delivery_photo}')" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6l4 4"/>
                        </svg>
                        View Full Size
                    </button>
                </div>
            `;
        }
        
        deliveryProofHtml += `
                </div>
            </div>
        `;
    }
    
    // Delivery Notes
    let notesHtml = '';
    if (shipment.delivery_notes) {
        notesHtml = `
            <div class="col-span-2">
                <p class="text-muted-foreground">Delivery Notes</p>
                <div class="mt-1 p-3 bg-muted rounded-lg text-sm">
                    ${shipment.delivery_notes}
                </div>
            </div>
        `;
    }
    
    // Items Section
    let itemsHtml = '';
    if (shipment.items && shipment.items.length > 0) {
        itemsHtml = `
            <div class="col-span-2 mt-4 pt-4 border-t">
                <h4 class="font-semibold mb-3">Shipment Items (${shipment.items.length})</h4>
                <div class="space-y-2">
                    ${shipment.items.map((item, index) => `
                        <div class="flex items-start gap-3 p-3 bg-muted/30 rounded-lg border">
                            <div class="flex items-center justify-center w-8 h-8 bg-primary/10 rounded-full text-primary font-semibold text-sm">
                                ${index + 1}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm">${item.description}</div>
                                <div class="text-xs text-muted-foreground mt-1">
                                    Qty: ${item.quantity} | Weight: ${item.weight} lbs
                                    ${item.dimensions ? ` | Size: ${item.dimensions}` : ''}
                                </div>
                                ${item.special_handling ? '<div class="text-xs text-orange-600 mt-1">⚠️ Special Handling Required</div>' : ''}
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    // Special Services
    let specialServicesHtml = '';
    const specialServices = [];
    if (shipment.insurance_required) specialServices.push(`Insurance: $${parseFloat(shipment.insurance_amount || 0).toFixed(2)}`);
    if (shipment.signature_required) specialServices.push('Signature Required');
    if (shipment.temperature_controlled) specialServices.push('Temperature Controlled');
    if (shipment.fragile_handling) specialServices.push('Fragile Handling');
    
    if (specialServices.length > 0) {
        specialServicesHtml = `
            <div class="col-span-2">
                <p class="text-muted-foreground">Special Services</p>
                <div class="mt-1 flex flex-wrap gap-2">
                    ${specialServices.map(service => `
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            ${service}
                        </span>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    const content = document.getElementById('quick-view-content');
    content.innerHTML = `
        <div class="space-y-4">
            ${paymentHtml}
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-muted-foreground">Tracking Number</p>
                    <p class="font-medium">${shipment.tracking_number}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                        ${shipment.status}
                    </span>
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
                    <p class="text-muted-foreground">Actual Delivery</p>
                    <p class="font-medium text-green-600">${shipment.actual_delivery}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Priority</p>
                    <p class="font-medium capitalize">${shipment.priority}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Total Amount</p>
                    <p class="font-medium">$${parseFloat(shipment.total_amount || 0).toFixed(2)}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Total Items</p>
                    <p class="font-medium">${shipment.items_count}</p>
                </div>
                <div>
                    <p class="text-muted-foreground">Total Weight</p>
                    <p class="font-medium">${shipment.total_weight}</p>
                </div>
                ${specialServicesHtml}
                ${notesHtml}
                <div class="col-span-2">
                    <p class="text-muted-foreground">Special Instructions</p>
                    <p class="font-medium">${shipment.special_instructions || 'None'}</p>
                </div>
            </div>
            ${itemsHtml}
            ${deliveryProofHtml}
        </div>
    `;
    document.getElementById('quick-view-modal').classList.remove('hidden');
}

// Add function to view full size image
function viewFullImage(imageUrl) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-[100] flex items-center justify-center p-4';
    modal.onclick = function(e) { 
        if (e.target === modal) modal.remove(); 
    };
    
    modal.innerHTML = `
        <div class="relative max-w-7xl max-h-full">
            <button onclick="this.parentElement.parentElement.remove()" class="absolute -top-10 right-0 text-white hover:text-gray-300 bg-black/50 rounded-full p-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
            <img src="${imageUrl}" alt="Full Size" class="max-w-full max-h-[90vh] mx-auto rounded-lg shadow-2xl">
        </div>
    `;
    
    document.body.appendChild(modal);
}

function closeQuickView() {
    document.getElementById('quick-view-modal').classList.add('hidden');
}

</script>
@endsection