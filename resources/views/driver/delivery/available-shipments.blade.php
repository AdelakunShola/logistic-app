@extends('driver.driver_dashboard')
@section('driver')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Available Shipments</h1>
            <p class="text-muted-foreground">Unassigned shipments you can accept</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.location.reload()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
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
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Total Available</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-orange-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-orange-500">
                        <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"></path>
                        <path d="m7.5 4.27 9 5.15"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Express Priority</p>
                    <p class="text-2xl font-bold">{{ $stats['express'] }}</p>
                </div>
                <div class="p-2 bg-red-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-red-500">
                        <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Overnight</p>
                    <p class="text-2xl font-bold">{{ $stats['overnight'] }}</p>
                </div>
                <div class="p-2 bg-purple-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-purple-500">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border bg-card shadow-sm">
        <div class="p-4">
            <form method="GET" action="{{ route('driver.available-shipments') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by tracking number, city..." class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                </div>
                <select name="priority" class="h-9 rounded-md border border-input bg-transparent px-3 text-sm">
                    <option value="">All Priorities</option>
                    <option value="standard" {{ ($filters['priority'] ?? '') == 'standard' ? 'selected' : '' }}>Standard</option>
                    <option value="express" {{ ($filters['priority'] ?? '') == 'express' ? 'selected' : '' }}>Express</option>
                    <option value="overnight" {{ ($filters['priority'] ?? '') == 'overnight' ? 'selected' : '' }}>Overnight</option>
                </select>
                <button type="submit" class="inline-flex items-center justify-center h-9 rounded-md bg-primary text-primary-foreground px-4 text-sm font-medium hover:bg-primary/90">
                    Filter
                </button>
                @if(($filters['search'] ?? '') || ($filters['priority'] ?? ''))
                    <a href="{{ route('driver.available-shipments') }}" class="inline-flex items-center justify-center h-9 rounded-md border border-input bg-background px-4 text-sm font-medium hover:bg-accent">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Shipments List -->
    @if($shipments->count() > 0)
        <div class="space-y-3">
            @foreach($shipments as $shipment)
                <div class="rounded-lg border bg-card shadow-sm hover:shadow-md transition-shadow" id="shipment-card-{{ $shipment->id }}">
                    <div class="p-4 md:p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Shipment Info -->
                            <div class="flex-1 space-y-2">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span class="font-mono text-sm font-semibold">{{ $shipment->tracking_number }}</span>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                        {{ $shipment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                    @if($shipment->delivery_priority === 'express')
                                        <span class="inline-flex items-center rounded-full bg-red-100 text-red-800 px-2 py-0.5 text-xs font-semibold">Express</span>
                                    @elseif($shipment->delivery_priority === 'overnight')
                                        <span class="inline-flex items-center rounded-full bg-purple-100 text-purple-800 px-2 py-0.5 text-xs font-semibold">Overnight</span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                                    <div class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-500 mt-0.5 shrink-0">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <div>
                                            <p class="text-muted-foreground text-xs">Pickup</p>
                                            <p class="font-medium">{{ $shipment->pickup_city ?? 'N/A' }}{{ $shipment->pickup_state ? ', ' . $shipment->pickup_state : '' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-500 mt-0.5 shrink-0">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <div>
                                            <p class="text-muted-foreground text-xs">Delivery</p>
                                            <p class="font-medium">{{ $shipment->delivery_city ?? 'N/A' }}{{ $shipment->delivery_state ? ', ' . $shipment->delivery_state : '' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 text-xs text-muted-foreground">
                                    @if($shipment->expected_delivery_date)
                                        <span>Due: {{ \Carbon\Carbon::parse($shipment->expected_delivery_date)->format('M d, Y') }}</span>
                                    @endif
                                    @if($shipment->total_weight)
                                        <span>Weight: {{ $shipment->total_weight }} {{ $shipment->weight_unit ?? 'kg' }}</span>
                                    @endif
                                    @if($shipment->shipmentItems && $shipment->shipmentItems->count() > 0)
                                        <span>{{ $shipment->shipmentItems->count() }} item(s)</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Accept Button -->
                            <div class="flex-shrink-0">
                                <button onclick="acceptShipment({{ $shipment->id }})" class="inline-flex items-center justify-center gap-2 rounded-md bg-green-600 text-white px-5 py-2.5 text-sm font-semibold hover:bg-green-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 6 9 17l-5-5"></path>
                                    </svg>
                                    Accept Shipment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $shipments->links() }}
        </div>
    @else
        <div class="rounded-lg border bg-card shadow-sm">
            <div class="p-12 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-muted flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted-foreground">
                        <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"></path>
                        <path d="m7.5 4.27 9 5.15"></path>
                        <polyline points="3.29 7 12 12 20.71 7"></polyline>
                        <line x1="12" x2="12" y1="22" y2="12"></line>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-1">No Available Shipments</h3>
                <p class="text-muted-foreground text-sm">There are no unassigned shipments at the moment. Check back later.</p>
            </div>
        </div>
    @endif
</div>

<script>
async function acceptShipment(shipmentId) {
    const confirmed = await showConfirm(
        'Are you sure you want to accept this shipment? It will be added to your active deliveries.',
        'Accept Shipment',
        'info'
    );

    if (!confirmed) return;

    try {
        const response = await fetch(`/driver/deliveries/${shipmentId}/self-assign`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message || 'Shipment accepted successfully!', 'success');
            const card = document.getElementById(`shipment-card-${shipmentId}`);
            if (card) {
                card.style.transition = 'opacity 0.3s, transform 0.3s';
                card.style.opacity = '0';
                card.style.transform = 'translateX(20px)';
                setTimeout(() => card.remove(), 300);
            }
            // Update stats
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast(data.message || 'Failed to accept shipment.', 'error');
        }
    } catch (error) {
        showToast('An error occurred. Please try again.', 'error');
    }
}
</script>
@endsection
