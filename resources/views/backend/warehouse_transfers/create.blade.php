@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .step-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }
    .step-item:not(:last-child):after {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: #e5e7eb;
        top: 20px;
        left: 50%;
        z-index: -1;
    }
    .step-item.active:not(:last-child):after,
    .step-item.completed:not(:last-child):after {
        background-color: #3b82f6;
    }
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e5e7eb;
        border: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #6b7280;
        position: relative;
        z-index: 1;
    }
    .step-item.active .step-circle {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }
    .step-item.completed .step-circle {
        background-color: #10b981;
        border-color: #10b981;
        color: white;
    }
    .shipment-card {
        transition: all 0.2s;
    }
    .shipment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .shipment-card.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    .driver-card.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
</style>

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Initiate Warehouse Transfer</h1>
            <p class="text-gray-600">Create a new transfer between warehouses</p>
        </div>
        <a href="{{ route('admin.warehouse.transfers.index') }}" class="inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 mr-2">
                <path d="m12 19-7-7 7-7"></path>
                <path d="M19 12H5"></path>
            </svg>
            Back to Transfers
        </a>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white rounded-lg border shadow-sm p-6">
        <div class="flex items-center justify-between mb-8">
            <div class="step-item active" data-step="1">
                <div class="step-circle">1</div>
                <div class="text-sm font-medium mt-2">Transfer Details</div>
            </div>
            <div class="step-item" data-step="2">
                <div class="step-circle">2</div>
                <div class="text-sm font-medium mt-2">Select Shipments</div>
            </div>
            <div class="step-item" data-step="3">
                <div class="step-circle">3</div>
                <div class="text-sm font-medium mt-2">Assign Driver</div>
            </div>
            <div class="step-item" data-step="4">
                <div class="step-circle">4</div>
                <div class="text-sm font-medium mt-2">Review & Submit</div>
            </div>
        </div>

        <form id="createTransferForm" method="POST" action="{{ route('admin.warehouse.transfers.store') }}">
            @csrf

            <!-- Step 1: Transfer Details -->
            <div class="step-content" id="step1">
                <h3 class="text-xl font-semibold mb-6">Transfer Details</h3>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">From Warehouse *</label>
                            <select name="from_warehouse_id" id="from_warehouse_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select source warehouse</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" data-name="{{ $warehouse->name }}" data-code="{{ $warehouse->warehouse_code }}">
                                    {{ $warehouse->name }} ({{ $warehouse->warehouse_code }})
                                </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Select the warehouse where shipments are currently located</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">To Warehouse *</label>
                            <select name="to_warehouse_id" id="to_warehouse_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select destination warehouse</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" data-name="{{ $warehouse->name }}" data-code="{{ $warehouse->warehouse_code }}">
                                    {{ $warehouse->name }} ({{ $warehouse->warehouse_code }})
                                </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Select the destination warehouse</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Transfer Type *</label>
                            <select name="transfer_type" id="transfer_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach($transferTypes as $type)
                                <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Select the type of transfer</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Priority</label>
                            <select name="priority" id="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="normal">Normal</option>
                                <option value="high">High Priority</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Transfer Notes / Special Instructions</label>
                        <textarea name="transfer_notes" id="transfer_notes" rows="4" placeholder="Add any special handling instructions, notes, or requirements for this transfer..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" id="nextToStep2" class="inline-flex items-center justify-center bg-blue-600 text-white hover:bg-blue-700 h-10 rounded-md px-6">
                        Next: Select Shipments
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ml-2">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Step 2: Select Shipments -->
            <div class="step-content hidden" id="step2">
                <h3 class="text-xl font-semibold mb-6">Select Shipments for Transfer</h3>

                <div class="space-y-6">
                    <!-- Search and Filter -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-2.5 h-4 w-4 text-gray-500">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            <input type="text" id="shipmentSearch" placeholder="Search by tracking number..." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <button type="button" id="selectAllShipments" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                            Select All
                        </button>
                        <button type="button" id="deselectAllShipments" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                            Deselect All
                        </button>
                    </div>

                    <!-- Selected Count -->
                    <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                                <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                                <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                            </svg>
                            <span class="font-semibold text-blue-900">
                                <span id="selectedShipmentsCount">0</span> shipment(s) selected
                            </span>
                        </div>
                        <span class="text-sm text-blue-700">Select at least one shipment to proceed</span>
                    </div>

                    <!-- Loading State -->
                    <div id="shipmentsLoading" class="text-center py-12">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        <p class="mt-4 text-gray-600">Loading available shipments...</p>
                    </div>

                    <!-- No Shipments Message -->
                    <div id="noShipmentsMessage" class="hidden text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto text-gray-400 mb-4">
                            <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                            <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Shipments Available</h3>
                        <p class="text-gray-600">No shipments available for transfer from the selected warehouse.</p>
                    </div>

                    <!-- Shipments Grid -->
                    <div id="shipmentsGrid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[500px] overflow-y-auto p-2">
                        <!-- Shipment cards will be loaded here -->
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" id="backToStep1" class="inline-flex items-center justify-center border border-gray-300 bg-white hover:bg-gray-50 h-10 rounded-md px-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                            <path d="m15 18-6-6 6-6"></path>
                        </svg>
                        Back
                    </button>
                    <button type="button" id="nextToStep3" class="inline-flex items-center justify-center bg-blue-600 text-white hover:bg-blue-700 h-10 rounded-md px-6">
                        Next: Assign Driver
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ml-2">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Step 3: Assign Driver -->
            <div class="step-content hidden" id="step3">
                <h3 class="text-xl font-semibold mb-6">Assign Driver (Optional)</h3>

                <div class="space-y-6">
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600 mt-0.5">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-yellow-800">Driver Assignment is Optional</p>
                                <p class="text-sm text-yellow-700 mt-1">You can assign a driver now or do it later. The transfer will remain in "Pending" status until a driver is assigned.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="assignDriverNow" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                            <label for="assignDriverNow" class="text-sm font-medium">Assign driver now</label>
                        </div>

                        <div id="driverSelectionSection" class="hidden space-y-4">
                            <!-- Drivers Grid -->
                            <div id="driversGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[400px] overflow-y-auto p-2">
                                @foreach($drivers as $driver)
                                <label class="driver-card border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition {{ $driver->is_available ? '' : 'opacity-60' }}" data-driver-id="{{ $driver->id }}">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold">{{ $driver->first_name }} {{ $driver->last_name }}</p>
                                                <p class="text-xs text-gray-500">ID: {{ $driver->id }}</p>
                                            </div>
                                        </div>
                                        <input type="radio" name="driver_id" value="{{ $driver->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500" {{ $driver->is_available ? '' : 'disabled' }}/>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $driver->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $driver->is_available ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" id="backToStep2" class="inline-flex items-center justify-center border border-gray-300 bg-white hover:bg-gray-50 h-10 rounded-md px-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                            <path d="m15 18-6-6 6-6"></path>
                        </svg>
                        Back
                    </button>
                    <button type="button" id="nextToStep4" class="inline-flex items-center justify-center bg-blue-600 text-white hover:bg-blue-700 h-10 rounded-md px-6">
                        Next: Review & Submit
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ml-2">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Step 4: Review & Submit -->
            <div class="step-content hidden" id="step4">
                <h3 class="text-xl font-semibold mb-6">Review Transfer Details</h3>

                <div class="space-y-6">
                    <!-- Transfer Summary -->
                    <div class="border rounded-lg p-6">
                        <h4 class="font-semibold text-lg mb-4">Transfer Summary</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">From Warehouse</p>
                                <p class="font-semibold" id="reviewFromWarehouse">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">To Warehouse</p>
                                <p class="font-semibold" id="reviewToWarehouse">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Transfer Type</p>
                                <p class="font-semibold" id="reviewTransferType">-</p>
                            </div>
                        </div>
                        <div class="mt-4" id="reviewNotesSection">
                            <p class="text-sm text-gray-600 mb-1">Transfer Notes</p>
                            <p class="text-sm bg-gray-50 p-3 rounded" id="reviewNotes">-</p>
                        </div>
                    </div>

                    <!-- Selected Shipments -->
                    <div class="border rounded-lg p-6">
                        <h4 class="font-semibold text-lg mb-4">Selected Shipments (<span id="reviewShipmentCount">0</span>)</h4>
                        <div id="reviewShipmentsList" class="space-y-2 max-h-[300px] overflow-y-auto">
                            <!-- Shipments will be listed here -->
                        </div>
                    </div>

                    <!-- Assigned Driver -->
                    <div class="border rounded-lg p-6">
                        <h4 class="font-semibold text-lg mb-4">Assigned Driver</h4>
                        <div id="reviewDriverInfo">
                            <p class="text-gray-600">No driver assigned</p>
                        </div>
                    </div>

                    <!-- Confirmation Checkbox -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="confirmTransfer" required class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-1"/>
                            <label for="confirmTransfer" class="text-sm text-blue-900">
                                <span class="font-semibold">I confirm that all details are correct</span><br/>
                                By submitting this transfer, the selected shipments will be marked for transfer and the process will be initiated.
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" id="backToStep3" class="inline-flex items-center justify-center border border-gray-300 bg-white hover:bg-gray-50 h-10 rounded-md px-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                            <path d="m15 18-6-6 6-6"></path>
                        </svg>
                        Back
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center bg-green-600 text-white hover:bg-green-700 h-10 rounded-md px-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Submit Transfer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let currentStep = 1;
let selectedShipments = [];
let loadedShipments = [];

// Step Navigation
function goToStep(stepNumber) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(step => {
        step.classList.add('hidden');
    });
    
    // Show target step
    document.getElementById('step' + stepNumber).classList.remove('hidden');
    
    // Update step indicators
    document.querySelectorAll('.step-item').forEach((item, index) => {
        const step = index + 1;
        item.classList.remove('active', 'completed');
        if (step < stepNumber) {
            item.classList.add('completed');
        } else if (step === stepNumber) {
            item.classList.add('active');
        }
    });
    
    currentStep = stepNumber;
    
    // Load data when entering step 2
    if (stepNumber === 2) {
        loadShipments();
    }
    
    // Update review when entering step 4
    if (stepNumber === 4) {
        updateReview();
    }
}

// Step 1 -> 2
document.getElementById('nextToStep2').addEventListener('click', () => {
    const fromWarehouse = document.getElementById('from_warehouse_id').value;
    const toWarehouse = document.getElementById('to_warehouse_id').value;
    
    if (!fromWarehouse || !toWarehouse) {
        alert('Please select both warehouses');
        return;
    }
    
    if (fromWarehouse === toWarehouse) {
        alert('Source and destination warehouses must be different');
        return;
    }
    
    goToStep(2);
});

// Load Shipments
function loadShipments() {
    const fromWarehouseId = document.getElementById('from_warehouse_id').value;
    
    document.getElementById('shipmentsLoading').classList.remove('hidden');
    document.getElementById('noShipmentsMessage').classList.add('hidden');
    document.getElementById('shipmentsGrid').classList.add('hidden');
    
    fetch(`/admin/warehouse-transfers/warehouse/${fromWarehouseId}/shipments`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('shipmentsLoading').classList.add('hidden');
        
        if (data.success && data.shipments.length > 0) {
            loadedShipments = data.shipments;
            displayShipments(data.shipments);
            document.getElementById('shipmentsGrid').classList.remove('hidden');
        } else {
            document.getElementById('noShipmentsMessage').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('shipmentsLoading').classList.add('hidden');
        alert('Error loading shipments');
    });
}

function displayShipments(shipments) {
    const grid = document.getElementById('shipmentsGrid');
    grid.innerHTML = '';
    
    shipments.forEach(shipment => {
        const card = document.createElement('div');
        card.className = 'shipment-card border rounded-lg p-4 cursor-pointer';
        card.dataset.shipmentId = shipment.id;
        card.innerHTML = `
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <p class="font-semibold text-blue-600">${shipment.tracking_number}</p>
                    <p class="text-xs text-gray-500 mt-1">ID: ${shipment.id}</p>
                </div>
                <input type="checkbox" name="shipment_ids[]" value="${shipment.id}" class="shipment-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
            </div>
            <div class="text-sm space-y-1">
                <p class="text-gray-600"><span class="font-medium">Status:</span> ${shipment.status || 'N/A'}</p>
            </div>
        `;
        
        card.addEventListener('click', (e) => {
            if (e.target.type !== 'checkbox') {
                const checkbox = card.querySelector('.shipment-checkbox');
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
        
        grid.appendChild(card);
    });
    
    // Add change listeners
    document.querySelectorAll('.shipment-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const card = this.closest('.shipment-card');
            if (this.checked) {
                card.classList.add('selected');
                if (!selectedShipments.includes(this.value)) {
                    selectedShipments.push(this.value);
                }
            } else {
                card.classList.remove('selected');
                selectedShipments = selectedShipments.filter(id => id !== this.value);
            }
            updateSelectedCount();
        });
    });
}

function updateSelectedCount() {
    document.getElementById('selectedShipmentsCount').textContent = selectedShipments.length;
}

// Search Shipments
document.getElementById('shipmentSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.shipment-card').forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Select/Deselect All
document.getElementById('selectAllShipments').addEventListener('click', () => {
    document.querySelectorAll('.shipment-checkbox:not([style*="display: none"])').forEach(cb => {
        if (!cb.checked) {
            cb.checked = true;
            cb.dispatchEvent(new Event('change'));
        }
    });
});

document.getElementById('deselectAllShipments').addEventListener('click', () => {
    document.querySelectorAll('.shipment-checkbox').forEach(cb => {
        if (cb.checked) {
            cb.checked = false;
            cb.dispatchEvent(new Event('change'));
        }
    });
});

// Step 2 Navigation
document.getElementById('backToStep1').addEventListener('click', () => goToStep(1));
document.getElementById('nextToStep3').addEventListener('click', () => {
    if (selectedShipments.length === 0) {
        alert('Please select at least one shipment');
        return;
    }
    goToStep(3);
});

// Step 3: Driver Selection
document.getElementById('assignDriverNow').addEventListener('change', function() {
    const section = document.getElementById('driverSelectionSection');
    if (this.checked) {
        section.classList.remove('hidden');
    } else {
        section.classList.add('hidden');
        // Uncheck all drivers
        document.querySelectorAll('input[name="driver_id"]').forEach(radio => {
            radio.checked = false;
        });
    }
});

// Driver Card Selection
document.querySelectorAll('.driver-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.driver-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
    });
});

// Step 3 Navigation
document.getElementById('backToStep2').addEventListener('click', () => goToStep(2));
document.getElementById('nextToStep4').addEventListener('click', () => goToStep(4));

// Step 4: Review
function updateReview() {
    // Warehouse Info
    const fromWarehouse = document.getElementById('from_warehouse_id');
    const toWarehouse = document.getElementById('to_warehouse_id');
    const fromOption = fromWarehouse.options[fromWarehouse.selectedIndex];
    const toOption = toWarehouse.options[toWarehouse.selectedIndex];
    
    document.getElementById('reviewFromWarehouse').textContent = fromOption.text;
    document.getElementById('reviewToWarehouse').textContent = toOption.text;
    
    // Transfer Type
    const transferType = document.getElementById('transfer_type');
    document.getElementById('reviewTransferType').textContent = transferType.options[transferType.selectedIndex].text;
    
    // Notes
    const notes = document.getElementById('transfer_notes').value;
    if (notes) {
        document.getElementById('reviewNotes').textContent = notes;
        document.getElementById('reviewNotesSection').classList.remove('hidden');
    } else {
        document.getElementById('reviewNotesSection').classList.add('hidden');
    }
    
    // Selected Shipments
    document.getElementById('reviewShipmentCount').textContent = selectedShipments.length;
    const shipmentsList = document.getElementById('reviewShipmentsList');
    shipmentsList.innerHTML = '';
    
    selectedShipments.forEach(id => {
        const shipment = loadedShipments.find(s => s.id == id);
        if (shipment) {
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between p-3 bg-gray-50 rounded';
            item.innerHTML = `
                <div>
                    <p class="font-medium text-blue-600">${shipment.tracking_number}</p>
                    <p class="text-xs text-gray-500">ID: ${shipment.id}</p>
                </div>
                <span class="text-xs text-gray-600">${shipment.status || 'N/A'}</span>
            `;
            shipmentsList.appendChild(item);
        }
    });
    
    // Driver Info
    const selectedDriver = document.querySelector('input[name="driver_id"]:checked');
    const driverInfo = document.getElementById('reviewDriverInfo');
    if (selectedDriver) {
        const driverCard = selectedDriver.closest('.driver-card');
        const driverName = driverCard.querySelector('p.font-semibold').textContent;
        driverInfo.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold">${driverName}</p>
                    <p class="text-xs text-gray-500">Driver ID: ${selectedDriver.value}</p>
                </div>
            </div>
        `;
    } else {
        driverInfo.innerHTML = '<p class="text-gray-600">No driver assigned</p>';
    }
}

// Step 4 Navigation
document.getElementById('backToStep3').addEventListener('click', () => goToStep(3));

// Form Submission
document.getElementById('createTransferForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!document.getElementById('confirmTransfer').checked) {
        alert('Please confirm the transfer details');
        return;
    }
    
    const formData = new FormData(this);
    
    // Add selected shipments if not already in form
    selectedShipments.forEach(id => {
        formData.append('shipment_ids[]', id);
    });
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("admin.warehouse.transfers.index") }}';
        } else {
            alert('Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><polyline points="20 6 9 17 4 12"></polyline></svg> Submit Transfer';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the transfer');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><polyline points="20 6 9 17 4 12"></polyline></svg> Submit Transfer';
    });
});
</script>

@endsection