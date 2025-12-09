@extends('admin.admin_dashboard')
@section('admin') 


<main class="flex-1 overflow-y-auto p-4 md:p-6">
					<div>
						<div class="space-y-6">
							<div class="flex flex-wrap gap-3 items-center justify-between" data-aria-hidden="true" aria-hidden="true">
								<div>
									<h1 class="text-2xl md:text-3xl font-bold tracking-tight">Dashboard Overview</h1>
									<p class="text-muted-foreground">Welcome back! Here's what's happening with your logistics operations.</p>
								</div>
								<div class="flex gap-2">
									<button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="radix-_r_6_" data-state="closed">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-2" aria-hidden="true">
											<path d="M5 12h14"></path>
											<path d="M12 5v14"></path>
										</svg>
										Add Vehicle
									</button>
									<button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="radix-_r_9_" data-state="closed">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-2" aria-hidden="true">
											<path d="M5 12h14"></path>
											<path d="M12 5v14"></path>
										</svg>
										New Shipment
									</button>
								</div>
							</div>
							<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
    {{-- Active Shipments Card --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
        <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="sm:text-2xl tracking-tight text-sm font-medium text-muted-foreground">Active Shipments</h3>
            <div class="p-2 rounded-full text-green-600 bg-green-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-4 w-4" aria-hidden="true">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <path d="M15 18H9"></path>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
            </div>
        </div>
        <div class="p-4 md:p-6 pt-0">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold">{{ $stats['active_shipments'] }}</div>
                    <p class="text-xs text-muted-foreground mt-1">Currently in transit</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($stats['active_shipments_growth'] >= 0)
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-green-600 bg-green-50">
                            +{{ $stats['active_shipments_growth'] }}%
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-4 w-4 text-green-500" aria-hidden="true">
                            <path d="M16 7h6v6"></path>
                            <path d="m22 7-8.5 8.5-5-5L2 17"></path>
                        </svg>
                    @else
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-red-600 bg-red-50">
                            {{ $stats['active_shipments_growth'] }}%
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down h-4 w-4 text-red-500" aria-hidden="true">
                            <path d="M16 17h6v-6"></path>
                            <path d="m22 17-8.5-8.5-5 5L2 7"></path>
                        </svg>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delivered Today Card --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
        <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="sm:text-2xl tracking-tight text-sm font-medium text-muted-foreground">Delivered Today</h3>
            <div class="p-2 rounded-full text-green-600 bg-green-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package h-4 w-4" aria-hidden="true">
                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                    <path d="M12 22V12"></path>
                    <polyline points="3.29 7 12 12 20.71 7"></polyline>
                    <path d="m7.5 4.27 9 5.15"></path>
                </svg>
            </div>
        </div>
      @php
    use App\Models\Shipment;
    
    $deliveredToday = Shipment::where('status', 'delivered')
        ->whereDate('actual_delivery_date', today())
        ->count();
    
    $deliveredYesterday = Shipment::where('status', 'delivered')
        ->whereDate('actual_delivery_date', today()->subDay())
        ->count();
    
    $deliveredTodayGrowth = $deliveredYesterday > 0 
        ? round((($deliveredToday - $deliveredYesterday) / $deliveredYesterday) * 100, 0)
        : ($deliveredToday > 0 ? 100 : 0);
@endphp

<div class="p-4 md:p-6 pt-0">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-2xl font-bold">{{ $deliveredToday }}</div>
            <p class="text-xs text-muted-foreground mt-1">Successful deliveries</p>
        </div>
        <div class="flex items-center space-x-2">
            @if($deliveredTodayGrowth >= 0)
                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-green-600 bg-green-50">
                    +{{ $deliveredTodayGrowth }}%
                </span> 
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-4 w-4 text-green-500" aria-hidden="true">
                    <path d="M16 7h6v6"></path>
                    <path d="m22 7-8.5 8.5-5-5L2 17"></path>
                </svg>
            @else
                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-red-600 bg-red-50">
                    {{ $deliveredTodayGrowth }}%
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down h-4 w-4 text-red-500" aria-hidden="true">
                    <path d="M16 17h6v-6"></path>
                    <path d="m22 17-8.5-8.5-5 5L2 7"></path>
                </svg>
            @endif
        </div>
    </div>
</div>
    </div>

    {{-- Pending Orders Card --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
        <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="sm:text-2xl tracking-tight text-sm font-medium text-muted-foreground">Pending Orders</h3>
            <div class="p-2 rounded-full {{ $stats['pending_orders'] > 100 ? 'text-red-600 bg-red-50' : 'text-yellow-600 bg-yellow-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4" aria-hidden="true">
                    <path d="M12 6v6l4 2"></path>
                    <circle cx="12" cy="12" r="10"></circle>
                </svg>
            </div>
        </div>
        <div class="p-4 md:p-6 pt-0">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold">{{ $stats['pending_orders'] }}</div>
                    <p class="text-xs text-muted-foreground mt-1">Awaiting processing</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($stats['pending_orders_growth'] <= 0)
                        {{-- Negative growth for pending is good (fewer pending orders) --}}
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-green-600 bg-green-50">
                            {{ $stats['pending_orders_growth'] }}%
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down h-4 w-4 text-green-500" aria-hidden="true">
                            <path d="M16 17h6v-6"></path>
                            <path d="m22 17-8.5-8.5-5 5L2 7"></path>
                        </svg>
                    @else
                        {{-- Positive growth for pending is bad (more pending orders) --}}
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-red-600 bg-red-50">
                            +{{ $stats['pending_orders_growth'] }}%
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-4 w-4 text-red-500" aria-hidden="true">
                            <path d="M16 7h6v6"></path>
                            <path d="m22 7-8.5 8.5-5-5L2 17"></path>
                        </svg>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue (MTD) Card --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
        <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="sm:text-2xl tracking-tight text-sm font-medium text-muted-foreground">Revenue (MTD)</h3>
            <div class="p-2 rounded-full text-green-600 bg-green-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign h-4 w-4" aria-hidden="true">
                    <line x1="12" x2="12" y1="2" y2="22"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
        </div>
        <div class="p-4 md:p-6 pt-0">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold">${{ number_format($stats['revenue_mtd'], 0) }}</div>
                    <p class="text-xs text-muted-foreground mt-1">Month to date</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($stats['revenue_growth'] >= 0)
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-green-600 bg-green-50">
                            +{{ $stats['revenue_growth'] }}%
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-4 w-4 text-green-500" aria-hidden="true">
                            <path d="M16 7h6v6"></path>
                            <path d="m22 7-8.5 8.5-5-5L2 17"></path>
                        </svg>
                    @else
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-red-600 bg-red-50">
                            {{ $stats['revenue_growth'] }}%
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down h-4 w-4 text-red-500" aria-hidden="true">
                            <path d="M16 17h6v-6"></path>
                            <path d="m22 17-8.5-8.5-5 5L2 7"></path>
                        </svg>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Optional: Add Performance Metrics Row Below --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 mt-4">
    {{-- On-Time Delivery Rate --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium text-muted-foreground">On-Time Delivery</h3>
        </div>
        <div class="text-3xl font-bold">{{ $stats['on_time_rate'] }}%</div>
        <div class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full transition-all duration-500 {{ $stats['on_time_rate'] >= 90 ? 'bg-green-500' : ($stats['on_time_rate'] >= 70 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                 style="width: {{ $stats['on_time_rate'] }}%">
            </div>
        </div>
    </div>

    {{-- Average Delivery Time --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium text-muted-foreground">Avg Delivery Time</h3>
        </div>
        <div class="text-3xl font-bold">{{ $stats['avg_delivery_time'] }}h</div>
        <p class="text-xs text-muted-foreground mt-2">
            Target: 48h 
            @if($stats['avg_delivery_time'] <= 48)
                <span class="text-green-600">✓</span>
            @else
                <span class="text-red-600">✗</span>
            @endif
        </p>
    </div>

    {{-- Active Drivers --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium text-muted-foreground">Active Drivers</h3>
        </div>
        <div class="text-3xl font-bold text-blue-600">{{ $stats['active_drivers'] }}</div>
        <p class="text-xs text-muted-foreground mt-2">
            {{ $stats['busy_drivers'] }} currently busy
        </p>
    </div>

    {{-- Delayed Shipments --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium text-muted-foreground">Delayed Shipments</h3>
        </div>
        <div class="text-3xl font-bold {{ $stats['delayed_shipments'] > 0 ? 'text-red-600' : 'text-green-600' }}">
            {{ $stats['delayed_shipments'] }}
        </div>
        <p class="text-xs text-muted-foreground mt-2">
            @if($stats['critical_delays'] > 0)
                <span class="text-red-600 font-semibold">{{ $stats['critical_delays'] }} critical</span>
            @else
                <span class="text-green-600">No critical delays</span>
            @endif
        </p>
    </div>
</div>
						<!---	<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4" data-aria-hidden="true" aria-hidden="true">
								<div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
									<div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
										<h3 class="sm:text-2xl tracking-tight text-sm font-medium text-muted-foreground">Fleet Utilization</h3>
										<div class="p-2 rounded-full text-green-600 bg-green-50">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-4 w-4" aria-hidden="true">
												<path d="M16 7h6v6"></path>
												<path d="m22 7-8.5 8.5-5-5L2 17"></path>
											</svg>
										</div>
									</div>
									<div class="p-4 md:p-6 pt-0">
										<div class="flex items-center justify-between">
											<div>
												<div class="text-2xl font-bold">87%</div>
												<p class="text-xs text-muted-foreground mt-1">Vehicle efficiency</p>
											</div>
											<div class="flex items-center space-x-2">
												<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-green-600 bg-green-50">+3%</span>
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-4 w-4 text-green-500" aria-hidden="true">
													<path d="M16 7h6v6"></path>
													<path d="m22 7-8.5 8.5-5-5L2 17"></path>
												</svg>
											</div>
										</div>
									</div>
								</div>
								<div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
									<div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
										<h3 class="sm:text-2xl tracking-tight text-sm font-medium text-muted-foreground">Active Clients</h3>
										<div class="p-2 rounded-full text-green-600 bg-green-50">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4" aria-hidden="true">
												<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
												<path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
												<path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
												<circle cx="9" cy="7" r="4"></circle>
											</svg>
										</div>
									</div>
									<div class="p-4 md:p-6 pt-0">
										<div class="flex items-center justify-between">
											<div>
												<div class="text-2xl font-bold">1247</div>
												<p class="text-xs text-muted-foreground mt-1">Total active clients</p>
											</div>
											<div class="flex items-center space-x-2">
												<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-green-600 bg-green-50">+23%</span>
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-4 w-4 text-green-500" aria-hidden="true">
													<path d="M16 7h6v6"></path>
													<path d="m22 7-8.5 8.5-5-5L2 17"></path>
												</svg>
											</div>
										</div>
									</div>
								</div>
								<div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
									<div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
										<h3 class="sm:text-2xl tracking-tight text-sm font-medium text-muted-foreground">Warehouse Capacity</h3>
										<div class="p-2 rounded-full text-red-600 bg-red-50">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-warehouse h-4 w-4" aria-hidden="true">
												<path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"></path>
												<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"></path>
												<path d="M6 13h12"></path>
												<path d="M6 17h12"></path>
											</svg>
										</div>
									</div>
									<div class="p-4 md:p-6 pt-0">
										<div class="flex items-center justify-between">
											<div>
												<div class="text-2xl font-bold">73%</div>
												<p class="text-xs text-muted-foreground mt-1">Average utilization</p>
											</div>
											<div class="flex items-center space-x-2">
												<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-red-600 bg-red-50">-2%</span>
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down h-4 w-4 text-red-500" aria-hidden="true">
													<path d="M16 17h6v-6"></path>
													<path d="m22 17-8.5-8.5-5 5L2 7"></path>
												</svg>
											</div>
										</div>
									</div>
								</div>
								<div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
									<div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
										<h3 class="sm:text-2xl tracking-tight text-sm font-medium text-muted-foreground">Delayed Shipments</h3>
										<div class="p-2 rounded-full text-red-600 bg-red-50">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert h-4 w-4" aria-hidden="true">
												<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
												<path d="M12 9v4"></path>
												<path d="M12 17h.01"></path>
											</svg>
										</div>
									</div>
									<div class="p-4 md:p-6 pt-0">
										<div class="flex items-center justify-between">
											<div>
												<div class="text-2xl font-bold">7</div>
												<p class="text-xs text-muted-foreground mt-1">Requiring attention</p>
											</div>
											<div class="flex items-center space-x-2">
												<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-secondary/80 text-red-600 bg-red-50">-12%</span>
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down h-4 w-4 text-red-500" aria-hidden="true">
													<path d="M16 17h6v-6"></path>
													<path d="m22 17-8.5-8.5-5 5L2 7"></path>
												</svg>
											</div>
										</div>
									</div>
								</div>
							</div>-->

							{{-- SHIPMENT TRENDS CHART & FLEET STATUS --}}
<div class="grid gap-6 md:grid-cols-2">

    {{-- Shipment Trends Chart --}}
     <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
    <div class="flex flex-col space-y-1.5 p-4 md:p-6">
        <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Shipment Trends</h3>
        <div class="text-sm text-muted-foreground">Monthly shipment and delivery performance over the past year</div>
    </div>
    <div class="p-4 md:p-6 pt-0">
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="shipmentTrendsChart"></canvas>
        </div>
    </div>
</div>



    {{-- Fleet Status Overview --}}
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-5 w-5">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <path d="M15 18H9"></path>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
                Fleet Status Overview
            </h3>
        </div>
        <div class="p-4 md:p-6 pt-0 space-y-6">
            {{-- Fleet Summary --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $stats['total_vehicles'] }}</div>
                    <div class="text-sm text-muted-foreground">Total Vehicles</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold {{ $stats['fleet_efficiency'] >= 80 ? 'text-green-600' : ($stats['fleet_efficiency'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $stats['fleet_efficiency'] }}%
                    </div>
                    <div class="text-sm text-muted-foreground">Efficiency</div>
                </div>
            </div>

            {{-- Fleet Breakdown --}}
            <div class="space-y-4">
                {{-- Active Vehicles --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big h-4 w-4 text-green-500">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <span class="text-sm">Active</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium">{{ $stats['active_vehicles'] }}</span>
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800">
                            {{ $stats['total_vehicles'] > 0 ? round(($stats['active_vehicles'] / $stats['total_vehicles']) * 100) : 0 }}%
                        </span>
                    </div>
                </div>
                <div class="relative w-full overflow-hidden rounded-full bg-secondary h-2">
                    <div class="h-full w-full flex-1 bg-green-500 transition-all" 
                         style="width: {{ $stats['total_vehicles'] > 0 ? ($stats['active_vehicles'] / $stats['total_vehicles']) * 100 : 0 }}%">
                    </div>
                </div>

                {{-- Maintenance Vehicles --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench h-4 w-4 text-yellow-500">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                        <span class="text-sm">Maintenance</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium">{{ $stats['maintenance_vehicles'] }}</span>
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-yellow-100 text-yellow-800">
                            {{ $stats['total_vehicles'] > 0 ? round(($stats['maintenance_vehicles'] / $stats['total_vehicles']) * 100) : 0 }}%
                        </span>
                    </div>
                </div>
                <div class="relative w-full overflow-hidden rounded-full bg-secondary h-2">
                    <div class="h-full w-full flex-1 bg-yellow-500 transition-all" 
                         style="width: {{ $stats['total_vehicles'] > 0 ? ($stats['maintenance_vehicles'] / $stats['total_vehicles']) * 100 : 0 }}%">
                    </div>
                </div>

                {{-- Available Vehicles --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle h-4 w-4 text-blue-500">
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        <span class="text-sm">Available</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium">{{ $stats['available_vehicles'] }}</span>
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ $stats['total_vehicles'] > 0 ? round(($stats['available_vehicles'] / $stats['total_vehicles']) * 100) : 0 }}%
                        </span>
                    </div>
                </div>
                <div class="relative w-full overflow-hidden rounded-full bg-secondary h-2">
                    <div class="h-full w-full flex-1 bg-blue-500 transition-all" 
                         style="width: {{ $stats['total_vehicles'] > 0 ? ($stats['available_vehicles'] / $stats['total_vehicles']) * 100 : 0 }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- RECENT ACTIVITY & ACTIVE DELIVERIES --}}
<div class="grid gap-6 grid-cols-12 mt-6">
    {{-- Recent Activity --}}
    <div class="col-span-12 md:col-span-6 2xl:col-span-8">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big h-5 w-5">
                        <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                        <path d="m9 11 3 3L22 4"></path>
                    </svg>
                    Recent Activity
                </h3>
            </div>
            <div class="p-4 md:p-6 pt-0">
              

<div class="space-y-4">
    @forelse($recentActivities as $activity)
    <div class="flex items-start space-x-4 pb-4 last:pb-0 border-b last:border-b-0">
        {{-- Icon with colored background --}}
        <div class="flex-shrink-0 mt-1 p-2.5 rounded-full
            @if(str_contains(strtolower($activity['action']), 'created') || str_contains(strtolower($activity['action']), 'new')) 
                bg-blue-100 text-blue-600
            @elseif(str_contains(strtolower($activity['action']), 'completed') || str_contains(strtolower($activity['action']), 'delivered') || str_contains(strtolower($activity['action']), 'optimized'))
                bg-green-100 text-green-600
            @elseif(str_contains(strtolower($activity['action']), 'maintenance') || str_contains(strtolower($activity['action']), 'due'))
                bg-yellow-100 text-yellow-600
            @elseif(str_contains(strtolower($activity['action']), 'delayed') || str_contains(strtolower($activity['action']), 'delay') || str_contains(strtolower($activity['action']), 'failed'))
                bg-red-100 text-red-600
            @else
                bg-gray-100 text-gray-600
            @endif">
            
            {{-- Different icons based on activity type --}}
            @if($activity['icon'] === 'truck' || $activity['model_type'] === 'Shipment')
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <path d="M15 18H9"></path>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
            @elseif($activity['icon'] === 'package')
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <path d="m7.5 4.27 9 5.15"></path>
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                    <path d="m3.3 7 8.7 5 8.7-5"></path>
                    <path d="M12 22V12"></path>
                </svg>
            @elseif($activity['icon'] === 'alert-triangle' || str_contains(strtolower($activity['action']), 'maintenance'))
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                    <path d="M12 9v4"></path>
                    <path d="M12 17h.01"></path>
                </svg>
            @elseif($activity['icon'] === 'alert-circle' || str_contains(strtolower($activity['action']), 'delayed'))
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
            @endif
        </div>
        
        {{-- Activity content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <h4 class="text-sm font-semibold text-foreground">{{ $activity['action'] }}</h4>
                <span class="text-xs text-muted-foreground whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}
                </span>
            </div>
            
            <p class="text-sm text-muted-foreground mt-1 leading-relaxed">
                {{ $activity['description'] }}
            </p>
            
            {{-- User info with avatar --}}
            @if(isset($activity['user']) && $activity['user'])
            <div class="flex items-center mt-3 space-x-2">
                <div class="relative flex shrink-0 overflow-hidden rounded-full h-6 w-6 ring-2 ring-white">
                    @if(isset($activity['user']->profile_image) && $activity['user']->profile_image)
                        <img src="{{ asset('storage/' . $activity['user']->profile_image) }}" 
                             alt="{{ $activity['user']->first_name }}" 
                             class="h-full w-full object-cover">
                    @else
                        <span class="flex h-full w-full items-center justify-center text-xs font-semibold
                            @if(str_contains(strtolower($activity['action']), 'created')) bg-blue-500 text-white
                            @elseif(str_contains(strtolower($activity['action']), 'delivered')) bg-green-500 text-white
                            @elseif(str_contains(strtolower($activity['action']), 'maintenance')) bg-yellow-500 text-white
                            @elseif(str_contains(strtolower($activity['action']), 'delayed')) bg-red-500 text-white
                            @else bg-gray-500 text-white
                            @endif">
                            {{ strtoupper(substr($activity['user']->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($activity['user']->last_name ?? 'N', 0, 1)) }}
                        </span>
                    @endif
                </div>
                <span class="text-xs font-medium text-muted-foreground">
                    {{ $activity['user']->first_name ?? 'Unknown' }} {{ $activity['user']->last_name ?? 'User' }}
                </span>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-12 w-12 mx-auto text-muted-foreground/30 mb-3">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
        </svg>
        <p class="text-sm text-muted-foreground">No recent activity</p>
    </div>
    @endforelse
</div>
            </div>
        </div>
    </div>

    {{-- Active Deliveries by Location --}}
    <div class="col-span-12 md:col-span-6 2xl:col-span-4">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-5 w-5">
                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Active Deliveries
                </h3>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="space-y-4">
                    @forelse($activeDeliveriesByLocation as $location)
                    <div class="flex flex-wrap gap-1 items-center justify-between p-3 rounded-lg border hover:bg-muted/50 transition-colors">
                        <div class="flex items-center space-x-3">
                            @if($location['is_delayed'])
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-red-500">
                                    <path d="M12 6v6l4 2"></path>
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-green-500">
                                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                    <circle cx="17" cy="18" r="2"></circle>
                                    <circle cx="7" cy="18" r="2"></circle>
                                </svg>
                            @endif
                            <div>
                                <div class="font-medium">{{ $location['city'] }}, {{ $location['state'] }}</div>
                                <div class="text-sm text-muted-foreground">{{ $location['count'] }} active shipments</div>
                            </div>
                        </div>
                        <div class="sm:text-right space-y-1">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold 
                                {{ $location['is_delayed'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $location['status'] }}
                            </span>
                            <div class="text-xs text-muted-foreground">
                                {{ floor($location['avg_hours']) }}h {{ round(($location['avg_hours'] - floor($location['avg_hours'])) * 60) }}m
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-muted-foreground">
                        <p>No active deliveries</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>


							<div class="rounded-lg border bg-card text-card-foreground shadow-sm" data-aria-hidden="true" aria-hidden="true">
								<div class="flex flex-col space-y-1.5 p-4 md:p-6">
									<h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Quick Actions</h3>
								</div>
								<div class="p-4 md:p-6 pt-0">
									<div class="grid grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-3">
										<a class="h-auto p-4 flex flex-col items-center gap-2 hover:shadow-md transition-all border rounded-md border-input bg-background hover:bg-accent hover:text-accent-foreground" href="{{ route('admin.shipments.create') }}">
											<div class="p-2 rounded-full text-white bg-blue-500 hover:bg-blue-600">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-plus h-4 w-4" aria-hidden="true">
													<rect width="18" height="18" x="3" y="3" rx="2"></rect>
													<path d="M8 12h8"></path>
													<path d="M12 8v8"></path>
												</svg>
											</div>
											<div class="text-center">
												<div class="text-xs font-medium">Create Shipment</div>
												<div class="text-xs text-muted-foreground hidden md:block">Add a new shipment to the system</div>
											</div>
										</a>
										<a class="h-auto p-4 flex flex-col items-center gap-2 hover:shadow-md transition-all border rounded-md border-input bg-background hover:bg-accent hover:text-accent-foreground" href="{{ route('admin.shipment.track.index') }}">
											<div class="p-2 rounded-full text-white bg-green-500 hover:bg-green-600">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-navigation h-4 w-4" aria-hidden="true">
													<polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
												</svg>
											</div>
											<div class="text-center">
												<div class="text-xs font-medium">Track Package</div>
												<div class="text-xs text-muted-foreground hidden md:block">Track existing shipments</div>
											</div>
										</a>
										<a class="h-auto p-4 flex flex-col items-center gap-2 hover:shadow-md transition-all border rounded-md border-input bg-background hover:bg-accent hover:text-accent-foreground" href="{{ route('admin.vehicles.create') }}">
											<div class="p-2 rounded-full text-white bg-purple-500 hover:bg-purple-600">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bus h-4 w-4" aria-hidden="true">
													<path d="M8 6v6"></path>
													<path d="M15 6v6"></path>
													<path d="M2 12h19.6"></path>
													<path d="M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"></path>
													<circle cx="7" cy="18" r="2"></circle>
													<path d="M9 18h5"></path>
													<circle cx="16" cy="18" r="2"></circle>
												</svg>
											</div>
											<div class="text-center">
												<div class="text-xs font-medium">Add Vehicle</div>
												<div class="text-xs text-muted-foreground hidden md:block">Register a new vehicle</div>
											</div>
										</a>
										<!---<a class="h-auto p-4 flex flex-col items-center gap-2 hover:shadow-md transition-all border rounded-md border-input bg-background hover:bg-accent hover:text-accent-foreground" href="/vendors/add">
											<div class="p-2 rounded-full text-white bg-orange-500 hover:bg-orange-600">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus h-4 w-4" aria-hidden="true">
													<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
													<circle cx="9" cy="7" r="4"></circle>
													<line x1="19" x2="19" y1="8" y2="14"></line>
													<line x1="22" x2="16" y1="11" y2="11"></line>
												</svg>
											</div>
											<div class="text-center">
												<div class="text-xs font-medium">Add Vendor</div>
												<div class="text-xs text-muted-foreground hidden md:block">Register a new vendor</div>
											</div>
										</a>--->
										<!---<a class="h-auto p-4 flex flex-col items-center gap-2 hover:shadow-md transition-all border rounded-md border-input bg-background hover:bg-accent hover:text-accent-foreground" href="/warehouses/inventory">
											<div class="p-2 rounded-full text-white bg-teal-500 hover:bg-teal-600">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package h-4 w-4" aria-hidden="true">
													<path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
													<path d="M12 22V12"></path>
													<polyline points="3.29 7 12 12 20.71 7"></polyline>
													<path d="m7.5 4.27 9 5.15"></path>
												</svg>
											</div>
											<div class="text-center">
												<div class="text-xs font-medium">Inventory Check</div>
												<div class="text-xs text-muted-foreground hidden md:block">Check warehouse inventory</div>
											</div>
										</a>--->
										<a class="h-auto p-4 flex flex-col items-center gap-2 hover:shadow-md transition-all border rounded-md border-input bg-background hover:bg-accent hover:text-accent-foreground" href="{{ route('admin.performance.show') }}">
											<div class="p-2 rounded-full text-white bg-indigo-500 hover:bg-indigo-600">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-column h-4 w-4" aria-hidden="true">
													<path d="M3 3v16a2 2 0 0 0 2 2h16"></path>
													<path d="M18 17V9"></path>
													<path d="M13 17V5"></path>
													<path d="M8 17v-3"></path>
												</svg>
											</div>
											<div class="text-center">
												<div class="text-xs font-medium">Generate Report</div>
												<div class="text-xs text-muted-foreground hidden md:block">Create performance reports</div>
											</div>
										</a>
										<a class="h-auto p-4 flex flex-col items-center gap-2 hover:shadow-md transition-all border rounded-md border-input bg-background hover:bg-accent hover:text-accent-foreground" href="{{ route('settings.index') }}">
											<div class="p-2 rounded-full text-white bg-gray-500 hover:bg-gray-600">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings h-4 w-4" aria-hidden="true">
													<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
													<circle cx="12" cy="12" r="3"></circle>
												</svg>
											</div>
											<div class="text-center">
												<div class="text-xs font-medium">System Settings</div>
												<div class="text-xs text-muted-foreground hidden md:block">Configure system preferences</div>
											</div>
										</a>
										<a class="h-auto p-4 flex flex-col items-center gap-2 hover:shadow-md transition-all border rounded-md border-input bg-background hover:bg-accent hover:text-accent-foreground" href="/help">
											<div class="p-2 rounded-full text-white bg-pink-500 hover:bg-pink-600">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-4 w-4" aria-hidden="true">
													<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
													<path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
													<path d="M10 9H8"></path>
													<path d="M16 13H8"></path>
													<path d="M16 17H8"></path>
												</svg>
											</div>
											<div class="text-center">
												<div class="text-xs font-medium">Documentation</div>
												<div class="text-xs text-muted-foreground hidden md:block">Access help and guides</div>
											</div>
										</a>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</main>
		



<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    'use strict';
    
    // Wait for everything to load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChart);
    } else {
        initChart();
    }
    
    function initChart() {
        console.log('Initializing chart...');
        
        // Small delay to ensure Chart.js is fully loaded
        setTimeout(function() {
            const canvas = document.getElementById('shipmentTrendsChart');
            
            if (!canvas) {
                console.error('Canvas not found!');
                return;
            }
            
            if (typeof Chart === 'undefined') {
                console.error('Chart.js not loaded!');
                return;
            }
            
            // Your data from Laravel
            const labels = ["Nov","Dec","Jan","Mar","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct"];
            const totalData = [0,0,0,0,0,0,0,0,0,1,34,15];
            const deliveredData = [0,0,0,0,0,0,0,0,0,0,5,3];
            
            // Or use Laravel data:
            // const monthlyData = @json($chartData['monthly_trends']);
            // const labels = monthlyData.map(item => item.month);
            // const totalData = monthlyData.map(item => item.total);
            // const deliveredData = monthlyData.map(item => item.delivered);
            
            console.log('Creating chart with data:', { labels, totalData, deliveredData });
            
            const ctx = canvas.getContext('2d');
            
            const chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Shipments',
                            data: totalData,
                            borderColor: 'rgb(96, 165, 250)',
                            backgroundColor: 'rgba(96, 165, 250, 0.3)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointBackgroundColor: 'rgb(96, 165, 250)'
                        },
                        {
                            label: 'Delivered',
                            data: deliveredData,
                            borderColor: 'rgb(52, 211, 153)',
                            backgroundColor: 'rgba(52, 211, 153, 0.3)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointBackgroundColor: 'rgb(52, 211, 153)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 12
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });
            
            console.log('Chart created successfully!', chartInstance);
            
        }, 100); // Small delay
    }
})();
</script>
@endsection