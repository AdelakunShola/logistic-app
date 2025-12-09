<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Manifest - {{ $transfer->transfer_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #333; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #333; padding-bottom: 15px; }
        .header h1 { font-size: 24px; margin-bottom: 5px; color: #2563eb; }
        .header p { font-size: 14px; color: #666; }
        .info-section { margin-bottom: 20px; }
        .info-section h2 { font-size: 16px; margin-bottom: 10px; background-color: #f3f4f6; padding: 8px; border-left: 4px solid #2563eb; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .info-box { border: 1px solid #e5e7eb; padding: 12px; background-color: #f9fafb; }
        .info-box .label { font-weight: bold; font-size: 10px; color: #6b7280; text-transform: uppercase; margin-bottom: 4px; }
        .info-box .value { font-size: 13px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background-color: #374151; color: white; padding: 10px; text-align: left; font-size: 11px; }
        table td { border: 1px solid #e5e7eb; padding: 8px; font-size: 11px; }
        table tr:nth-child(even) { background-color: #f9fafb; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-in_transit { background-color: #dbeafe; color: #1e40af; }
        .status-completed { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        .timeline { margin: 20px 0; }
        .timeline-item { padding: 10px; border-left: 3px solid #2563eb; margin-left: 10px; margin-bottom: 10px; }
        .timeline-item .time { font-weight: bold; color: #2563eb; }
        .timeline-item .desc { color: #666; margin-top: 2px; }
        .signatures { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 40px; padding-top: 20px; border-top: 2px solid #333; }
        .signature-box { text-align: center; }
        .signature-line { border-top: 2px solid #333; margin-top: 60px; padding-top: 5px; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
        .notes-box { background-color: #fffbeb; border: 1px solid #fbbf24; padding: 12px; margin: 15px 0; }
        .barcode { text-align: center; margin: 20px 0; }
        .barcode-number { font-family: 'Courier New', monospace; font-size: 18px; font-weight: bold; letter-spacing: 2px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>WAREHOUSE TRANSFER MANIFEST</h1>
            <p>Transfer Document & Package Manifest</p>
        </div>

        <!-- Barcode/Transfer Code -->
        <div class="barcode">
            <div class="barcode-number">{{ $transfer->transfer_code }}</div>
            <p style="font-size: 10px; color: #666;">Transfer Identification Number</p>
        </div>

        <!-- Transfer Information -->
        <div class="info-section">
            <h2>Transfer Information</h2>
            <div class="info-grid">
                <div class="info-box">
                    <div class="label">Transfer ID</div>
                    <div class="value">{{ $transfer->transfer_code }}</div>
                </div>
                <div class="info-box">
                    <div class="label">Transfer Type</div>
                    <div class="value">{{ $transfer->transfer_type_label }}</div>
                </div>
                <div class="info-box">
                    <div class="label">Status</div>
                    <div class="value">
                        <span class="status-badge status-{{ $transfer->status }}">
                            {{ $transfer->status_label }}
                        </span>
                    </div>
                </div>
                <div class="info-box">
                    <div class="label">Initiated Date</div>
                    <div class="value">{{ $transfer->initiated_at ? $transfer->initiated_at->format('M d, Y H:i') : 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Route Information -->
        <div class="info-section">
            <h2>Route Details</h2>
            <div class="info-grid">
                <div class="info-box">
                    <div class="label">From Warehouse</div>
                    <div class="value">{{ $transfer->fromWarehouse->name }}</div>
                    <div style="font-size: 10px; color: #666; margin-top: 4px;">
                        {{ $transfer->fromWarehouse->warehouse_code }}<br>
                        {{ $transfer->fromWarehouse->city }}, {{ $transfer->fromWarehouse->state }}
                    </div>
                </div>
                <div class="info-box">
                    <div class="label">To Warehouse</div>
                    <div class="value">{{ $transfer->toWarehouse->name }}</div>
                    <div style="font-size: 10px; color: #666; margin-top: 4px;">
                        {{ $transfer->toWarehouse->warehouse_code }}<br>
                        {{ $transfer->toWarehouse->city }}, {{ $transfer->toWarehouse->state }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Driver & Vehicle -->
        <div class="info-section">
            <h2>Transport Details</h2>
            <div class="info-grid">
                <div class="info-box">
                    <div class="label">Assigned Driver</div>
                    <div class="value">
                        {{ $transfer->driver ? $transfer->driver->first_name . ' ' . $transfer->driver->last_name : 'Not Assigned' }}
                    </div>
                    @if($transfer->driver && $transfer->driver->phone)
                    <div style="font-size: 10px; color: #666; margin-top: 4px;">
                        Phone: {{ $transfer->driver->phone }}
                    </div>
                    @endif
                </div>
                <div class="info-box">
                    <div class="label">Vehicle Number</div>
                    <div class="value">{{ $transfer->vehicle_number ?? 'Not Assigned' }}</div>
                </div>
            </div>
        </div>

        <!-- Shipment Manifest -->
        <div class="info-section">
            <h2>Package Manifest</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tracking Number</th>
                        <th>Package Type</th>
                        <th>Weight (kg)</th>
                        <th>Dimensions (cm)</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: bold;">{{ $transfer->shipment->tracking_number ?? 'N/A' }}</td>
                        <td>{{ $transfer->shipment->package_type ?? 'N/A' }}</td>
                        <td>{{ $transfer->shipment->weight ?? 'N/A' }}</td>
                        <td>
                            {{ $transfer->shipment->length ?? 'N/A' }} × 
                            {{ $transfer->shipment->width ?? 'N/A' }} × 
                            {{ $transfer->shipment->height ?? 'N/A' }}
                        </td>
                        <td>
                            @if($transfer->shipment->sender)
                            {{ $transfer->shipment->sender->first_name }} {{ $transfer->shipment->sender->last_name }}
                            @else
                            N/A
                            @endif
                        </td>
                        <td>
                            @if($transfer->shipment->receiver)
                            {{ $transfer->shipment->receiver->first_name }} {{ $transfer->shipment->receiver->last_name }}
                            @else
                            N/A
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Timeline -->
        <div class="info-section">
            <h2>Transfer Timeline</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="time">Initiated: {{ $transfer->initiated_at ? $transfer->initiated_at->format('M d, Y H:i') : 'N/A' }}</div>
                    @if($transfer->initiatedBy)
                    <div class="desc">By {{ $transfer->initiatedBy->first_name }} {{ $transfer->initiatedBy->last_name }}</div>
                    @endif
                </div>
                
                @if($transfer->departed_at)
                <div class="timeline-item">
                    <div class="time">Departed: {{ $transfer->departed_at->format('M d, Y H:i') }}</div>
                    <div class="desc">Vehicle departed from origin warehouse</div>
                </div>
                @endif

                @if($transfer->arrived_at)
                <div class="timeline-item">
                    <div class="time">Arrived: {{ $transfer->arrived_at->format('M d, Y H:i') }}</div>
                    <div class="desc">Vehicle arrived at destination warehouse</div>
                </div>
                @endif

                @if($transfer->completed_at)
                <div class="timeline-item">
                    <div class="time">Completed: {{ $transfer->completed_at->format('M d, Y H:i') }}</div>
                    @if($transfer->receivedBy)
                    <div class="desc">Received by {{ $transfer->receivedBy->first_name }} {{ $transfer->receivedBy->last_name }}</div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Notes -->
        @if($transfer->transfer_notes)
        <div class="info-section">
            <h2>Transfer Notes / Special Instructions</h2>
            <div class="notes-box">
                {{ $transfer->transfer_notes }}
            </div>
        </div>
        @endif

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div>Sender's Signature</div>
                <div class="signature-line">
                    <div style="font-size: 10px; color: #666;">Warehouse Staff</div>
                    @if($transfer->initiatedBy)
                    <div style="font-weight: bold;">{{ $transfer->initiatedBy->first_name }} {{ $transfer->initiatedBy->last_name }}</div>
                    @endif
                </div>
            </div>
            <div class="signature-box">
                <div>Driver's Signature</div>
                <div class="signature-line">
                    <div style="font-size: 10px; color: #666;">Driver</div>
                    @if($transfer->driver)
                    <div style="font-weight: bold;">{{ $transfer->driver->first_name }} {{ $transfer->driver->last_name }}</div>
                    @endif
                </div>
            </div>
            <div class="signature-box">
                <div>Receiver's Signature</div>
                <div class="signature-line">
                    <div style="font-size: 10px; color: #666;">Warehouse Staff</div>
                    @if($transfer->receivedBy)
                    <div style="font-weight: bold;">{{ $transfer->receivedBy->first_name }} {{ $transfer->receivedBy->last_name }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Important Instructions -->
        <div class="info-section" style="margin-top: 30px;">
            <h2>Important Instructions</h2>
            <div style="font-size: 10px; line-height: 1.8;">
                <ol style="padding-left: 20px;">
                    <li>This manifest must accompany the shipment at all times during transfer.</li>
                    <li>Driver must verify package condition before departure and report any damage immediately.</li>
                    <li>All signatures are mandatory for transfer completion.</li>
                    <li>Report any delays or issues to dispatch immediately.</li>
                    <li>Ensure proper handling of fragile or special items as marked.</li>
                    <li>Receiver must inspect packages before signing for receipt.</li>
                </ol>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>This is a computer-generated document.</strong></p>
            <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
            <p>For inquiries, contact your logistics management office.</p>
        </div>
    </div>
</body>
</html>