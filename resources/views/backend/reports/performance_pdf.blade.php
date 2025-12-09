<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Performance Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .metric { margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Delivery Performance Report</h1>
        <p>Period: {{ $dateFrom }} to {{ $dateTo }}</p>
        <p>Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
    
    <h2>Overview Metrics</h2>
    <div class="metric">On-Time Delivery Rate: {{ $performanceData['overview']['avg_on_time_rate'] }}%</div>
    <div class="metric">Average Delivery Time: {{ $performanceData['overview']['avg_delivery_time'] }} days</div>
    <div class="metric">Total Deliveries: {{ $performanceData['overview']['total_deliveries'] }}</div>
    
    <h2>Driver Performance</h2>
    <table>
        <thead>
            <tr>
                <th>Driver Name</th>
                <th>Employee ID</th>
                <th>Total Deliveries</th>
                <th>On-Time Rate</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($performanceData['drivers'] as $driver)
            <tr>
                <td>{{ $driver['name'] }}</td>
                <td>{{ $driver['employee_id'] }}</td>
                <td>{{ $driver['total_deliveries'] }}</td>
                <td>{{ $driver['on_time_rate'] }}%</td>
                <td>{{ $driver['rating'] }}/5.0</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>