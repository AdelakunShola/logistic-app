@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="p-6 space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <strong class="font-bold">Please fix the following errors:</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Edit Transfer #{{ $transfer->transfer_code }}</h1>
            <p class="text-gray-600">Update transfer details and assignment</p>
        </div>
        <a href="{{ route('admin.warehouse.transfers.index') }}" class="inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 mr-2">
                <path d="m12 19-7-7 7-7"></path>
                <path d="M19 12H5"></path>
            </svg>
            Back to Transfers
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <form id="editTransferForm" method="POST" action="{{ route('admin.warehouse.transfers.update', $transfer->id) }}">
                @csrf
                @method('PUT')

                <!-- Transfer Details -->
                <div class="bg-white rounded-lg border shadow-sm p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Transfer Details</h3>
                        
                        <div class="space-y-4">
                            <!-- From Warehouse (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Warehouse</label>
                                <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-700">
                                    {{ $transfer->fromWarehouse->name ?? 'N/A' }} ({{ $transfer->fromWarehouse->warehouse_code ?? 'N/A' }})
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Source warehouse cannot be changed</p>
                            </div>

                            <!-- To Warehouse -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">To Warehouse *</label>
                                <select name="to_warehouse_id" id="to_warehouse_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select destination warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                        @if($warehouse->id != $transfer->from_warehouse_id)
                                        <option value="{{ $warehouse->id }}" {{ $transfer->to_warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }} ({{ $warehouse->warehouse_code }})
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('to_warehouse_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Transfer Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Transfer Type *</label>
                                <select name="transfer_type" id="transfer_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach($transferTypes as $type)
                                    <option value="{{ $type }}" {{ $transfer->transfer_type == $type ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $type)) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('transfer_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Shipment Info (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Shipment</label>
                                <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md">
                                    <p class="font-medium text-blue-600">{{ $transfer->shipment->tracking_number ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Shipment cannot be changed after creation</p>
                                </div>
                            </div>

                            <!-- Transfer Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Transfer Notes</label>
                                <textarea name="transfer_notes" id="transfer_notes" rows="4" placeholder="Add any special handling instructions or notes..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('transfer_notes', $transfer->transfer_notes) }}</textarea>
                                @error('transfer_notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Driver Assignment -->
                <div class="bg-white rounded-lg border shadow-sm p-6 space-y-6 mt-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Driver Assignment</h3>
                        
                        <div class="space-y-4">
                            <!-- Current Driver Display -->
                            @if($transfer->driver)
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-blue-900">Currently Assigned: {{ $transfer->driver->first_name }} {{ $transfer->driver->last_name }}</p>
                                        <p class="text-xs text-blue-700">Change driver below or leave as is</p>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800"><span class="font-semibold">No driver assigned.</span> Select a driver below to assign one.</p>
                            </div>
                            @endif

                            <!-- Driver Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Driver</label>
                                <select name="driver_id" id="driver_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">No driver / Unassign</option>
                                    @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" 
                                            {{ $transfer->driver_id == $driver->id ? 'selected' : '' }}
                                            {{ $driver->is_available ? '' : 'disabled' }}>
                                        {{ $driver->first_name }} {{ $driver->last_name }}
                                        {{ $driver->is_available ? '' : '(Unavailable)' }}
                                        {{ $driver->vehicle_number ? '- ' . $driver->vehicle_number : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('admin.warehouse.transfers.index') }}" class="inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-10 rounded-md px-6">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center bg-blue-600 text-white hover:bg-blue-700 h-10 rounded-md px-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Transfer Status -->
            <div class="bg-white rounded-lg border shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">Transfer Status</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Current Status:</span>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $transfer->status_badge ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucwords(str_replace('_', ' ', $transfer->status)) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Transfer ID:</span>
                        <span class="text-sm font-medium">{{ $transfer->transfer_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Initiated:</span>
                        <span class="text-sm font-medium">{{ $transfer->initiated_at ? $transfer->initiated_at->format('M d, Y H:i') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Initiated By:</span>
                        <span class="text-sm font-medium">{{ $transfer->initiatedBy->first_name ?? 'N/A' }} {{ $transfer->initiatedBy->last_name ?? '' }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipment Info -->
            <div class="bg-white rounded-lg border shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">Shipment Details</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tracking Number</p>
                        <p class="font-medium text-blue-600">{{ $transfer->shipment->tracking_number ?? 'N/A' }}</p>
                    </div>
                    @if($transfer->shipment)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Current Status</p>
                        <p class="text-sm font-medium">{{ ucwords(str_replace('_', ' ', $transfer->shipment->status ?? 'N/A')) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Edit Restrictions -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600 mt-0.5">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 mb-1">Edit Restrictions</p>
                        <ul class="text-xs text-yellow-700 space-y-1">
                            <li>• Source warehouse cannot be changed</li>
                            <li>• Shipment cannot be modified</li>
                            <li>• Changes only allowed for pending transfers</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.warehouse.transfers.show', $transfer->id) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-md transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        View Full Details
                    </a>
                    <a href="{{ route('admin.warehouse.transfers.print', $transfer->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-md transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Print Document
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Form submission with AJAX
document.getElementById('editTransferForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnHtml = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            successDiv.innerHTML = '<span class="block sm:inline">Transfer updated successfully!</span>';
            document.body.appendChild(successDiv);
            
            // Redirect after short delay
            setTimeout(() => {
                window.location.href = '{{ route("admin.warehouse.transfers.index") }}';
            }, 1500);
        } else {
            alert('Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the transfer');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHtml;
    });
});

// Validate warehouses are different
document.getElementById('to_warehouse_id').addEventListener('change', function() {
    const fromWarehouseId = '{{ $transfer->from_warehouse_id }}';
    if (this.value === fromWarehouseId) {
        alert('Destination warehouse must be different from source warehouse');
        this.value = '';
    }
});
</script>

@endsection