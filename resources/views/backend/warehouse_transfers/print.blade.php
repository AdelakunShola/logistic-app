<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Document - {{ $transfer->transfer_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-8">
        <!-- Print Button -->
        <div class="no-print mb-4 flex justify-end gap-2">
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Print Document
            </button>
            <button onclick="window.close()" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                Close
            </button>
        </div>

        <!-- Document -->
        <div class="bg-white p-8 shadow-lg">
            <!-- Header -->
            <div class="border-b-2 border-gray-300 pb-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">WAREHOUSE TRANSFER DOCUMENT</h1>
                        <p class="text-gray-600 mt-2">Transfer ID: <span class="font-semibold">{{ $transfer->transfer_code }}</span></p>
                        <p class="text-gray-600">Date: <span class="font-semibold">{{ $transfer->initiated_at->format('M d, Y H:i') }}</span></p>
                    </div>
                    <div class="text-right">
                        <!-- Add your company logo here -->
                        <div class="text-2xl font-bold text-blue-600">YOUR COMPANY</div>
                        <div class="text-sm text-gray-600 mt-1">Logistics Management System</div>
                    </div>
                </div>
            </div>

            <!-- Transfer Status -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-sm text-gray-600">Transfer Status:</span>
                        <span class="ml-2 px-3 py-1 rounded-full text-sm font-semibold {{ $transfer->status_badge }}">
                            {{ $transfer->status_label }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Transfer Type:</span>
                        <span class="ml-2 font-semibold">{{ $transfer->transfer_type_label }}</span>
                    </div>
                </div>
            </div>

            <!-- Route Information -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="border-2 border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        FROM WAREHOUSE
                    </h3>
                    <div class="space-y-2">
                        <p class="font-bold text-xl">{{ $transfer->fromWarehouse->name }}</p>
                        <p class="text-sm text-gray-600">{{ $transfer->fromWarehouse->warehouse_code }}</p>
                        <p class="text-sm text-gray-600">{{ $transfer->fromWarehouse->address }}</p>
                        <p class="text-sm text-gray-600">{{ $transfer->fromWarehouse->city }}, {{ $transfer->fromWarehouse->state }}</p>
                        @if($transfer->fromWarehouse->phone)
                        <p class="text-sm text-gray-600">Tel: {{ $transfer->fromWarehouse->phone }}</p>
                        @endif
                    </div>
                </div>

                <div class="border-2 border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        TO WAREHOUSE
                    </h3>
                    <div class="space-y-2">
                        <p class="font-bold text-xl">{{ $transfer->toWarehouse->name }}</p>
                        <p class="text-sm text-gray-600">{{ $transfer->toWarehouse->warehouse_code }}</p>
                        <p class="text-sm text-gray-600">{{ $transfer->toWarehouse->address }}</p>
                        <p class="text-sm text-gray-600">{{ $transfer->toWarehouse->city }}, {{ $transfer->toWarehouse->state }}</p>
                        @if($transfer->toWarehouse->phone)
                        <p class="text-sm text-gray-600">Tel: {{ $transfer->toWarehouse->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Shipment Details -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">SHIPMENT DETAILS</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tracking Number</p>
                            <p class="font-bold text-lg">{{ $transfer->shipment->tracking_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Package Type</p>
                            <p class="font-semibold">{{ $transfer->shipment->package_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Weight</p>
                            <p class="font-semibold">{{ $transfer->shipment->weight ?? 'N/A' }} kg</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Dimensions</p>
                            <p class="font-semibold">
                                {{ $transfer->shipment->length ?? 'N/A' }} × 
                                {{ $transfer->shipment->width ?? 'N/A' }} × 
                                {{ $transfer->shipment->height ?? 'N/A' }} cm
                            </p>
                        </div>
                    </div>

                    @if($transfer->shipment->sender && $transfer->shipment->receiver)
                    <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold mb-2">Sender Information</p>
                            <p class="font-semibold">{{ $transfer->shipment->sender->first_name }} {{ $transfer->shipment->sender->last_name }}</p>
                            <p class="text-sm text-gray-600">{{ $transfer->shipment->sender->phone ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $transfer->shipment->sender_address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold mb-2">Receiver Information</p>
                            <p class="font-semibold">{{ $transfer->shipment->receiver->first_name }} {{ $transfer->shipment->receiver->last_name }}</p>
                            <p class="text-sm text-gray-600">{{ $transfer->shipment->receiver->phone ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $transfer->shipment->receiver_address ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Driver & Vehicle Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">TRANSPORT DETAILS</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Assigned Driver</p>
                        <p class="font-bold text-lg">
                            {{ $transfer->driver ? $transfer->driver->first_name . ' ' . $transfer->driver->last_name : 'Not Assigned' }}
                        </p>
                        @if($transfer->driver && $transfer->driver->phone)
                        <p class="text-sm text-gray-600 mt-1">Phone: {{ $transfer->driver->phone }}</p>
                        @endif
                        @if($transfer->driver && $transfer->driver->license_number)
                        <p class="text-sm text-gray-600">License: {{ $transfer->driver->license_number }}</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Vehicle Details</p>
                        <p class="font-bold text-lg">{{ $transfer->vehicle_number ?? 'Not Assigned' }}</p>
                        @if($transfer->driver && $transfer->driver->vehicle_type)
                        <p class="text-sm text-gray-600 mt-1">Type: {{ $transfer->driver->vehicle_type }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">TRANSFER TIMELINE</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-32 text-sm text-gray-600 font-semibold">Initiated:</div>
                        <div class="flex-1 font-semibold">
                            {{ $transfer->initiated_at ? $transfer->initiated_at->format('M d, Y H:i') : 'N/A' }}
                            @if($transfer->initiatedBy)
                            <span class="text-sm text-gray-600 ml-2">
                                by {{ $transfer->initiatedBy->first_name }} {{ $transfer->initiatedBy->last_name }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 text-sm text-gray-600 font-semibold">Departed:</div>
                        <div class="flex-1 font-semibold">
                            {{ $transfer->departed_at ? $transfer->departed_at->format('M d, Y H:i') : 'Pending' }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 text-sm text-gray-600 font-semibold">Arrived:</div>
                        <div class="flex-1 font-semibold">
                            {{ $transfer->arrived_at ? $transfer->arrived_at->format('M d, Y H:i') : 'Pending' }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 text-sm text-gray-600 font-semibold">Completed:</div>
                        <div class="flex-1 font-semibold">
                            {{ $transfer->completed_at ? $transfer->completed_at->format('M d, Y H:i') : 'Pending' }}
                            @if($transfer->receivedBy)
                            <span class="text-sm text-gray-600 ml-2">
                                by {{ $transfer->receivedBy->first_name }} {{ $transfer->receivedBy->last_name }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @if($transfer->duration)
                    <div class="flex items-center">
                        <div class="w-32 text-sm text-gray-600 font-semibold">Duration:</div>
                        <div class="flex-1 font-semibold">{{ $transfer->duration }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($transfer->transfer_notes)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">TRANSFER NOTES</h3>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <p class="text-gray-700">{{ $transfer->transfer_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Cancellation Reason -->
            @if($transfer->status === 'cancelled' && $transfer->reason)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-red-700 mb-3 border-b border-red-300 pb-2">CANCELLATION REASON</h3>
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <p class="text-red-700">{{ $transfer->reason }}</p>
                </div>
            </div>
            @endif

            <!-- Signatures -->
            <div class="mt-12 pt-6 border-t-2 border-gray-300">
                <div class="grid grid-cols-3 gap-8">
                    <div>
                        <p class="text-sm text-gray-600 mb-12">Sender's Signature</p>
                        <div class="border-t-2 border-gray-400 pt-1">
                            <p class="text-xs text-gray-500">Warehouse Staff</p>
                            @if($transfer->initiatedBy)
                            <p class="text-sm font-semibold">{{ $transfer->initiatedBy->first_name }} {{ $transfer->initiatedBy->last_name }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-12">Driver's Signature</p>
                        <div class="border-t-2 border-gray-400 pt-1">
                            <p class="text-xs text-gray-500">Driver</p>
                            @if($transfer->driver)
                            <p class="text-sm font-semibold">{{ $transfer->driver->first_name }} {{ $transfer->driver->last_name }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-12">Receiver's Signature</p>
                        <div class="border-t-2 border-gray-400 pt-1">
                            <p class="text-xs text-gray-500">Warehouse Staff</p>
                            @if($transfer->receivedBy)
                            <p class="text-sm font-semibold">{{ $transfer->receivedBy->first_name }} {{ $transfer->receivedBy->last_name }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-4 border-t text-center text-xs text-gray-500">
                <p>This is a computer-generated document and does not require a physical signature unless specified.</p>
                <p class="mt-1">Generated on {{ now()->format('M d, Y H:i:s') }}</p>
            </div>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>