@extends('admin.admin_dashboard')
@section('admin')

<style>
.step-content { display: none; }
.step-content.active { display: block; }
.item-card { position: relative; }
.remove-item-btn { position: absolute; top: 10px; right: 10px; z-index: 10; }






/* Toggle Switch Styles */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e5e7eb;
    transition: 0.4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.toggle-switch input:checked + .toggle-slider {
    background-color: #2563eb;
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(20px);
}
</style>

<div class="mx-auto w-full max-w-6xl">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold">Create New Shipment</h1>
        <p class="text-muted-foreground mt-2">Follow the steps below to create and schedule your shipment</p>
    </div>

    <!-- Progress Bar -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm mb-8">
        <div class="md:p-6 p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium">Step <span id="current-step">1</span> of 5</span>
                <span class="text-sm text-muted-foreground"><span id="progress-percent">20</span>% Complete</span>
            </div>
            <div class="relative h-2 w-full overflow-hidden rounded-full bg-secondary mb-4">
                <div id="progress-bar" class="h-full bg-primary transition-all duration-300" style="width: 20%"></div>
            </div>
            <div class="flex flex-wrap gap-2 text-nowrap sm:justify-between text-xs text-muted-foreground">
                <span id="step-label-1" class="text-primary font-medium">Shipment Type</span>
                <span id="step-label-2">Addresses</span>
                <span id="step-label-3">Package Details</span>
                <span id="step-label-4">Services</span>
                <span id="step-label-5">Review</span>
            </div>
        </div>
    </div>

    <form id="shipment-form" method="POST" action="{{ route('admin.shipments.store') }}">
        @csrf


		@if($errors->any())
    <div class="rounded-lg bg-red-50 border border-red-200 p-4 mb-4">
        <div class="flex">
            <div class="text-red-800">
                <strong>Validation Errors:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="rounded-lg bg-red-50 border border-red-200 p-4 mb-4">
        <div class="text-red-800">{{ session('error') }}</div>
    </div>
@endif

@if(session('success'))
    <div class="rounded-lg bg-green-50 border border-green-200 p-4 mb-4">
        <div class="text-green-800">{{ session('success') }}</div>
    </div>
@endif
        
        <!-- Step 1: Shipment Type & Priority -->
        <div id="step-1" class="step-content active">
            <div class="space-y-6 min-h-[800px]">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                        <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                <path d="M12 22V12"></path>
                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                            </svg>
                            Shipment Type & Priority
                        </h3>
                        <div class="text-sm text-muted-foreground">Select the type of shipment and delivery priority</div>
                    </div>
                    <div class="p-4 md:p-6 pt-0 space-y-6">
                        <div class="space-y-3">
                            <label class="text-sm font-medium">Shipment Type</label>
                            <div class="grid gap-2">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="shipment_type" value="Standard Package" checked class="h-4 w-4" onchange="calculatePricing()">
                                    <span>Standard Package</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="shipment_type" value="Document Envelope" class="h-4 w-4" onchange="calculatePricing()">
                                    <span>Document Envelope</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="shipment_type" value="Freight/Pallet" class="h-4 w-4" onchange="calculatePricing()">
                                    <span>Freight/Pallet</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="shipment_type" value="Bulk Cargo" class="h-4 w-4" onchange="calculatePricing()">
                                    <span>Bulk Cargo</span>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-3">
    <label class="text-sm font-medium">Delivery Priority</label>
    <div class="grid gap-2">
        <label class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-accent">
            <div class="flex items-center space-x-2">
                <input type="radio" name="delivery_priority" value="standard" 
                       data-price="{{ $pricingSettings['standard_package']['standard'] }}" 
                       data-days="5-7 business days" 
                       checked class="h-4 w-4" 
                       onchange="calculatePricing()">
                <div>
                    <div class="font-medium">Standard</div>
                    <p class="text-sm text-muted-foreground">5-7 business days</p>
                </div>
            </div>
            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-secondary" data-priority="standard">
                {{ $pricingSettings['currency_symbol'] }}{{ number_format($pricingSettings['standard_package']['standard'], 2) }}
            </span>
        </label>
        <label class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-accent">
            <div class="flex items-center space-x-2">
                <input type="radio" name="delivery_priority" value="express" 
                       data-price="{{ $pricingSettings['standard_package']['express'] }}" 
                       data-days="2-3 business days" 
                       class="h-4 w-4" 
                       onchange="calculatePricing()">
                <div>
                    <div class="font-medium">Express</div>
                    <p class="text-sm text-muted-foreground">2-3 business days</p>
                </div>
            </div>
            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-secondary" data-priority="express">
                {{ $pricingSettings['currency_symbol'] }}{{ number_format($pricingSettings['standard_package']['express'], 2) }}
            </span>
        </label>
        <label class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-accent">
            <div class="flex items-center space-x-2">
                <input type="radio" name="delivery_priority" value="overnight" 
                       data-price="{{ $pricingSettings['standard_package']['overnight'] }}" 
                       data-days="Next business day" 
                       class="h-4 w-4" 
                       onchange="calculatePricing()">
                <div>
                    <div class="font-medium">Overnight</div>
                    <p class="text-sm text-muted-foreground">Next business day</p>
                </div>
            </div>
            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-secondary" data-priority="overnight">
                {{ $pricingSettings['currency_symbol'] }}{{ number_format($pricingSettings['standard_package']['overnight'], 2) }}
            </span>
        </label>
    </div>
</div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Pickup / Dropoff Date</label>
                                <input type="date" name="pickup_date" class="w-full px-4 py-2 border rounded-md" min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Preferred Delivery Date</label>
                                <input type="date" name="preferred_delivery_date" class="w-full px-4 py-2 border rounded-md" min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium">Customer</label>
                            <select name="customer_id" id="customer_id" class="w-full px-4 py-2 border rounded-md" >
                                <option value="">Select customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Addresses -->
        <div id="step-2" class="step-content">
            <div class="space-y-6">
                <!-- Origin Address -->
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                        <h3 class="text-xl sm:text-2xl font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            Origin / Sender's Address
                        </h3>
                        <div class="text-sm text-muted-foreground">Enter the pickup or sender's address details</div>
                    </div>
                    <div class="p-4 md:p-6 pt-0 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Company Name</label>
                                <input type="text" name="pickup_company_name" class="w-full px-4 py-2 border rounded-md" placeholder="Enter company name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Contact Name *</label>
                                <input type="text" name="pickup_contact_name" class="w-full px-4 py-2 border rounded-md" placeholder="Enter contact name" >
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Address Line 1 *</label>
                            <input type="text" name="pickup_address" id="pickup_address" class="w-full px-4 py-2 border rounded-md" placeholder="Enter street address"  onchange="calculatePricing()">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Address Line 2 (Optional)</label>
                            <input type="text" name="pickup_address_line2" class="w-full px-4 py-2 border rounded-md" placeholder="Apartment, suite, etc.">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">City *</label>
                                <input type="text" name="pickup_city" id="pickup_city" class="w-full px-4 py-2 border rounded-md" placeholder="Enter city" >
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">State/Province *</label>
                                <input type="text" name="pickup_state" id="pickup_state" class="w-full px-4 py-2 border rounded-md" placeholder="Enter state" >
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">ZIP/Postal Code *</label>
                                <input type="text" name="pickup_postal_code" id="pickup_postal_code" class="w-full px-4 py-2 border rounded-md" placeholder="Enter ZIP code" >
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Phone Number *</label>
                                <input type="tel" name="pickup_contact_phone" class="w-full px-4 py-2 border rounded-md" placeholder="Enter phone number" >
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Email Address</label>
                                <input type="email" name="pickup_contact_email" class="w-full px-4 py-2 border rounded-md" placeholder="Enter email address">
                            </div>
                        </div>
                        <input type="hidden" name="pickup_country" value="USA">
                        <input type="hidden" name="pickup_latitude" id="pickup_latitude">
                        <input type="hidden" name="pickup_longitude" id="pickup_longitude">
                    </div>
                </div>

                <!-- Destination Address -->
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                        <h3 class="text-xl sm:text-2xl font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            Destination Address
                        </h3>
                        <div class="text-sm text-muted-foreground">Enter the delivery location details</div>
                    </div>
                    <div class="p-4 md:p-6 pt-0 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Company Name</label>
                                <input type="text" name="delivery_company_name" class="w-full px-4 py-2 border rounded-md" placeholder="Enter company name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Contact Name *</label>
                                <input type="text" name="delivery_contact_name" class="w-full px-4 py-2 border rounded-md" placeholder="Enter contact name" >
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Address Line 1 *</label>
                            <input type="text" name="delivery_address" id="delivery_address" class="w-full px-4 py-2 border rounded-md" placeholder="Enter street address"  onchange="calculatePricing()">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Address Line 2 (Optional)</label>
                            <input type="text" name="delivery_address_line2" class="w-full px-4 py-2 border rounded-md" placeholder="Apartment, suite, etc.">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">City *</label>
                                <input type="text" name="delivery_city" id="delivery_city" class="w-full px-4 py-2 border rounded-md" placeholder="Enter city" >
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">State/Province *</label>
                                <input type="text" name="delivery_state" id="delivery_state" class="w-full px-4 py-2 border rounded-md" placeholder="Enter state" >
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">ZIP/Postal Code *</label>
                                <input type="text" name="delivery_postal_code" id="delivery_postal_code" class="w-full px-4 py-2 border rounded-md" placeholder="Enter ZIP code" >
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Phone Number *</label>
                                <input type="tel" name="delivery_contact_phone" class="w-full px-4 py-2 border rounded-md" placeholder="Enter phone number" >
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Email Address</label>
                                <input type="email" name="delivery_contact_email" class="w-full px-4 py-2 border rounded-md" placeholder="Enter email address">
                            </div>
                        </div>
                        <input type="hidden" name="delivery_country" value="USA">
                        <input type="hidden" name="delivery_latitude" id="delivery_latitude">
                        <input type="hidden" name="delivery_longitude" id="delivery_longitude">
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Package Details -->
        <div id="step-3" class="step-content">
            <div class="space-y-6 min-h-[800px]">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                        <h3 class="text-xl sm:text-2xl font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 7v7"/><path d="M12 7v7"/><path d="M16 7v7"/></svg>
                            Package Details
                        </h3>
                        <div class="text-sm text-muted-foreground">Add items to your shipment</div>
                    </div>
                    <div class="p-4 md:p-6 pt-0 space-y-4">
                        <div id="items-container">
                            <!-- Item 1 (Default) -->
                            <div class="item-card rounded-lg border p-4 mb-4" data-item="1">
                                <h4 class="font-semibold mb-4">Item 1</h4>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Description</label>
                                            <input type="text" name="items[0][description]" class="w-full px-4 py-2 border rounded-md" placeholder="Enter item description" >
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Category</label>
                                            <select name="items[0][category]" class="w-full px-4 py-2 border rounded-md">
                                                <option value="general_merchandise">General Merchandise</option>
                                                <option value="electronics">Electronics</option>
                                                <option value="clothing">Clothing</option>
                                                <option value="food">Food</option>
                                                <option value="furniture">Furniture</option>
                                                <option value="documents">Documents</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Quantity</label>
                                            <input type="number" name="items[0][quantity]" value="1" min="1" class="w-full px-4 py-2 border rounded-md item-quantity" onchange="calculateTotals()">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Weight (lbs)</label>
                                            <input type="number" name="items[0][weight]" value="0" step="0.01" min="0" class="w-full px-4 py-2 border rounded-md item-weight" onchange="calculateTotals()">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Value (<span class="currency-symbol">{{ $pricingSettings['currency_symbol'] }}</span>)</label>
                                            <input type="number" name="items[0][value]" value="0" step="0.01" min="0" class="w-full px-4 py-2 border rounded-md item-value" onchange="calculateTotals()">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Dimensions (inches)</label>
                                        <div class="grid grid-cols-3 gap-4">
                                            <input type="number" name="items[0][length]" placeholder="Length" step="0.01" min="0" class="w-full px-4 py-2 border rounded-md">
                                            <input type="number" name="items[0][width]" placeholder="Width" step="0.01" min="0" class="w-full px-4 py-2 border rounded-md">
                                            <input type="number" name="items[0][height]" placeholder="Height" step="0.01" min="0" class="w-full px-4 py-2 border rounded-md">
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" name="items[0][is_hazardous]" value="1" class="h-4 w-4">
                                        <label class="text-sm font-medium">Hazardous Material</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="add-item-btn" class="w-full py-3 border-2 border-dashed rounded-lg hover:bg-accent flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Add Another Item
                        </button>

                        <!-- Totals Summary -->
                        <div class="rounded-lg bg-muted p-4 mt-6">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold" id="total-items">1</div>
                                    <div class="text-sm text-muted-foreground">Total Items</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold"><span id="total-weight">0.0</span> lbs</div>
                                    <div class="text-sm text-muted-foreground">Total Weight</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold"><span class="currency-symbol">{{ $pricingSettings['currency_symbol'] }}</span><span id="total-value">0.00</span></div>
                                    <div class="text-sm text-muted-foreground">Total Value</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Services -->
        <div id="step-4" class="step-content">
            <div class="space-y-6 w-full">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                        <h3 class="text-xl sm:text-2xl font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                            Special Services & Options
                        </h3>
                        <div class="text-sm text-muted-foreground">Configure additional services for your shipment</div>
                    </div>
                    <div class="p-4 md:p-6 pt-0 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                               <div class="flex items-center justify-between p-3 border rounded-lg">
	<div class="flex-1">
		<div class="font-medium">Insurance Coverage</div>
		<div class="text-sm text-muted-foreground">Protect your shipment ({{ $pricingSettings['insurance_rate'] }}% of shipment value)</div>
	</div>
	<div class="flex items-center gap-3">
		<span class="text-sm font-semibold text-muted-foreground" id="insurance-fee-display" style="display: none;">
			<span class="currency-symbol">{{ $pricingSettings['currency_symbol'] }}</span><span id="insurance-fee-amount">0.00</span>
		</span>
		<label class="toggle-switch">
			<input type="checkbox" name="insurance_required" onchange="calculatePricing()">
			<span class="toggle-slider"></span>
		</label>
	</div>
</div>

                                <div class="flex items-center justify-between p-3 border rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium">Signature Required</div>
                                        <div class="text-sm text-muted-foreground">Require signature upon delivery</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-semibold text-muted-foreground">
                                            <span class="currency-symbol">{{ $pricingSettings['currency_symbol'] }}</span>{{ number_format($pricingSettings['signature_fee'], 2) }}
                                        </span>
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="signature_required" onchange="calculatePricing()">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 border rounded-lg">
    <div class="flex-1">
        <div class="font-medium">Temperature Controlled</div>
        <div class="text-sm text-muted-foreground">Maintain temperature range</div>
    </div>
    <div class="flex items-center gap-3">
		<span class="text-sm font-semibold text-muted-foreground">
			<span class="currency-symbol">{{ $pricingSettings['currency_symbol'] }}</span>{{ number_format($pricingSettings['temperature_controlled_fee'], 2) }}
		</span>
		<label class="toggle-switch">
			<input type="checkbox" name="temperature_controlled" onchange="calculatePricing()">
			<span class="toggle-slider"></span>
		</label>
	</div>
</div>

                                <div class="flex items-center justify-between p-3 border rounded-lg">
    <div class="flex-1">
        <div class="font-medium">Fragile Handling</div>
        <div class="text-sm text-muted-foreground">Special care for delicate items</div>
    </div>
    <div class="flex items-center gap-3">
		<span class="text-sm font-semibold text-muted-foreground">
			<span class="currency-symbol">{{ $pricingSettings['currency_symbol'] }}</span>{{ number_format($pricingSettings['fragile_handling_fee'], 2) }}
		</span>
		<label class="toggle-switch">
			<input type="checkbox" name="fragile_handling" onchange="calculatePricing()">
			<span class="toggle-slider"></span>
		</label>
	</div>
</div>
                            </div>


        

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Preferred Carrier</label>
                                    <select name="carrier_id" class="w-full px-4 py-2 border rounded-md">
                                        <option value="">Select carrier (optional)</option>
                                        @if(isset($carriers))
                                            @foreach($carriers as $carrier)
                                                <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="fedex">FedEx</option>
                                            <option value="ups">UPS</option>
                                            <option value="usps">USPS</option>
                                            <option value="dhl">DHL</option>
                                        @endif
                                    </select>
                                </div>


                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Assigned Driver</label>
                                    <select name="assigned_driver_id" class="w-full px-4 py-2 border rounded-md">
                                        <option value="">Select driver </option>
                                           @foreach($drivers as $driver)
                        
                                                <option value="{{ $driver->id }}">{{ $driver->first_name }} {{ $driver->last_name }}</option>
                                                @if($driver->vehicle)
                                                    ({{ $driver->vehicle->make }} {{ $driver->vehicle->model }})
                                                @endif
                                          
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                

                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Service Level</label>
                                    <select name="service_level" class="w-full px-4 py-2 border rounded-md">
                                        <option value="">Select service</option>
                                        <option value="ground">Ground</option>
                                        <option value="air">Air</option>
                                        <option value="ocean">Ocean</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Payment Mode</label>
                                    <select name="payment_mode" class="w-full px-4 py-2 border rounded-md" onchange="toggleCOD(this)">
                                        <option value="prepaid">Prepaid</option>
                                        <option value="cod">Cash on Delivery</option>
                                        <option value="credit">Credit</option>
                                    </select>
                                </div>

                                <div id="cod-amount-div" class="space-y-2 hidden">
                                    <label class="text-sm font-medium">COD Amount ($)</label>
                                    <input type="number" name="cod_amount" step="0.01" min="0" class="w-full px-4 py-2 border rounded-md" placeholder="Enter COD amount">
                                </div>
                            </div>


                                                <!-- Shipping Zone Selection (Full Width) -->
<div class="col-span-1 md:col-span-2">
    <div class="rounded-lg border bg-muted/50 p-4">
        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-primary">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/>
                    <path d="M2 12h20"/>
                </svg>
                <label class="text-sm font-medium">Shipping Zone *</label>
            </div>
            <select name="shipping_zone" id="shipping_zone" class="w-full px-4 py-2 border rounded-md" required onchange="calculatePricing()">
                <option value="">Select shipping zone</option>
                <option value="local" data-price="{{ $pricingSettings['zone_local'] }}">
                    Local (Within Same City) - {{ $pricingSettings['currency_symbol'] }}{{ number_format($pricingSettings['zone_local'], 2) }}
                </option>
                <option value="regional" data-price="{{ $pricingSettings['zone_regional'] }}">
                    Regional (Within Same State) - {{ $pricingSettings['currency_symbol'] }}{{ number_format($pricingSettings['zone_regional'], 2) }}
                </option>
                <option value="national" data-price="{{ $pricingSettings['zone_national'] }}">
                    National (Different States) - {{ $pricingSettings['currency_symbol'] }}{{ number_format($pricingSettings['zone_national'], 2) }}
                </option>
                <option value="international" data-price="{{ $pricingSettings['zone_international'] }}">
                    International (Different Countries) - {{ $pricingSettings['currency_symbol'] }}{{ number_format($pricingSettings['zone_international'], 2) }}
                </option>
            </select>
            <p class="text-xs text-muted-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline h-3 w-3">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4"/>
                    <path d="M12 8h.01"/>
                </svg>
                Zone-based flat rate will be applied for this shipment. This determines the distance charge.
            </p>
        </div>
    </div>
</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium">Special Instructions</label>
                            <textarea name="special_instructions" rows="4" class="w-full px-4 py-2 border rounded-md" placeholder="Enter any special handling instructions, delivery notes, or requirements..."></textarea>
                        </div>

                        <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-blue-600 flex-shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <p class="text-sm text-blue-900">Additional services may affect shipping cost and delivery time. Review the cost estimate in the next step.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 5: Review & Cost Estimate -->
        <div id="step-5" class="step-content">
            <div class="space-y-6">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                        <h3 class="text-xl sm:text-2xl font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><rect width="14" height="17" x="6" y="3" rx="2"/><path d="M12 11h2"/><path d="M12 7h2"/><path d="M12 15h2"/></svg>
                            Review & Cost Estimate
                        </h3>
                        <div class="text-sm text-muted-foreground">Review your shipment details and get a cost estimate</div>
                    </div>
                    <div class="p-4 md:p-6 pt-0">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left Column - Shipment Summary -->
                            <div class="space-y-6">
                                <div>
                                    <h4 class="font-semibold text-lg mb-4">Shipment Summary</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Type:</span>
                                            <span class="font-medium" id="review-type">Standard</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Priority:</span>
                                            <span class="font-medium" id="review-priority">Standard</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Total Items:</span>
                                            <span class="font-medium" id="review-items">1</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Total Weight:</span>
                                            <span class="font-medium"><span id="review-weight">0.0</span> lbs</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted-foreground">Total Value:</span>
                                            <span class="font-medium">{{ $pricingSettings['currency_symbol'] }}<span id="review-value">0.00</span></span>
                                        </div>
                                        <div class="flex justify-between">
    <span class="text-muted-foreground">Assigned Driver:</span>
    <span class="font-medium" id="review-driver">Not assigned</span>
</div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-lg mb-4">Route</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <div class="text-sm font-medium mb-1">From:</div>
                                            <div class="text-sm text-muted-foreground" id="review-from">Origin Company</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium mb-1">To:</div>
                                            <div class="text-sm text-muted-foreground" id="review-to">Destination Company</div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-lg mb-4">Special Services</h4>
                                    <div class="text-sm text-muted-foreground" id="review-services">No special services selected</div>
                                </div>
                            </div>

                            <!-- Right Column - Cost Breakdown -->
                            <div>
                               <div class="rounded-lg border bg-muted p-6">
        <h4 class="font-semibold text-lg mb-4">Cost Breakdown</h4>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-muted-foreground">Base Shipping:</span>
                <span class="font-medium">
                    <span id="currency-base">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-base">{{ number_format($pricingSettings['standard_package']['standard'], 2) }}</span>
                </span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-muted-foreground">Weight Charge:</span>
                <span class="font-medium">
                    <span id="currency-weight">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-weight">0.00</span>
                </span>
            </div>
            <div class="text-xs text-muted-foreground ml-4" id="weight-breakdown">
                Rate: <span id="weight-rate-display">{{ $pricingSettings['currency_symbol'] }}{{ $pricingSettings['weight_rate_per_lb'] }}</span>/lb × <span id="total-weight-display">0</span> lbs
            </div>
            
            <div class="flex justify-between">
                <span class="text-muted-foreground">Distance Charge:</span>
                <span class="font-medium">
                    <span id="currency-distance">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-distance">0.00</span>
                </span>
            </div>
            <div class="text-xs text-muted-foreground ml-4" id="zone-breakdown">
                Zone: <span id="zone-display">Not selected</span>
            </div>
            
            <!-- Individual Service Fees -->
            <div id="service-signature" class="flex justify-between" style="display: none;">
                <span class="text-muted-foreground">Signature Required:</span>
                <span class="font-medium">
                    <span id="currency-signature">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-signature">{{ number_format($pricingSettings['signature_fee'], 2) }}</span>
                </span>
            </div>
            
            <div id="service-temperature" class="flex justify-between" style="display: none;">
                <span class="text-muted-foreground">Temperature Controlled:</span>
                <span class="font-medium">
                    <span id="currency-temperature">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-temperature">{{ number_format($pricingSettings['temperature_controlled_fee'], 2) }}</span>
                </span>
            </div>
            
            <div id="service-fragile" class="flex justify-between" style="display: none;">
                <span class="text-muted-foreground">Fragile Handling:</span>
                <span class="font-medium">
                    <span id="currency-fragile">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-fragile">{{ number_format($pricingSettings['fragile_handling_fee'], 2) }}</span>
                </span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-muted-foreground">Insurance Fee:</span>
                <span class="font-medium">
                    <span id="currency-insurance">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-insurance">0.00</span>
                </span>
            </div>
            <div class="text-xs text-muted-foreground ml-4" id="insurance-breakdown" style="display: none;">
                <span id="insurance-rate-display">{{ $pricingSettings['insurance_rate'] }}%</span> of <span id="currency-value">{{ $pricingSettings['currency_symbol'] }}</span><span id="insurance-value-display">0.00</span>
            </div>
            
            <div class="border-t pt-3">
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Subtotal:</span>
                    <span class="font-medium">
                        <span id="currency-subtotal">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-subtotal">0.00</span>
                    </span>
                </div>
            </div>
            
            <div class="flex justify-between">
                <span class="text-muted-foreground">Tax ({{ $pricingSettings['tax_percentage'] }}%):</span>
                <span class="font-medium">
                    <span id="currency-tax">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-tax">0.00</span>
                </span>
            </div>
            
            <div class="border-t pt-3 mt-3">
                <div class="flex justify-between text-lg font-bold">
                    <span>Total Estimated Cost:</span>
                    <span class="text-primary">
                        <span id="currency-total">{{ $pricingSettings['currency_symbol'] }}</span><span id="cost-total">{{ number_format($pricingSettings['standard_package']['standard'], 2) }}</span>
                    </span>
                </div>
            </div>
        </div>

        <button type="button" onclick="manualRecalculate()" class="w-full mt-4 py-2 border rounded-md hover:bg-accent flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
            Recalculate Estimate
        </button>
    </div>

    <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 flex gap-2 mt-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-blue-600 flex-shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
        <p class="text-sm text-blue-900">This is an estimate. Final cost may vary based on actual dimensions and carrier rates.</p>
    </div>
</div>

                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex flex-wrap gap-2 justify-between mt-8">
            <button type="button" id="prev-btn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-10 px-4 py-2" onclick="previousStep()" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </button>
            <div class="flex gap-2">
                <button type="button" id="save-draft-btn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-10 px-4 py-2" onclick="saveDraft()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                        <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path>
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path>
                        <path d="M7 3v4a1 1 0 0 0 1 1h7"></path>
                    </svg>
                    Save Draft
                </button>
                <button type="button" id="next-btn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2" onclick="nextStep()">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 ml-2"><path d="m9 18 6-6-6-6"/></svg>
                </button>
                <button type="submit" id="submit-btn" class="hidden inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><path d="m5 12 5 5L20 7"/></svg>
                    Create Shipment
                </button>
            </div>
        </div>

        <!-- Hidden fields for pricing calculations -->
        <input type="hidden" name="base_price" id="hidden-base-price" value="0">
        <input type="hidden" name="weight_charge" id="hidden-weight-charge" value="0">
        <input type="hidden" name="distance_charge" id="hidden-distance-charge" value="0">
        <input type="hidden" name="priority_charge" id="hidden-priority-charge" value="0">
        <input type="hidden" name="tax_amount" id="hidden-tax" value="0">
        <input type="hidden" name="insurance_fee" id="hidden-insurance-fee" value="0">
        <input type="hidden" name="additional_services_fee" id="hidden-services-fee" value="0">
        <input type="hidden" name="total_amount" id="hidden-total" value="0">
        <input type="hidden" name="total_weight" id="hidden-total-weight" value="0">
        <input type="hidden" name="total_value" id="hidden-total-value" value="0">
        <input type="hidden" name="number_of_items" id="hidden-item-count" value="1">

        <input type="hidden" name="signature_fee" id="hidden-signature-fee" value="0">
<input type="hidden" name="temperature_fee" id="hidden-temperature-fee" value="0">
<input type="hidden" name="fragile_fee" id="hidden-fragile-fee" value="0">
<input type="hidden" name="subtotal_amount" id="hidden-subtotal" value="0">
    </form>
</div>

<script>
// Declare all global variables at the top
let currentStep = 1;
let itemCount = 1;
let pricingSettings = {};
let isCalculatingTotals = false;

// Wait for document to be ready
document.addEventListener('DOMContentLoaded', function() {
    loadPricingSettings();
    bindEventListeners();
});

// Bind all event listeners
function bindEventListeners() {
    // Shipment type changes
    document.querySelectorAll('input[name="shipment_type"]').forEach(function(el) {
        el.addEventListener('change', function() {
            updateDeliveryPriorityPrices();
            calculatePricing();
        });
    });
    
    // Delivery priority changes
    document.querySelectorAll('input[name="delivery_priority"]').forEach(function(el) {
        el.addEventListener('change', function() {
            calculatePricing();
        });
    });
    
    // Shipping zone change
    const zoneSelect = document.getElementById('shipping_zone');
    if (zoneSelect) {
        zoneSelect.addEventListener('change', function() {
            console.log('Shipping zone changed:', this.value);
            calculatePricing();
        });
    }
    
    // Service toggles
    const insuranceCheckbox = document.querySelector('input[name="insurance_required"]');
    if (insuranceCheckbox) {
        insuranceCheckbox.addEventListener('change', calculatePricing);
    }
    
    const signatureCheckbox = document.querySelector('input[name="signature_required"]');
    if (signatureCheckbox) {
        signatureCheckbox.addEventListener('change', calculatePricing);
    }
    
    const temperatureCheckbox = document.querySelector('input[name="temperature_controlled"]');
    if (temperatureCheckbox) {
        temperatureCheckbox.addEventListener('change', calculatePricing);
    }
    
    const fragileCheckbox = document.querySelector('input[name="fragile_handling"]');
    if (fragileCheckbox) {
        fragileCheckbox.addEventListener('change', calculatePricing);
    }
    
    // Item field changes (using event delegation)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-quantity') || 
            e.target.classList.contains('item-weight') || 
            e.target.classList.contains('item-value')) {
            calculateTotals();
        }
    });
}

// Load pricing settings from API
function loadPricingSettings() {
    fetch('{{ route("settings.pricing") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            pricingSettings = response.pricing;
            console.log('Pricing settings loaded:', pricingSettings);
            
            // Update currency symbols in the UI
            updateCurrencySymbols();
            
            // Update delivery priority prices
            updateDeliveryPriorityPrices();
            
            // Initial pricing calculation
            calculatePricing();
        }
    })
    .catch(error => {
        console.error('Failed to load pricing settings:', error);
        // Use default settings if loading fails
        pricingSettings = getDefaultPricingSettings();
        updateCurrencySymbols();
        updateDeliveryPriorityPrices();
    });
}

// Update all currency symbols in the page
function updateCurrencySymbols() {
    const symbol = pricingSettings.currency_symbol || '$';
    
    // Update all elements that show currency
    document.querySelectorAll('.currency-symbol').forEach(function(el) {
        el.textContent = symbol;
    });
    
    // Update cost breakdown labels
    const currencyElements = ['currency-base', 'currency-weight', 'currency-distance', 
                              'currency-services', 'currency-insurance', 'currency-tax', 'currency-total'];
    currencyElements.forEach(function(id) {
        const el = document.getElementById(id);
        if (el) el.textContent = symbol;
    });
}

// Update delivery priority prices dynamically
function updateDeliveryPriorityPrices() {
    const shipmentType = document.querySelector('input[name="shipment_type"]:checked')?.value || 'Standard Package';
    const prices = getShipmentTypePrices(shipmentType);
    
    if (!prices) return;
    
    const symbol = pricingSettings.currency_symbol || '$';
    
    // Update each priority option
    const priorities = ['standard', 'express', 'overnight'];
    priorities.forEach(function(priority) {
        const input = document.querySelector(`input[name="delivery_priority"][value="${priority}"]`);
        if (input) {
            input.setAttribute('data-price', prices[priority]);
            const label = input.closest('label');
            if (label) {
                const priceSpan = label.querySelector('.px-2\\.5, [data-priority="' + priority + '"]');
                if (priceSpan) {
                    priceSpan.textContent = symbol + prices[priority].toFixed(2);
                }
            }
        }
    });
}

// Get prices for specific shipment type
function getShipmentTypePrices(shipmentType) {
    const typeMap = {
        'Standard Package': pricingSettings.standard_package,
        'Document Envelope': pricingSettings.document_envelope,
        'Freight/Pallet': pricingSettings.freight_pallet,
        'Bulk Cargo': pricingSettings.bulk_cargo,
    };
    
    return typeMap[shipmentType] || pricingSettings.standard_package;
}

// Default pricing settings (fallback)
function getDefaultPricingSettings() {
    return {
        currency_symbol: '$',
        standard_package: { standard: 15.99, express: 29.99, overnight: 49.99 },
        document_envelope: { standard: 9.99, express: 19.99, overnight: 34.99 },
        freight_pallet: { standard: 99.99, express: 149.99, overnight: 249.99 },
        bulk_cargo: { standard: 199.99, express: 299.99, overnight: 449.99 },
        weight_threshold: 10,
        weight_rate_per_lb: 0.50,
        distance_rate_per_mile: 0.75,
        zone_local: 5.00,
        zone_regional: 15.00,
        zone_national: 35.00,
        zone_international: 100.00,
        insurance_rate: 2,
        signature_fee: 5.00,
        temperature_controlled_fee: 25.00,
        fragile_handling_fee: 10.00,
        tax_percentage: 10,
    };
}

// Calculate Totals
function calculateTotals() {
    if (isCalculatingTotals) return;
    isCalculatingTotals = true;
    
    let totalItems = 0;
    let totalWeight = 0;
    let totalValue = 0;
    
    document.querySelectorAll('.item-card').forEach(card => {
        const qty = parseFloat(card.querySelector('.item-quantity').value) || 0;
        const weight = parseFloat(card.querySelector('.item-weight').value) || 0;
        const value = parseFloat(card.querySelector('.item-value').value) || 0;
        
        totalItems += qty;
        totalWeight += weight * qty;
        totalValue += value * qty;
    });
    
    console.log('=== Totals Calculated ===');
    console.log('Items:', totalItems, 'Weight:', totalWeight, 'Value:', totalValue);
    
    document.getElementById('total-items').textContent = totalItems;
    document.getElementById('total-weight').textContent = totalWeight.toFixed(2);
    document.getElementById('total-value').textContent = totalValue.toFixed(2);
    
    document.getElementById('hidden-item-count').value = totalItems;
    document.getElementById('hidden-total-weight').value = totalWeight.toFixed(2);
    document.getElementById('hidden-total-value').value = totalValue.toFixed(2);
    
    // Update insurance fee display when insurance is enabled
    updateInsuranceFeeDisplay(totalValue);
    
    // Trigger pricing calculation after a short delay
    setTimeout(function() {
        isCalculatingTotals = false;
        calculatePricing();
    }, 200);
}

// Update insurance fee display based on total shipment value
function updateInsuranceFeeDisplay(totalValue) {
    const insuranceCheckbox = document.querySelector('input[name="insurance_required"]');
    const insuranceFeeDisplay = document.getElementById('insurance-fee-display');
    const insuranceFeeAmount = document.getElementById('insurance-fee-amount');
    
    if (insuranceCheckbox && insuranceCheckbox.checked && totalValue > 0) {
        const insuranceRate = parseFloat('{{ $pricingSettings["insurance_rate"] }}');
        const insuranceFee = (totalValue * insuranceRate) / 100;
        
        insuranceFeeAmount.textContent = insuranceFee.toFixed(2);
        insuranceFeeDisplay.style.display = 'inline';
    } else {
        insuranceFeeDisplay.style.display = 'none';
    }
}

// Pricing Calculation using API
function calculatePricing() {
    // Gather all required data
    const shipmentType = document.querySelector('input[name="shipment_type"]:checked')?.value || 'Standard Package';
    const deliveryPriority = document.querySelector('input[name="delivery_priority"]:checked')?.value || 'standard';
    const totalWeight = parseFloat(document.getElementById('hidden-total-weight').value) || 0;
    const shippingZone = document.getElementById('shipping_zone')?.value;
    
    console.log('=== Calculate Pricing Called ===');
    console.log('Shipment Type:', shipmentType);
    console.log('Priority:', deliveryPriority);
    console.log('Total Weight:', totalWeight);
    console.log('Shipping Zone:', shippingZone);
    
    // Check if we have minimum required data
    if (!shippingZone) {
        console.warn('Shipping zone not selected - using fallback calculation');
        fallbackPricingCalculation();
        return;
    }
    
    // Insurance
    const insuranceRequired = document.querySelector('input[name="insurance_required"]')?.checked || false;
    const totalValue = parseFloat(document.getElementById('hidden-total-value').value) || 0;
    
    // Additional services
    const signatureRequired = document.querySelector('input[name="signature_required"]')?.checked || false;
    const temperatureControlled = document.querySelector('input[name="temperature_controlled"]')?.checked || false;
    const fragileHandling = document.querySelector('input[name="fragile_handling"]')?.checked || false;
    
    console.log('Insurance Required:', insuranceRequired, 'Total Value:', totalValue);
    console.log('Services - Signature:', signatureRequired, 'Temperature:', temperatureControlled, 'Fragile:', fragileHandling);
    
    // Call API to calculate pricing
    fetch('{{ route("admin.shipments.calculate-pricing") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            shipment_type: shipmentType,
            delivery_priority: deliveryPriority,
            total_weight: totalWeight,
            shipping_zone: shippingZone,
            total_value: totalValue,
            insurance_required: insuranceRequired,
            signature_required: signatureRequired,
            temperature_controlled: temperatureControlled,
            fragile_handling: fragileHandling,
        })
    })
    .then(response => response.json())
    .then(response => {
        console.log('API Response:', response);
        if (response.success) {
            updatePricingDisplay(response.pricing);
        } else {
            console.error('API returned error:', response);
            fallbackPricingCalculation();
        }
    })
    .catch(error => {
        console.error('Pricing calculation API failed:', error);
        // Use fallback calculation if API fails
        fallbackPricingCalculation();
    });
}

// Update pricing display with API response
function updatePricingDisplay(pricing) {
    const symbol = pricing.currency_symbol || pricingSettings.currency_symbol || '$';
    
    // Update cost breakdown
    document.getElementById('cost-base').textContent = pricing.base_price;
    document.getElementById('cost-weight').textContent = pricing.weight_charge;
    document.getElementById('cost-distance').textContent = pricing.distance_charge;
    
    // Update weight breakdown
    document.getElementById('weight-rate-display').textContent = symbol + parseFloat(pricing.weight_rate || pricingSettings.weight_rate_per_lb).toFixed(2);
    document.getElementById('total-weight-display').textContent = parseFloat(pricing.total_weight || 0).toFixed(2);
    
    // Update zone breakdown
    const zoneNames = {
        'local': 'Local (Same City)',
        'regional': 'Regional (Same State)',
        'national': 'National (Different States)',
        'international': 'International'
    };
    document.getElementById('zone-display').textContent = pricing.zone_name || zoneNames[pricing.shipping_zone] || 'Not selected';
    
    // Update individual service fees
    const signatureFee = parseFloat(pricing.signature_fee || 0);
    const temperatureFee = parseFloat(pricing.temperature_fee || 0);
    const fragileFee = parseFloat(pricing.fragile_fee || 0);
    
    const serviceSignature = document.getElementById('service-signature');
    const serviceTemperature = document.getElementById('service-temperature');
    const serviceFragile = document.getElementById('service-fragile');
    
    if (serviceSignature) serviceSignature.style.display = signatureFee > 0 ? 'flex' : 'none';
    if (serviceTemperature) serviceTemperature.style.display = temperatureFee > 0 ? 'flex' : 'none';
    if (serviceFragile) serviceFragile.style.display = fragileFee > 0 ? 'flex' : 'none';
    
    document.getElementById('cost-signature').textContent = signatureFee.toFixed(2);
    document.getElementById('cost-temperature').textContent = temperatureFee.toFixed(2);
    document.getElementById('cost-fragile').textContent = fragileFee.toFixed(2);
    
    // Update insurance
    document.getElementById('cost-insurance').textContent = pricing.insurance_fee;
    const insuranceBreakdown = document.getElementById('insurance-breakdown');
    if (parseFloat(pricing.insurance_fee) > 0 && parseFloat(pricing.total_value) > 0) {
        insuranceBreakdown.style.display = 'block';
        document.getElementById('insurance-rate-display').textContent = pricing.insurance_rate + '%';
        document.getElementById('insurance-value-display').textContent = parseFloat(pricing.total_value).toFixed(2);
    } else {
        insuranceBreakdown.style.display = 'none';
    }
    
    // Update subtotal, tax, and total
    document.getElementById('cost-subtotal').textContent = pricing.subtotal || '0.00';
    document.getElementById('cost-tax').textContent = pricing.tax_amount;
    document.getElementById('cost-total').textContent = pricing.total_amount;
    
    // Update hidden fields for form submission
    document.getElementById('hidden-base-price').value = pricing.base_price;
    document.getElementById('hidden-weight-charge').value = pricing.weight_charge;
    document.getElementById('hidden-distance-charge').value = pricing.distance_charge;
    document.getElementById('hidden-insurance-fee').value = pricing.insurance_fee;
    document.getElementById('hidden-tax').value = pricing.tax_amount;
    document.getElementById('hidden-total').value = pricing.total_amount;
    document.getElementById('hidden-priority-charge').value = pricing.base_price;
    
    // Update individual service fee hidden fields
    document.getElementById('hidden-signature-fee').value = signatureFee.toFixed(2);
    document.getElementById('hidden-temperature-fee').value = temperatureFee.toFixed(2);
    document.getElementById('hidden-fragile-fee').value = fragileFee.toFixed(2);
    document.getElementById('hidden-services-fee').value = pricing.additional_services_fee;
    document.getElementById('hidden-subtotal').value = pricing.subtotal || '0.00';
}

// Fallback pricing calculation (client-side)
function fallbackPricingCalculation() {
    if (!pricingSettings || !pricingSettings.currency_symbol) {
        console.error('Pricing settings not loaded');
        return;
    }
    
    console.log('=== Starting Fallback Calculation ===');
    
    // 1. Base price from delivery priority
    const priorityEl = document.querySelector('input[name="delivery_priority"]:checked');
    const basePrice = parseFloat(priorityEl?.dataset.price || 15.99);
    console.log('Base Price:', basePrice);
    
    // 2. Weight charge - Rate per lb × Total Weight
    const totalWeight = parseFloat(document.getElementById('hidden-total-weight').value) || 0;
    const weightRate = parseFloat(pricingSettings.weight_rate_per_lb) || 0.50;
    const weightCharge = totalWeight * weightRate;
    console.log('Weight:', totalWeight, 'Rate:', weightRate, 'Charge:', weightCharge);
    
    // 3. Distance charge - use selected zone value directly
    const shippingZoneSelect = document.getElementById('shipping_zone');
    const shippingZone = shippingZoneSelect?.value;
    let distanceCharge = 0;

    if (shippingZone) {
        switch(shippingZone) {
            case 'local':
                distanceCharge = parseFloat(pricingSettings.zone_local) || 5.00;
                break;
            case 'regional':
                distanceCharge = parseFloat(pricingSettings.zone_regional) || 15.00;
                break;
            case 'national':
                distanceCharge = parseFloat(pricingSettings.zone_national) || 35.00;
                break;
            case 'international':
                distanceCharge = parseFloat(pricingSettings.zone_international) || 100.00;
                break;
            default:
                distanceCharge = 0;
        }
    }
    console.log('Shipping Zone:', shippingZone, 'Distance Charge:', distanceCharge);
    
    // 4. Individual service fees
    let signatureFee = 0;
    let temperatureFee = 0;
    let fragileFee = 0;
    
    if (document.querySelector('input[name="signature_required"]')?.checked) {
        signatureFee = parseFloat(pricingSettings.signature_fee) || 5.00;
    }
    if (document.querySelector('input[name="temperature_controlled"]')?.checked) {
        temperatureFee = parseFloat(pricingSettings.temperature_controlled_fee) || 25.00;
    }
    if (document.querySelector('input[name="fragile_handling"]')?.checked) {
        fragileFee = parseFloat(pricingSettings.fragile_handling_fee) || 10.00;
    }
    
    const totalServicesFee = signatureFee + temperatureFee + fragileFee;
    console.log('Services - Signature:', signatureFee, 'Temp:', temperatureFee, 'Fragile:', fragileFee, 'Total:', totalServicesFee);
    
    // 5. Insurance fee - percentage of total value
    const insuranceRequired = document.querySelector('input[name="insurance_required"]')?.checked || false;
    const totalValue = parseFloat(document.getElementById('hidden-total-value').value) || 0;
    const insuranceRate = parseFloat(pricingSettings.insurance_rate) || 2;
    const insuranceFee = insuranceRequired && totalValue > 0 ? (totalValue * insuranceRate) / 100 : 0;
    console.log('Insurance - Required:', insuranceRequired, 'Value:', totalValue, 'Rate:', insuranceRate, 'Fee:', insuranceFee);
    
    // 6. Calculate subtotal (before tax)
    const subtotal = basePrice + weightCharge + distanceCharge + totalServicesFee + insuranceFee;
    console.log('Subtotal:', subtotal);
    
    // 7. Tax - percentage of subtotal
    const taxPercentage = parseFloat(pricingSettings.tax_percentage) || 10;
    const tax = (subtotal * taxPercentage) / 100;
    console.log('Tax:', tax, 'Percentage:', taxPercentage);
    
    // 8. Total
    const total = subtotal + tax;
    console.log('Total:', total);
    
    // Update display - Base, Weight, Distance
    document.getElementById('cost-base').textContent = basePrice.toFixed(2);
    document.getElementById('cost-weight').textContent = weightCharge.toFixed(2);
    document.getElementById('cost-distance').textContent = distanceCharge.toFixed(2);
    
    // Update weight breakdown
    document.getElementById('weight-rate-display').textContent = pricingSettings.currency_symbol + weightRate.toFixed(2);
    document.getElementById('total-weight-display').textContent = totalWeight.toFixed(2);
    
    // Update zone breakdown
    const zoneNames = {
        'local': 'Local (Same City)',
        'regional': 'Regional (Same State)',
        'national': 'National (Different States)',
        'international': 'International'
    };
    const zoneName = shippingZone ? zoneNames[shippingZone] : 'Not selected';
    document.getElementById('zone-display').textContent = zoneName;
    console.log('Zone Display:', zoneName);
    
    // Update individual service fees display
    const signatureDiv = document.getElementById('service-signature');
    const temperatureDiv = document.getElementById('service-temperature');
    const fragileDiv = document.getElementById('service-fragile');
    
    if (signatureDiv) signatureDiv.style.display = signatureFee > 0 ? 'flex' : 'none';
    if (temperatureDiv) temperatureDiv.style.display = temperatureFee > 0 ? 'flex' : 'none';
    if (fragileDiv) fragileDiv.style.display = fragileFee > 0 ? 'flex' : 'none';
    
    document.getElementById('cost-signature').textContent = signatureFee.toFixed(2);
    document.getElementById('cost-temperature').textContent = temperatureFee.toFixed(2);
    document.getElementById('cost-fragile').textContent = fragileFee.toFixed(2);
    
    // Update insurance display
    document.getElementById('cost-insurance').textContent = insuranceFee.toFixed(2);
    const insuranceBreakdown = document.getElementById('insurance-breakdown');
    if (insuranceRequired && totalValue > 0) {
        insuranceBreakdown.style.display = 'block';
        document.getElementById('insurance-rate-display').textContent = insuranceRate + '%';
        document.getElementById('insurance-value-display').textContent = totalValue.toFixed(2);
    } else {
        insuranceBreakdown.style.display = 'none';
    }
    
    // Update subtotal, tax, and total
    document.getElementById('cost-subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('cost-tax').textContent = tax.toFixed(2);
    document.getElementById('cost-total').textContent = total.toFixed(2);
    
    // Update hidden fields
    document.getElementById('hidden-base-price').value = basePrice.toFixed(2);
    document.getElementById('hidden-weight-charge').value = weightCharge.toFixed(2);
    document.getElementById('hidden-distance-charge').value = distanceCharge.toFixed(2);
    document.getElementById('hidden-priority-charge').value = basePrice.toFixed(2);
    document.getElementById('hidden-insurance-fee').value = insuranceFee.toFixed(2);
    document.getElementById('hidden-tax').value = tax.toFixed(2);
    document.getElementById('hidden-total').value = total.toFixed(2);
    
    // Update individual service fee hidden fields
    document.getElementById('hidden-signature-fee').value = signatureFee.toFixed(2);
    document.getElementById('hidden-temperature-fee').value = temperatureFee.toFixed(2);
    document.getElementById('hidden-fragile-fee').value = fragileFee.toFixed(2);
    document.getElementById('hidden-services-fee').value = totalServicesFee.toFixed(2);
    document.getElementById('hidden-subtotal').value = subtotal.toFixed(2);
    
    // Update insurance amount field if exists
    const insuranceAmountField = document.querySelector('input[name="insurance_amount"]');
    if (insuranceAmountField && insuranceRequired) {
        insuranceAmountField.value = totalValue.toFixed(2);
    }
    
    updateInsuranceFeeDisplay(totalValue);
    
    console.log('=== Calculation Complete ===');
}

// Step Navigation
function nextStep() {
    if (!validateStep(currentStep)) {
        return;
    }
    
    if (currentStep < 5) {
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        document.getElementById(`step-label-${currentStep}`).classList.remove('text-primary', 'font-medium');
        
        currentStep++;
        
        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.getElementById(`step-label-${currentStep}`).classList.add('text-primary', 'font-medium');
        
        updateProgress();
        updateButtons();
        
        if (currentStep === 5) {
            updateReviewSummary();
        }
        
        window.scrollTo(0, 0);
    }
}

function previousStep() {
    if (currentStep > 1) {
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        document.getElementById(`step-label-${currentStep}`).classList.remove('text-primary', 'font-medium');
        
        currentStep--;
        
        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.getElementById(`step-label-${currentStep}`).classList.add('text-primary', 'font-medium');
        
        updateProgress();
        updateButtons();
        window.scrollTo(0, 0);
    }
}

function updateProgress() {
    const percent = (currentStep / 5) * 100;
    document.getElementById('current-step').textContent = currentStep;
    document.getElementById('progress-percent').textContent = percent;
    document.getElementById('progress-bar').style.width = percent + '%';
}

function updateButtons() {
    document.getElementById('prev-btn').disabled = currentStep === 1;
    
    if (currentStep === 5) {
        document.getElementById('next-btn').classList.add('hidden');
        document.getElementById('submit-btn').classList.remove('hidden');
    } else {
        document.getElementById('next-btn').classList.remove('hidden');
        document.getElementById('submit-btn').classList.add('hidden');
    }
}

function validateStep(step) {
    const currentStepEl = document.getElementById(`step-${step}`);
    const required = currentStepEl.querySelectorAll('[required]');
    
    for (let field of required) {
        if (!field.value) {
            field.focus();
            alert('Please fill in all required fields');
            return false;
        }
    }
    return true;
}

// Add Item Functionality
document.getElementById('add-item-btn').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const firstItem = document.querySelector('.item-card');
    const newItem = firstItem.cloneNode(true);
    
    itemCount++;
    newItem.setAttribute('data-item', itemCount);
    newItem.querySelector('h4').textContent = `Item ${itemCount}`;
    
    // Update input names with correct index
    const inputs = newItem.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            // Replace any existing index with new one
            const newName = name.replace(/\[\d+\]/, `[${itemCount - 1}]`);
            input.setAttribute('name', newName);
            
            if (input.type === 'checkbox') {
                input.checked = false;
            } else if (input.type === 'number') {
                input.value = input.classList.contains('item-quantity') ? '1' : '0';
            } else {
                input.value = '';
            }
        }
    });
    
    // Add remove button
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'remove-item-btn absolute top-2 right-2 text-red-600 hover:text-red-800 bg-white rounded-full p-1 shadow';
    removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';
    removeBtn.onclick = function() {
        if (document.querySelectorAll('.item-card').length > 1) {
            newItem.remove();
            itemCount--;
            calculateTotals();
        } else {
            alert('You must have at least one item');
        }
    };
    newItem.appendChild(removeBtn);
    
    container.appendChild(newItem);
    calculateTotals();
});

// Toggle COD Amount
function toggleCOD(select) {
    const div = document.getElementById('cod-amount-div');
    if (select.value === 'cod') {
        div.classList.remove('hidden');
    } else {
        div.classList.add('hidden');
        document.querySelector('input[name="cod_amount"]').value = '';
    }
}

// Update Review Summary
function updateReviewSummary() {
    console.log('=== Updating Review Summary ===');
    
    // Force recalculation when entering step 5
    const shippingZone = document.getElementById('shipping_zone')?.value;
    console.log('Current shipping zone:', shippingZone);
    
    if (!shippingZone) {
        console.warn('WARNING: No shipping zone selected!');
    }
    
    // Update driver display
    const driverSelect = document.querySelector('select[name="assigned_driver_id"]');
    const driverText = driverSelect?.options[driverSelect.selectedIndex]?.text || 'Not assigned';
    document.getElementById('review-driver').textContent = driverText;

    // Type and Priority
    const type = document.querySelector('input[name="shipment_type"]:checked').value;
    const priority = document.querySelector('input[name="delivery_priority"]:checked').value;
    document.getElementById('review-type').textContent = type;
    document.getElementById('review-priority').textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
    
    // Totals
    document.getElementById('review-items').textContent = document.getElementById('total-items').textContent;
    document.getElementById('review-weight').textContent = document.getElementById('total-weight').textContent;
    document.getElementById('review-value').textContent = document.getElementById('total-value').textContent;
    
    // Addresses
    const pickupCompany = document.querySelector('input[name="pickup_company_name"]').value || 'Origin Company';
    const deliveryCompany = document.querySelector('input[name="delivery_company_name"]').value || 'Destination Company';
    document.getElementById('review-from').textContent = pickupCompany;
    document.getElementById('review-to').textContent = deliveryCompany;
    
    // Services
    const services = [];
    if (document.querySelector('input[name="insurance_required"]')?.checked) services.push('Insurance Coverage');
    if (document.querySelector('input[name="signature_required"]')?.checked) services.push('Signature Required');
    if (document.querySelector('input[name="temperature_controlled"]')?.checked) services.push('Temperature Controlled');
    if (document.querySelector('input[name="fragile_handling"]')?.checked) services.push('Fragile Handling');
    document.getElementById('review-services').textContent = services.length > 0 ? services.join(', ') : 'No special services selected';
    
    calculatePricing();
}

// Manual Recalculate Handler
function manualRecalculate() {
    console.log('=== Manual Recalculate Triggered ===');
    calculatePricing();
}

// Save Draft
function saveDraft() {
    const form = document.getElementById('shipment-form');
    const formData = new FormData(form);
    formData.append('save_as_draft', 'true');
    
    // Show loading indicator
    const btn = document.getElementById('save-draft-btn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
    
    fetch('{{ route("admin.shipments.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Draft saved successfully! Tracking: ' + data.tracking_number);
        } else {
            alert('Failed to save draft: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save draft. Check console for details.');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>

@endsection