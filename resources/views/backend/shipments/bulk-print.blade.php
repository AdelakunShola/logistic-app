<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Shipments</title>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        
        .shipment-card {
            border: 2px solid #333;
            padding: 20px;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .shipment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .info-section {
            margin-bottom: 15px;
        }
        
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        
        .info-value {
            flex: 1;
        }
        
        .barcode {
            text-align: center;
            font-size: 24px;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            padding: 10px;
            border: 1px solid #333;
            margin: 15px 0;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .print-button:hover {
            background: #2563eb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        
        table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print All</button>
    
    <div class="print-header no-print">
        <h1>Shipment Labels - Bulk Print</h1>
        <p>Total Shipments: {{ count($shipments) }}</p>
        <p>Printed: {{ date('F j, Y g:i A') }}</p>
    </div>
    
    @foreach($shipments as $index => $shipment)
    <div class="shipment-card {{ $index < count($shipments) - 1 ? 'page-break' : '' }}">
        <div class="shipment-header">
            <div>
                <h2 style="margin: 0;">Shipment #{{ $shipment->id }}</h2>
                <p style="margin: 5px 0; color: #666;">{{ $shipment->created_at->format('M d, Y') }}</p>
            </div>
            <div style="text-align: right;">
                <span style="display: inline-block; padding: 5px 10px; background: 
                    @if($shipment->status === 'delivered') #10b981
                    @elseif($shipment->status === 'in_transit') #3b82f6
                    @elseif($shipment->status === 'pending') #f59e0b
                    @else #6b7280
                    @endif; 
                    color: white; border-radius: 4px; font-size: 12px; font-weight: bold;">
                    {{ strtoupper(str_replace('_', ' ', $shipment->status)) }}
                </span>
            </div>
        </div>
        
        <div class="barcode">
            {{ $shipment->tracking_number }}
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="info-section">
                <h3>📦 Pickup / Sender's Information</h3>
                <div class="info-row">
                    <div class="info-label">Service:</div>
                    <div class="info-value">{{ ucfirst($shipment->service_level ?? 'Road') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Weight:</div>
                    <div class="info-value">{{ $shipment->total_weight ?? 0 }} kg</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Value:</div>
                    <div class="info-value">${{ number_format($shipment->total_value ?? 0, 2) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Items:</div>
                    <div class="info-value">{{ $shipment->number_of_items ?? 0 }}</div>
                </div>
            </div>
        </div>
        
        @if($shipment->shipmentItems && $shipment->shipmentItems->count() > 0)
        <div class="info-section" style="margin-top: 20px;">
            <h3>📦 Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Weight (kg)</th>
                        <th>Value ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipment->shipmentItems as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->weight ?? 0 }}</td>
                        <td>${{ number_format($item->value ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        @if($shipment->carrier)
        <div class="info-section" style="margin-top: 20px;">
            <h3>🚚 Carrier Information</h3>
            <div class="info-row">
                <div class="info-label">Carrier:</div>
                <div class="info-value">{{ $shipment->carrier->name }}</div>
            </div>
            @if($shipment->carrier->contact_email)
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $shipment->carrier->contact_email }}</div>
            </div>
            @endif
            @if($shipment->carrier->contact_phone)
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $shipment->carrier->contact_phone }}</div>
            </div>
            @endif
        </div>
        @endif
        
        @if($shipment->customer)
        <div class="info-section" style="margin-top: 20px;">
            <h3>👤 Customer Information</h3>
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $shipment->customer->first_name }} {{ $shipment->customer->last_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $shipment->customer->email }}</div>
            </div>
            @if($shipment->customer->phone)
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $shipment->customer->phone }}</div>
            </div>
            @endif
        </div>
        @endif
        
        @if($shipment->special_instructions)
        <div class="info-section" style="margin-top: 20px;">
            <h3>⚠️ Special Instructions</h3>
            <p style="margin: 0; padding: 10px; background: #fef3c7; border-left: 4px solid #f59e0b;">
                {{ $shipment->special_instructions }}
            </p>
        </div>
        @endif
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px dashed #999; text-align: center; color: #666; font-size: 11px;">
            <p style="margin: 0;">This is an official shipment label. Please handle with care.</p>
            <p style="margin: 5px 0 0 0;">Printed on {{ date('F j, Y \a\t g:i A') }}</p>
        </div-row">
                    <div class="info-label">Contact:</div>
                    <div class="info-value">{{ $shipment->pickup_contact_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $shipment->pickup_contact_phone }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Address:</div>
                    <div class="info-value">{{ $shipment->pickup_address }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">City:</div>
                    <div class="info-value">{{ $shipment->pickup_city }}, {{ $shipment->pickup_state }} {{ $shipment->pickup_postal_code }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Country:</div>
                    <div class="info-value">{{ $shipment->pickup_country }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ $shipment->pickup_date ? $shipment->pickup_date->format('M d, Y') : 'N/A' }}</div>
                </div>
            </div>
            
            <div class="info-section">
                <h3>📍 Delivery Information</h3>
                <div class="info-row">
                    <div class="info-label">Contact:</div>
                    <div class="info-value">{{ $shipment->delivery_contact_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $shipment->delivery_contact_phone }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Address:</div>
                    <div class="info-value">{{ $shipment->delivery_address }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">City:</div>
                    <div class="info-value">{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }} {{ $shipment->delivery_postal_code }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Country:</div>
                    <div class="info-value">{{ $shipment->delivery_country }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Expected:</div>
                    <div class="info-value">{{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('M d, Y') : 'N/A' }}</div>
                </div>
            </div>
        </div>
        
        <div class="info-section" style="margin-top: 20px;">
            <h3>📋 Shipment Details</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                <div class="info-row">
                    <div class="info-label">Type:</div>
                    <div class="info-value">{{ ucfirst($shipment->shipment_type) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Priority:</div>
                    <div class="info-value">{{ ucfirst($shipment->delivery_priority) }}</div>
                </div>
                <div class="info