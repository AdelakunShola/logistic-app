<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shipments Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .meta {
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Shipments Export</h1>
        <p style="text-align: center;">Generated on {{ now()->format('F d, Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tracking Number</th>
                <th>Customer</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Status</th>
                <th>Type</th>
                <th>Priority</th>
                <th>Weight (kg)</th>
                <th>Value ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $shipment)
            <tr>
                <td>{{ $shipment->id }}</td>
                <td>{{ $shipment->tracking_number }}</td>
                <td>{{ $shipment->customer ? $shipment->customer->first_name . ' ' . $shipment->customer->last_name : 'N/A' }}</td>
                <td>{{ $shipment->pickup_city }}, {{ $shipment->pickup_state }}</td>
                <td>{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $shipment->status)) }}</td>
                <td>{{ ucfirst($shipment->shipment_type) }}</td>
                <td>{{ ucfirst($shipment->delivery_priority) }}</td>
                <td>{{ number_format($shipment->total_weight, 2) }}</td>
                <td>{{ number_format($shipment->total_value, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="meta">
        <p>Total Shipments: {{ $shipments->count() }}</p>
        <p>Total Weight: {{ number_format($shipments->sum('total_weight'), 2) }} kg</p>
        <p>Total Value: ${{ number_format($shipments->sum('total_value'), 2) }}</p>
    </div>
</body>
</html>