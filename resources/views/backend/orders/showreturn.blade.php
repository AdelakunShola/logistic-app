@extends('admin.admin_dashboard')
@section('admin')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Return Details</h1>
            <p class="text-muted-foreground">{{ $return->return_number }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.returns.index') }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
                Back to Returns
            </a>
            @if($return->shipment)
            <a href="{{ route('admin.shipments.show', $return->shipment->id) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
                View Original Shipment
            </a>
            @endif
        </div>
    </div>

    <!-- Status Alert -->
    @if($return->status === 'pending_review')
    <div class="rounded-lg border-l-4 border-yellow-500 bg-yellow-50 p-4">
        <div class="flex items-start">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-yellow-600 mt-0.5 mr-3">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" x2="12" y1="8" y2="12"></line>
                <line x1="12" x2="12.01" y1="16" y2="16"></line>
            </svg>
            <div>
                <h4 class="text-sm font-semibold text-yellow-900">Pending Review</h4>
                <p class="text-sm text-yellow-700">This return request is awaiting review and approval.</p>
            </div>
        </div>
    </div>
    @elseif($return->status === 'approved')
    <div class="rounded-lg border-l-4 border-green-500 bg-green-50 p-4">
        <div class="flex items-start">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-green-600 mt-0.5 mr-3">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <path d="m9 11 3 3L22 4"></path>
            </svg>
            <div>
                <h4 class="text-sm font-semibold text-green-900">Return Approved</h4>
                <p class="text-sm text-green-700">This return has been approved. Tracking: {{ $return->tracking_number }}</p>
            </div>
        </div>
    </div>
    @elseif($return->status === 'rejected')
    <div class="rounded-lg border-l-4 border-red-500 bg-red-50 p-4">
        <div class="flex items-start">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-red-600 mt-0.5 mr-3">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m15 9-6 6"></path>
                <path d="m9 9 6 6"></path>
            </svg>
            <div>
                <h4 class="text-sm font-semibold text-red-900">Return Rejected</h4>
                <p class="text-sm text-red-700">{{ $return->rejection_reason ?? 'This return has been rejected.' }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Return Information -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Return Information</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Return Number</label>
                            <p class="text-lg font-semibold">{{ $return->return_number }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Order Number</label>
                            <p class="text-lg">{{ $return->order_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Status</label>
                            <div>
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 {{ $return->status_badge['class'] ?? '' }}">
                                    {{ $return->status_badge['text'] ?? ucfirst(str_replace('_', ' ', $return->status)) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Request Date</label>
                            <p>{{ $return->request_date ? $return->request_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Warehouse</label>
                            <p>{{ $return->warehouse ?? 'Main Warehouse' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Tracking Number</label>
                            <p>{{ $return->tracking_number ?? 'Not assigned yet' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Return Reason & Description -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Return Reason</h3>
                </div>
                <div class="p-6 pt-0 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">Reason</label>
                        <p class="text-lg font-medium">{{ $return->formatted_return_reason ?? ucfirst(str_replace('_', ' ', $return->return_reason)) }}</p>
                    </div>
                    @if($return->description)
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">Description</label>
                        <p class="text-sm">{{ $return->description }}</p>
                    </div>
                    @endif
                    @if($return->customer_notes)
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">Customer Notes</label>
                        <p class="text-sm">{{ $return->customer_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Return Items -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Return Items</h3>
                </div>
                <div class="p-6 pt-0">
                    @if($return->items && count($return->items) > 0)
                    <div class="space-y-3">
                        @foreach($return->items as $item)
                        <div class="p-4 border rounded-md">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">{{ $item['description'] ?? $item['name'] ?? 'N/A' }}</p>
                                    <p class="text-sm text-muted-foreground">
                                        Quantity: {{ $item['quantity'] ?? 1 }} | 
                                        Weight: {{ $item['weight'] ?? 'N/A' }} kg
                                        @if(isset($item['category']))
                                        | Category: {{ ucfirst($item['category']) }}
                                        @endif
                                    </p>
                                </div>
                                <p class="font-semibold">${{ number_format(($item['value'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted-foreground">No items information available</p>
                    @endif
                </div>
            </div>

            <!-- Attached Images -->
            @if($return->attached_images && count($return->attached_images) > 0)
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Attached Images</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($return->attached_images as $image)
                        <a href="{{ Storage::url($image) }}" target="_blank" class="border rounded-md overflow-hidden hover:opacity-75">
                            <img src="{{ Storage::url($image) }}" alt="Return image" class="w-full h-32 object-cover">
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Pickup Address -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Pickup Location</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="bg-muted p-4 rounded-md">
                        <p class="font-medium">{{ $return->pickup_contact_name ?? 'N/A' }}</p>
                        <p class="text-sm">{{ $return->pickup_address ?? 'No address provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Financial Summary -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Financial Summary</h3>
                </div>
                <div class="p-6 pt-0 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-muted-foreground">Total Amount</span>
                        <span class="text-lg font-semibold">${{ number_format($return->total_amount ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-muted-foreground">Return Value</span>
                        <span class="text-lg font-semibold">${{ number_format($return->return_value ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t">
                        <span class="text-sm font-medium">Refund Amount</span>
                        <span class="text-xl font-bold text-primary">${{ number_format($return->refund_amount ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-muted-foreground">Refund Status</span>
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold">
                            {{ ucfirst($return->refund_status) }}
                        </span>
                    </div>
                    @if($return->refund_method)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-muted-foreground">Refund Method</span>
                        <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $return->refund_method)) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Customer Information</h3>
                </div>
                <div class="p-6 pt-0 space-y-3">
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">Name</label>
                        <p>{{ optional($return->customer)->first_name }} {{ optional($return->customer)->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">Email</label>
                        <p>{{ optional($return->customer)->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">Order History</label>
                        <p class="text-sm">{{ $return->customer_order_count ?? 0 }} orders</p>
                        <p class="text-sm text-muted-foreground">{{ $return->customer_return_count ?? 0 }} previous returns</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-muted-foreground">Customer Since</label>
                        <p class="text-sm">{{ $return->customer_since ? \Carbon\Carbon::parse($return->customer_since)->format('F Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($return->status === 'pending_review')
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Actions</h3>
                </div>
                <div class="p-6 pt-0 space-y-3">
                    <form action="{{ route('admin.returns.approve', $return->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 rounded-md px-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                            Approve Return
                        </button>
                    </form>
                    
                    <button onclick="openRejectModal()" class="w-full inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 rounded-md px-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m15 9-6 6"></path>
                            <path d="m9 9 6 6"></path>
                        </svg>
                        Reject Return
                    </button>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Timeline</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-primary mt-2"></div>
                            <div>
                                <p class="font-medium">Return Requested</p>
                                <p class="text-sm text-muted-foreground">{{ $return->request_date ? $return->request_date->format('M d, Y g:i A') : 'N/A' }}</p>
                            </div>
                        </div>
                        @if($return->reviewed_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-primary mt-2"></div>
                            <div>
                                <p class="font-medium">Reviewed</p>
                                <p class="text-sm text-muted-foreground">{{ \Carbon\Carbon::parse($return->reviewed_at)->format('M d, Y g:i A') }}</p>
                                @if($return->reviewedBy)
                                <p class="text-sm text-muted-foreground">By: {{ $return->reviewedBy->first_name }} {{ $return->reviewedBy->last_name }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($return->approved_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                            <div>
                                <p class="font-medium">Approved</p>
                                <p class="text-sm text-muted-foreground">{{ \Carbon\Carbon::parse($return->approved_at)->format('M d, Y g:i A') }}</p>
                                @if($return->approvedBy)
                                <p class="text-sm text-muted-foreground">By: {{ $return->approvedBy->first_name }} {{ $return->approvedBy->last_name }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($return->completed_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                            <div>
                                <p class="font-medium">Completed</p>
                                <p class="text-sm text-muted-foreground">{{ \Carbon\Carbon::parse($return->completed_at)->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <form action="{{ route('admin.returns.reject', $return->id) }}" method="POST">
            @csrf
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Reject Return Request</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium mb-2 block">Rejection Reason <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" required rows="4" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="Provide reason for rejection..."></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">Admin Notes (Optional)</label>
                        <textarea name="admin_notes" rows="3" class="flex min-h-[60px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="Internal notes..."></textarea>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 justify-end p-6 border-t">
                <button type="button" onclick="closeRejectModal()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-8">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 rounded-md px-8">
                    Reject Return
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
    }
});

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>

@endsection