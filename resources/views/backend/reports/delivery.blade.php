@extends('admin.admin_dashboard')
@section('admin')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #f9fafb;
        color: #111827;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-text h1 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .header-text p {
        color: #6b7280;
    }

    .header-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #1f2937;
        color: white;
    }

    .btn-primary:hover {
        background-color: #374151;
    }

    .btn-secondary {
        background-color: white;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background-color: #f3f4f6;
    }

    .btn svg {
        width: 1rem;
        height: 1rem;
        margin-right: 0.5rem;
    }

    /* Tabs */
    .tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .tab {
        padding: 0.75rem 1.5rem;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tab:hover {
        color: #111827;
    }

    .tab.active {
        color: #111827;
        border-bottom-color: #1f2937;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Cards */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .card-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }

    .card-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-value {
        font-size: 1.875rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .card-change {
        display: flex;
        align-items: center;
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }

    .card-change svg {
        width: 1rem;
        height: 1rem;
        margin-right: 0.25rem;
    }

    .progress-bar {
        width: 100%;
        height: 0.5rem;
        background-color: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background-color: #10b981;
        border-radius: 9999px;
        transition: width 0.3s;
    }

    /* Charts */
    .chart-container {
        height: 350px;
        position: relative;
    }

    .chart-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Tables */
    .table-container {
        overflow-x: auto;
        margin-top: 1rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        text-align: left;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    th {
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        background-color: #f9fafb;
    }

    td {
        color: #6b7280;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-in-transit {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-delivered {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-delayed {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-yes {
        color: #10b981;
    }

    .status-no {
        color: #ef4444;
    }

    /* Search and filters */
    .search-bar {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .search-input {
        flex: 1;
        padding: 0.75rem 1rem;
        padding-left: 2.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .search-wrapper {
        position: relative;
        flex: 1;
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .filter-select {
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        background: white;
    }

    /* Star rating */
    .star-rating {
        display: flex;
        gap: 0.25rem;
    }

    .star {
        color: #facc15;
        font-size: 1.25rem;
    }

    .star.empty {
        color: #d1d5db;
    }

    @media print {
        .header-actions,
        .tabs {
            display: none !important;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .header {
            flex-direction: column;
        }
        
        .card-grid {
            grid-template-columns: 1fr;
        }

        .chart-grid {
            grid-template-columns: 1fr;
        }



	
    }
</style>

<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="header-text">
            <h1>Delivery Performance</h1>
            <p>Monitor and analyze delivery metrics and carrier performance</p>
        </div>
        <div class="header-actions">
            <!-- Date Filter Form -->
            <form method="GET" action="{{ route('admin.performance.show') }}" class="flex gap-2 items-center">
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="filter-select">
                <input type="date" name="date_to" value="{{ $dateTo }}" class="filter-select">
                
                @if(!$isSingleDriver)
                <select name="driver_id" class="filter-select">
                    <option value="">All Drivers</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ $driverId == $driver->id ? 'selected' : '' }}>
                            {{ $driver->first_name }} {{ $driver->last_name }}
                        </option>
                    @endforeach
                </select>
                @endif
                
                <button type="submit" class="btn btn-secondary">Apply Filter</button>
                @if($driverId)
                    <a href="{{ route('admin.performance.show') }}" class="btn btn-secondary">Clear Filter</a>
                @endif
            </form>
            
            <!-- Export Dropdown -->
            <div style="position: relative; display: inline-block;">
                <button type="button" class="btn btn-secondary" id="exportDropdownBtn" onclick="toggleExportDropdown()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export
                </button>
                <div id="exportDropdown" style="display: none; position: absolute; right: 0; margin-top: 0.5rem; width: 200px; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); z-index: 1000;">
                    <a href="{{ route('admin.performance.export', ['format' => 'csv', 'date_from' => $dateFrom, 'date_to' => $dateTo, 'driver_id' => $driverId]) }}" class="export-item">Export as CSV</a>
                    <a href="{{ route('admin.performance.export', ['format' => 'excel', 'date_from' => $dateFrom, 'date_to' => $dateTo, 'driver_id' => $driverId]) }}" class="export-item">Export as Excel</a>
                    <a href="{{ route('admin.performance.export', ['format' => 'pdf', 'date_from' => $dateFrom, 'date_to' => $dateTo, 'driver_id' => $driverId]) }}" class="export-item">Export as PDF</a>
                </div>
            </div>
            
            <button type="button" onclick="location.reload()" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab active" data-tab="overview">Overview</button>
        @if(!$isSingleDriver)
        <button class="tab" data-tab="individual-drivers">Individual Drivers</button>
        @endif
        <button class="tab" data-tab="carriers">Carriers</button>
        <button class="tab" data-tab="regions">Regions</button>
        <button class="tab" data-tab="deliveries">Deliveries</button>
    </div>

    <!-- Overview Tab -->
    <div class="tab-content active" id="overview">
        @if($isSingleDriver)
            <div class="alert" style="background-color: #dbeafe; border: 1px solid #3b82f6; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <p style="color: #1e40af; font-weight: 500;">
                    Showing performance for: {{ $drivers->first()->first_name }} {{ $drivers->first()->last_name }}
                </p>
            </div>
        @endif
        
        <!-- Key Metrics -->
        <div class="card-grid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">On-Time Delivery Rate</h3>
                    <div class="card-icon" style="background-color: rgba(16, 185, 129, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-value">{{ $performanceData['overview_metrics']['on_time_rate'] }}%</div>
                <div class="card-change">
                    <svg xmlns="http://www.w3.org/2000/svg" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    {{ $isSingleDriver ? 'Current period' : '+2.5% from last month' }}
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $performanceData['overview_metrics']['on_time_rate'] }}%;"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Average Delivery Time</h3>
                    <div class="card-icon" style="background-color: rgba(16, 185, 129, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-value">{{ $performanceData['overview_metrics']['avg_delivery_time'] }} days</div>
                <div class="card-change">
                    <svg xmlns="http://www.w3.org/2000/svg" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                    {{ $isSingleDriver ? 'Current period' : '-0.3 days from last month' }}
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Satisfaction</h3>
                    <div class="card-icon" style="background-color: rgba(59, 130, 246, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" style="color: #3b82f6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-value">{{ number_format($performanceData['overview_metrics']['customer_satisfaction'], 1) }}/5</div>
                <div class="card-change">
                    <svg xmlns="http://www.w3.org/2000/svg" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    {{ $isSingleDriver ? 'Current period' : '+0.3 from last month' }}
                </div>
                <div class="star-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= floor($performanceData['overview_metrics']['customer_satisfaction']) ? '' : 'empty' }}">★</span>
                    @endfor
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Deliveries</h3>
                    <div class="card-icon" style="background-color: rgba(139, 92, 246, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" style="color: #8b5cf6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-value">{{ $performanceData['overview_metrics']['total_deliveries'] }}</div>
                <div class="card-change">
                    <span style="color: #6b7280;">In selected period</span>
                </div>
            </div>
        </div>

       <!-- Charts -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Delivery Performance Trends</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">Monthly on-time vs. delayed delivery percentages</p>
            <div class="chart-container">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>

        <div class="chart-grid">
            <div class="card">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">On-Time vs Delayed Deliveries</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">Distribution of delivery statuses</p>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <div class="card">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Delay Reasons</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">Common causes for delivery delays</p>
                <div class="chart-container">
                    <canvas id="delayChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Individual Drivers Tab (NEW) -->
    @if(!$isSingleDriver)
    <div class="tab-content" id="individual-drivers">
        <div class="card" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Individual Driver Performance</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">
                Detailed performance metrics for each driver ({{ $dateFrom }} to {{ $dateTo }})
            </p>
            
            <!-- Search and Sort -->
            <div class="search-bar" style="margin-bottom: 1.5rem;">
                <div class="search-wrapper">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" class="search-input" placeholder="Search drivers..." id="driverSearchInput">
                </div>
                <select class="filter-select" id="sortDrivers">
                    <option value="name">Sort by Name</option>
                    <option value="on_time_rate">Sort by On-Time Rate</option>
                    <option value="total_deliveries">Sort by Total Deliveries</option>
                    <option value="rating">Sort by Rating</option>
                </select>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3" id="driverPerformanceGrid">
                @foreach($drivers as $driver)
                <div class="driver-performance-card" data-driver-name="{{ strtolower($driver->first_name . ' ' . $driver->last_name) }}">
                    <!-- Driver Header -->
                    <div class="flex items-center space-x-3 mb-4 pb-4 border-b">
                        <span class="relative flex h-12 w-12 shrink-0 overflow-hidden rounded-full">
                            @if($driver->profile_photo)
                                <img alt="{{ $driver->first_name }}" src="{{ asset('storage/' . $driver->profile_photo) }}" class="w-full h-full object-cover"/>
                            @else
                                <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">
                                    {{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}
                                </span>
                            @endif
                        </span>
                        <div class="flex-1">
                            <h4 class="font-semibold text-lg">{{ $driver->first_name }} {{ $driver->last_name }}</h4>
                            <p class="text-sm text-muted-foreground">{{ $driver->employee_id }}</p>
                            <div class="flex items-center mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-400 fill-yellow-400 mr-1">
                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                </svg>
                                <span class="font-medium text-sm">{{ number_format($driver->rating ?? 0, 1) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Metrics -->
                    <div class="space-y-3">
                        <!-- On-Time Rate -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm text-muted-foreground">On-Time Rate</span>
                                <span class="text-lg font-bold" style="color: {{ $driver->on_time_rate >= 90 ? '#10b981' : ($driver->on_time_rate >= 80 ? '#f59e0b' : '#ef4444') }}">
                                    {{ number_format($driver->on_time_rate, 1) }}%
                                </span>
                            </div>
                            <div class="progress-bar" style="height: 6px;">
                                <div class="progress-fill" style="width: {{ $driver->on_time_rate }}%; background-color: {{ $driver->on_time_rate >= 90 ? '#10b981' : ($driver->on_time_rate >= 80 ? '#f59e0b' : '#ef4444') }}"></div>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="text-center p-3 bg-gray-50 rounded">
                                <div class="text-xl font-bold">{{ $driver->total_deliveries }}</div>
                                <div class="text-xs text-muted-foreground">Total</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded">
                                <div class="text-xl font-bold text-green-600">{{ $driver->successful_deliveries }}</div>
                                <div class="text-xs text-muted-foreground">Success</div>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded">
                                <div class="text-xl font-bold text-red-600">{{ $driver->failed_deliveries }}</div>
                                <div class="text-xs text-muted-foreground">Failed</div>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded">
                                <div class="text-xl font-bold text-blue-600">{{ $driver->on_time_deliveries }}</div>
                                <div class="text-xs text-muted-foreground">On-Time</div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="pt-3 border-t space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Weekly Hours:</span>
                                <span class="font-medium">{{ $driver->weekly_hours ?? '0h' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Monthly Earnings:</span>
                                <span class="font-medium">${{ number_format($driver->monthly_earnings ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-3">
                            <a href="{{ route('admin.performance.show', ['driver_id' => $driver->id, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                               class="btn btn-primary w-full text-center">
                                View Detailed Report
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($drivers->isEmpty())
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto text-gray-300 mb-4">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <p class="text-muted-foreground">No driver data available for the selected period</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Carriers Tab -->
    <div class="tab-content" id="carriers">
        <div class="card" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Carrier Performance</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">Comparison of delivery performance across carriers</p>
            <div class="chart-container">
                <canvas id="carrierChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Carrier Details</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">Detailed performance metrics for each carrier</p>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Carrier</th>
                            <th>On-Time %</th>
                            <th>Total Deliveries</th>
                            <th>Avg. Delay</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($performanceData['carriers'] as $carrier)
                        <tr>
                            <td style="font-weight: 600; color: #111827;">{{ $carrier['name'] }}</td>
                            <td>{{ $carrier['on_time_rate'] }}%</td>
                            <td>{{ $carrier['total_deliveries'] }}</td>
                            <td>{{ $carrier['avg_delay'] }} days</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="flex: 1; height: 0.5rem; background: #e5e7eb; border-radius: 9999px; overflow: hidden;">
                                        <div style="height: 100%; width: {{ $carrier['on_time_rate'] }}%; background: {{ $carrier['on_time_rate'] >= 90 ? '#10b981' : ($carrier['on_time_rate'] >= 80 ? '#facc15' : '#ef4444') }};"></div>
                                    </div>
                                    <span style="font-size: 0.75rem; font-weight: 500;">{{ $carrier['on_time_rate'] }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Regions Tab -->
    <div class="tab-content" id="regions">
        <div class="card" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Regional Performance</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">Delivery performance across different regions</p>
            <div class="chart-container">
                <canvas id="regionalChart"></canvas>
            </div>
        </div>

        <div class="card" style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">Delivery Performance Map</h3>
                    <p style="font-size: 0.875rem; color: #6b7280;">Interactive visualization of regional delivery performance</p>
                </div>
                <button class="btn btn-secondary" onclick="alert('Map fullscreen feature')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                    </svg>
                    Fullscreen
                </button>
            </div>
            <div style="background: #e0e7ff; border-radius: 0.5rem; padding: 3rem; min-height: 400px; position: relative;">
                <!-- Simplified US Map Visualization -->
                <div style="position: relative; max-width: 800px; margin: 0 auto;">
                    <!-- Map regions with performance indicators -->
                    @if(count($performanceData['regions']) > 0)
                        @foreach($performanceData['regions'] as $index => $region)
                            <div style="position: absolute; {{ $index == 0 ? 'top: 10%; left: 45%;' : ($index == 1 ? 'top: 60%; left: 40%;' : ($index == 2 ? 'top: 40%; right: 15%;' : 'top: 40%; left: 10%;')) }}">
                                <div style="text-align: center;">
                                    <div style="width: 80px; height: 80px; border-radius: 50%; background: {{ $region['on_time_rate'] >= 90 ? '#10b981' : ($region['on_time_rate'] >= 85 ? '#3b82f6' : ($region['on_time_rate'] >= 80 ? '#f59e0b' : '#ef4444')) }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.25rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                        {{ $region['on_time_rate'] }}%
                                    </div>
                                    <div style="margin-top: 0.5rem; background: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                        {{ $region['region'] }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: #4b5563; margin-top: 0.25rem; background: white; padding: 0.125rem 0.5rem; border-radius: 0.25rem;">
                                        {{ $region['total'] }} deliveries
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <!-- Legend -->
                <div style="position: absolute; bottom: 1rem; left: 1rem; background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem;">Performance Scale</div>
                    <div style="display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #10b981;"></div>
                            <span>90%+ Excellent</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #3b82f6;"></div>
                            <span>85-89% Good</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #f59e0b;"></div>
                            <span>80-84% Fair</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444;"></div>
                            <span>&lt;80% Poor</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Regional Details</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">Detailed performance metrics for each region</p>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Region</th>
                            <th>On-Time %</th>
                            <th>Total Deliveries</th>
                            <th>Avg. Delay</th>
                            <th>Major Cities</th>
                            <th>Top Issues</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($performanceData['regions'] as $region)
                        <tr>
                            <td style="font-weight: 600; color: #111827;">{{ $region['region'] }}</td>
                            <td>
                                <span class="status-badge" style="background: {{ $region['on_time_rate'] >= 90 ? '#d1fae5' : ($region['on_time_rate'] >= 85 ? '#dbeafe' : ($region['on_time_rate'] >= 80 ? '#fef3c7' : '#fee2e2')) }}; color: {{ $region['on_time_rate'] >= 90 ? '#065f46' : ($region['on_time_rate'] >= 85 ? '#1e40af' : ($region['on_time_rate'] >= 80 ? '#92400e' : '#991b1b')) }};">
                                    {{ $region['on_time_rate'] }}%
                                </span>
                            </td>
                            <td>{{ $region['total'] }}</td>
                            <td>{{ $region['avg_delay'] }} days</td>
                            <td>
                                @if($region['region'] == 'North')
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem; margin-right: 0.25rem;">New York</span>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem; margin-right: 0.25rem;">Boston</span>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem;">+2 more</span>
                                @elseif($region['region'] == 'South')
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem; margin-right: 0.25rem;">Miami</span>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem; margin-right: 0.25rem;">Atlanta</span>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem;">+2 more</span>
                                @elseif($region['region'] == 'East')
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem; margin-right: 0.25rem;">Philadelphia</span>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem; margin-right: 0.25rem;">Washington DC</span>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem;">+2 more</span>
                                @else
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem; margin-right: 0.25rem;">Los Angeles</span>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem; margin-right: 0.25rem;">San Francisco</span>
                                    <span style="display: inline-block; padding: 0.125rem 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.75rem;">+2 more</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24" style="color: #f59e0b;">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                    </svg>
                                    <span>
                                        @if($region['region'] == 'North')
                                            Weather Conditions
                                        @elseif($region['region'] == 'South')
                                            Hurricane Season
                                        @elseif($region['region'] == 'East')
                                            Urban Traffic
                                        @else
                                            Wildfires
                                        @endif
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Deliveries Tab -->
    <div class="tab-content" id="deliveries">
        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Recent Deliveries</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">Detailed list of recent deliveries and their performance</p>

            <!-- Search and Filters -->
            <div class="search-bar">
                <div class="search-wrapper">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" class="search-input" placeholder="Search by ID, order, customer, or destination..." id="deliverySearch">
                </div>
                <select class="filter-select" id="carrierFilter">
                    <option value="">All Carriers</option>
                    @foreach($performanceData['carriers'] as $carrier)
                        <option value="{{ $carrier['name'] }}">{{ $carrier['name'] }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="in_transit">In Transit</option>
                    <option value="delivered">Delivered</option>
                    <option value="delayed">Delayed</option>
                </select>
            </div>

            <div class="table-container">
                <table id="deliveriesTable">
                    <thead>
                        <tr>
                            <th>Delivery ID</th>
                            <th>Customer</th>
                            <th>Destination</th>
                            <th>Carrier</th>
                            <th>Status</th>
                            <th>Scheduled</th>
                            <th>Actual</th>
                            <th>On Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($performanceData['recent_deliveries'] as $delivery)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #111827;">{{ $delivery->tracking_number }}</div>
                                <div style="font-size: 0.75rem; color: #9ca3af;">{{ $delivery->reference_number }}</div>
                            </td>
                            <td>{{ $delivery->customer_name }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.25rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #9ca3af;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $delivery->delivery_city }}, {{ $delivery->delivery_state }}</span>
                                </div>
                            </td>
                            <td>{{ $delivery->carrier_name ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ str_replace('_', '-', $delivery->status) }}">
                                    @if($delivery->status == 'in_transit')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right: 0.25rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        In Transit
                                    @elseif($delivery->status == 'delivered')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right: 0.25rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Delivered
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right: 0.25rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Delayed
                                    @endif
                                </span>
                            </td>
                            <td>{{ $delivery->pickup_scheduled_date ? \Carbon\Carbon::parse($delivery->pickup_scheduled_date)->format('Y-m-d') : '-' }}</td>
                            <td>{{ $delivery->actual_delivery_date ? \Carbon\Carbon::parse($delivery->actual_delivery_date)->format('Y-m-d') : '-' }}</td>
                            <td>
                                @if($delivery->on_time == 'yes')
                                    <span class="status-yes" style="display: flex; align-items: center; gap: 0.25rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Yes
                                    </span>
                                @else
                                    <span class="status-no" style="display: flex; align-items: center; gap: 0.25rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        No
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Chart colors
    const colors = {
        blue: '#3b82f6',
        green: '#10b981',
        yellow: '#f59e0b',
        red: '#ef4444',
        purple: '#8b5cf6',
        teal: '#14b8a6',
        orange: '#f97316',
        gray: '#6b7280'
    };

    // Trends Chart
    const trendsData = @json($performanceData['trends']);
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    
    new Chart(trendsCtx, {
        type: 'bar',
        data: {
            labels: trendsData.map(d => d.month),
            datasets: [
                {
                    label: 'Delayed',
                    data: trendsData.map(d => d.delayed),
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: colors.red,
                    borderWidth: 0
                },
                {
                    label: 'On Time',
                    data: trendsData.map(d => d.on_time),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: colors.blue,
                    borderWidth: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                x: {
                    stacked: false,
                    grid: {
                        display: false
                    }
                },
                y: {
                    stacked: false,
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Status Distribution Chart
    const statusData = @json($performanceData['status_distribution']);
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: ['On Time', 'Delayed'],
            datasets: [{
                data: [statusData.on_time_percentage, statusData.delayed_percentage],
                backgroundColor: [colors.blue, colors.green],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 13
                        },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            return data.labels.map((label, i) => ({
                                text: `${label}: ${data.datasets[0].data[i]}%`,
                                fillStyle: data.datasets[0].backgroundColor[i],
                                hidden: false,
                                index: i
                            }));
                        }
                    }
                }
            }
        }
    });

    // Delay Reasons Chart
    const delayData = @json($performanceData['delay_reasons']);
    const delayCtx = document.getElementById('delayChart').getContext('2d');
    
    new Chart(delayCtx, {
        type: 'pie',
        data: {
            labels: delayData.map(d => d.reason),
            datasets: [{
                data: delayData.map(d => d.percentage),
                backgroundColor: [colors.blue, colors.green, colors.yellow, colors.orange, colors.purple, colors.gray],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 11
                        },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            return data.labels.map((label, i) => ({
                                text: `${label}: ${data.datasets[0].data[i]}%`,
                                fillStyle: data.datasets[0].backgroundColor[i],
                                hidden: false,
                                index: i
                            }));
                        }
                    }
                }
            }
        }
    });

    // Carrier Performance Chart
    const carrierData = @json($performanceData['carriers']);
    const carrierCtx = document.getElementById('carrierChart').getContext('2d');
    
    new Chart(carrierCtx, {
        type: 'bar',
        data: {
            labels: carrierData.map(c => c.name),
            datasets: [
                {
                    label: 'On-Time Percentage',
                    data: carrierData.map(c => c.on_time_rate),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    yAxisID: 'y'
                },
                {
                    label: 'Total Deliveries',
                    data: carrierData.map(c => c.total_deliveries),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'On-Time Percentage (%)'
                    },
                    beginAtZero: true,
                    max: 100
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Total Deliveries'
                    },
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Regional Performance Chart
    const regionalData = @json($performanceData['regions']);
    const regionalCtx = document.getElementById('regionalChart').getContext('2d');
    
    new Chart(regionalCtx, {
        type: 'bar',
        data: {
            labels: regionalData.map(r => r.region),
            datasets: [
                {
                    label: 'On-Time Percentage',
                    data: regionalData.map(r => r.on_time_rate),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    yAxisID: 'y'
                },
                {
                    label: 'Total Deliveries',
                    data: regionalData.map(r => r.total),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'On-Time Percentage (%)'
                    },
                    beginAtZero: true,
                    max: 100
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Total Deliveries'
                    },
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Search and filter functionality
    const searchInput = document.getElementById('deliverySearch');
    const carrierFilter = document.getElementById('carrierFilter');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('deliveriesTable');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const carrierValue = carrierFilter.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matchesSearch = text.includes(searchTerm);
            const matchesCarrier = !carrierValue || text.includes(carrierValue);
            const matchesStatus = !statusValue || text.includes(statusValue);
            
            row.style.display = matchesSearch && matchesCarrier && matchesStatus ? '' : 'none';
        });
    }
    
    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (carrierFilter) carrierFilter.addEventListener('change', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
});
</script>


<script>
// Export Dropdown Toggle
function toggleExportDropdown() {
    const dropdown = document.getElementById('exportDropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('exportDropdown');
    const button = document.getElementById('exportDropdownBtn');
    
    if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});


</script>











<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Driver search functionality
    const driverSearchInput = document.getElementById('driverSearchInput');
    const sortDrivers = document.getElementById('sortDrivers');
    
    if (driverSearchInput) {
        driverSearchInput.addEventListener('input', function() {
            filterDriverCards();
        });
    }
    
    if (sortDrivers) {
        sortDrivers.addEventListener('change', function() {
            sortDriverCards(this.value);
        });
    }
    
    function filterDriverCards() {
        const searchTerm = driverSearchInput.value.toLowerCase();
        const cards = document.querySelectorAll('.driver-performance-card');
        
        cards.forEach(card => {
            const driverName = card.getAttribute('data-driver-name');
            if (driverName.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    function sortDriverCards(sortBy) {
        const grid = document.getElementById('driverPerformanceGrid');
        const cards = Array.from(document.querySelectorAll('.driver-performance-card'));
        
        cards.sort((a, b) => {
            if (sortBy === 'name') {
                return a.getAttribute('data-driver-name').localeCompare(b.getAttribute('data-driver-name'));
            }
            // Add more sorting options as needed
            return 0;
        });
        
        cards.forEach(card => grid.appendChild(card));
    }

    // Your existing chart code...
});



</script>
@endsection
