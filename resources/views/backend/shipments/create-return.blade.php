@extends('admin.admin_dashboard')
@section('admin')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Create Return Request</h1>
            <p class="text-muted-foreground">Initiate return for shipment {{ $shipment->tracking_number }}</p>
        </div>
        <a href="{{ route('admin.shipments.show', $shipment->id) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left h-4 w-4 mr-2">
                <path d="m12 19-7-7 7-7"></path>
                <path d="M19 12H5"></path>
            </svg>
            Back to Shipment
        </a>
    </div>

    <form action="{{ route('admin.shipments.store-return', $shipment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Shipment Information Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-xl font-semibold leading-none tracking-tight">Shipment Details</h3>
            </div>
            <div class="p-6 pt-0">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Tracking Number</label>
                        <p class="text-lg font-semibold">{{ $shipment->tracking_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Delivery Date</label>
                        <p>{{ $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Customer</label>
                        <p>{{ $shipment->customer->first_name }} {{ $shipment->customer->last_name }}</p>
                        <p class="text-sm text-muted-foreground">{{ $shipment->customer->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Total Value</label>
                        <p class="text-lg font-semibold">${{ number_format($shipment->total_value, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Items Selection -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-xl font-semibold leading-none tracking-tight">Select Items to Return</h3>
                <p class="text-sm text-muted-foreground">Choose which items from this shipment you want to return</p>
            </div>
            <div class="p-6 pt-0">
                <div class="space-y-3">
                    @forelse($shipment->shipmentItems as $item)
                    <label class="flex items-center p-4 border rounded-md hover:bg-muted/50 cursor-pointer">
                        <input type="checkbox" name="return_items[]" value="{{ $item->id }}" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary" checked>
                        <div class="ml-4 flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">{{ $item->description }}</p>
                                    <p class="text-sm text-muted-foreground">
                                        Quantity: {{ $item->quantity }} | 
                                        Weight: {{ $item->weight }} kg | 
                                        Category: {{ ucfirst($item->category) }}
                                    </p>
                                </div>
                                <p class="font-semibold">${{ number_format($item->value * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                    </label>
                    @empty
                    <p class="text-muted-foreground text-center py-8">No items found in this shipment</p>
                    @endforelse
                </div>
                @error('return_items')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Return Reason -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-xl font-semibold leading-none tracking-tight">Return Information</h3>
            </div>
            <div class="p-6 pt-0 space-y-4">
                <!-- Return Reason -->
                <div>
                    <label class="text-sm font-medium mb-2 block">Return Reason <span class="text-red-500">*</span></label>
                    <select name="return_reason" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="">Select reason...</option>
                        <option value="defective_product">Defective Product</option>
                        <option value="wrong_item_sent">Wrong Item Sent</option>
                        <option value="changed_mind">Changed Mind</option>
                        <option value="damaged_in_transit">Damaged in Transit</option>
                        <option value="not_as_described">Not as Described</option>
                        <option value="quality_issue">Quality Issue</option>
                        <option value="size_issue">Size Issue</option>
                        <option value="other">Other</option>
                    </select>
                    @error('return_reason')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="text-sm font-medium mb-2 block">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" required rows="4" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Provide detailed explanation for the return...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer Notes (Optional) -->
                <div>
                    <label class="text-sm font-medium mb-2 block">Additional Notes (Optional)</label>
                    <textarea name="customer_notes" rows="3" class="flex min-h-[60px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Any additional information...">{{ old('customer_notes') }}</textarea>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="text-sm font-medium mb-2 block">Attach Images (Optional)</label>
                    <p class="text-xs text-muted-foreground mb-2">Upload photos showing the issue (max 5MB per image)</p>
                    <input type="file" name="images[]" multiple accept="image/*" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    @error('images.*')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pickup Address -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-xl font-semibold leading-none tracking-tight">Pickup Location</h3>
                <p class="text-sm text-muted-foreground">Items will be picked up from the delivery address</p>
            </div>
            <div class="p-6 pt-0">
                <div class="bg-muted p-4 rounded-md">
                    <p class="font-medium">{{ $shipment->delivery_contact_name }}</p>
                    <p class="text-sm">{{ $shipment->delivery_address }}</p>
                    @if($shipment->delivery_address_line2)
                    <p class="text-sm">{{ $shipment->delivery_address_line2 }}</p>
                    @endif
                    <p class="text-sm">{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }} {{ $shipment->delivery_postal_code }}</p>
                    <p class="text-sm mt-2">Phone: {{ $shipment->delivery_contact_phone }}</p>
                    @if($shipment->delivery_contact_email)
                    <p class="text-sm">Email: {{ $shipment->delivery_contact_email }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.shipments.show', $shipment->id) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-8">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 rounded-md px-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-4 w-4 mr-2">
                    <path d="M20 6 9 17l-5-5"></path>
                </svg>
                Submit Return Request
            </button>
        </div>
    </form>
</div>

@endsection