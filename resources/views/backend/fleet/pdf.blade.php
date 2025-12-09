<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maintenance Logs Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
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
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-in_progress {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-scheduled {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .priority {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .priority-critical {
            background-color: #f8d7da;
            color: #721c24;
        }
        .priority-high {
            background-color: #fff3cd;
            color: #856404;
        }
        .priority-medium {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .priority-low {
            background-color: #d4edda;
            color: #155724;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
        .summary-label {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Maintenance Logs Report</h1>
        <p>Generated on {{ now()->format('F d, Y - h:i A') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Records:</span> {{ $maintenanceLogs->count() }}
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Cost:</span> ${{ number_format($maintenanceLogs->sum('cost'), 2) }}
        </div>
        <div class="summary-item">
            <span class="summary-label">Completed:</span> {{ $maintenanceLogs->where('status', 'completed')->count() }}
        </div>
        <div class="summary-item">
            <span class="summary-label">Pending:</span> {{ $maintenanceLogs->whereIn('status', ['scheduled', 'in_progress'])->count() }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Vehicle</th>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Cost</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maintenanceLogs as $log)
            <tr>
                <td>{{ $log->log_number }}</td>
                <td>
                    {{ $log->vehicle->registration_number }}<br>
                    <small>{{ $log->vehicle->make }} {{ $log->vehicle->model }}</small>
                </td>
                <td>{{ ucfirst($log->maintenance_type) }}</td>
                <td>{{ Str::limit($log->description, 50) }}</td>
                <td>
                    <span class="status status-{{ $log->status }}">
                        {{ ucfirst($log->status) }}
                    </span>
                </td>
                <td>
                    <span class="priority priority-{{ $log->priority ?? 'medium' }}">
                        {{ ucfirst($log->priority ?? 'medium') }}
                    </span>
                </td>
                <td>${{ number_format($log->cost, 2) }}</td>
                <td>{{ $log->maintenance_date->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer-generated report. No signature required.</p>
        <p>&copy; {{ date('Y') }} Vehicle Management System. All rights reserved.</p>
    </div>
</body>
</html>