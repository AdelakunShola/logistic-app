@extends('driver.driver_dashboard')
@section('driver') 

<script>
// Safety check - remove any stuck modals on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded - checking for stuck elements');
    
    // Force close all modals
    const modals = document.querySelectorAll('[id$="-modal"]');
    modals.forEach(modal => {
        if (modal && !modal.classList.contains('hidden')) {
            console.log('Found open modal:', modal.id);
            modal.classList.add('hidden');
        }
    });
    
    // Ensure body is interactive
    document.body.style.pointerEvents = '';
    document.body.style.overflow = '';
    
    console.log('Cleanup complete');
});
</script>

<main class="flex-1 overflow-y-auto p-4 md:p-6">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-wrap gap-3 items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Welcome Back, {{ Auth::user()->first_name }}!</h1>
                <p class="text-muted-foreground">Your delivery dashboard for {{ date('l, F j, Y') }}</p>
            </div>
            <div class="flex gap-2">
                <!-- Status Toggle -->
                <button onclick="toggleStatus()" id="statusBtn" class="inline-flex items-center justify-center gap-2 rounded-full px-4 py-2 text-sm font-semibold bg-green-500 text-white hover:bg-green-600 transition-colors">
                    <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                    <span id="statusText">Available for Delivery</span>
                </button>
            </div>
        </div>

        <!-- Driver Stats Cards -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <!-- Today's Deliveries -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Today's Deliveries</h3>
                    <div class="p-2 rounded-full text-blue-600 bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold">{{ $todayDeliveries ?? 8 }}</div>
                            <p class="text-xs text-muted-foreground mt-1">{{ $completedToday ?? 5 }} completed</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-green-600">{{ $pendingDeliveries ?? 3 }}</span>
                            <p class="text-xs text-muted-foreground">pending</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Earnings -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Today's Earnings</h3>
                    <div class="p-2 rounded-full text-green-600 bg-green-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <line x1="12" x2="12" y1="2" y2="22"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">${{ number_format($todayEarnings ?? 156.50, 2) }}</div>
                    <p class="text-xs text-muted-foreground mt-1">This week: ${{ number_format($weekEarnings ?? 892.00, 2) }}</p>
                </div>
            </div>

            <!-- Distance Covered -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Distance Today</h3>
                    <div class="p-2 rounded-full text-purple-600 bg-purple-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">{{ $distanceToday ?? 45 }} km</div>
                    <p class="text-xs text-muted-foreground mt-1">{{ $totalTrips ?? 12 }} trips</p>
                </div>
            </div>

            <!-- My Rating -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">My Rating</h3>
                    <div class="p-2 rounded-full text-yellow-600 bg-yellow-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">{{ number_format(Auth::user()->rating ?? 4.8, 1) }}/5.0</div>
                    <p class="text-xs text-muted-foreground mt-1">Based on {{ $totalRatings ?? 124 }} ratings</p>
                </div>
            </div>
        </div>

        <!-- Current Route & My Vehicle -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Current Route Card -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                    <h3 class="text-xl font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-500">
                            <path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
                        </svg>
                        Current Route
                    </h3>
                </div>
                <div class="p-4 md:p-6 pt-0 space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div>
                            <p class="font-semibold">Route A-15</p>
                            <p class="text-sm text-muted-foreground">Downtown Area</p>
                        </div>
                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Active</span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="p-2 rounded-full bg-green-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium">Next Stop</p>
                                <p class="text-sm text-muted-foreground">123 Main Street, Downtown</p>
                                <p class="text-xs text-muted-foreground mt-1">ETA: 15 mins</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 rounded-full bg-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium">Remaining Stops</p>
                                <p class="text-sm text-muted-foreground">3 more deliveries</p>
                            </div>
                        </div>
                    </div>

                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
                        </svg>
                        Navigate to Next Stop
                    </button>
                </div>
            </div>

            <!-- My Vehicle Card -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                    <h3 class="text-xl font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-purple-500">
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                            <path d="M15 18H9"></path>
                            <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                            <circle cx="17" cy="18" r="2"></circle>
                            <circle cx="7" cy="18" r="2"></circle>
                        </svg>
                        My Vehicle
                    </h3>
                </div>
                <div class="p-4 md:p-6 pt-0 space-y-4">
                  
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div>
                            <p class="font-semibold">8766556</p>
                            <p class="text-sm text-muted-foreground">bike</p>
                        </div>
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Active</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-muted-foreground">Fuel Level</p>
                            <p class="text-lg font-bold mt-1">75%</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-muted-foreground">Mileage</p>
                            <p class="text-lg font-bold mt-1">45562 km</p>
                        </div>
                    </div>

                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg">
                        Report Issue
                    </button>
                    
                    <div class="text-center py-8">
                        <p class="text-muted-foreground">No vehicle assigned</p>
                        <p class="text-sm text-muted-foreground mt-2">Contact dispatch for vehicle assignment</p>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Today's Delivery Schedule -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                    </svg>
                    Today's Delivery Schedule
                </h3>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="space-y-4">
                    <!-- Completed Delivery -->
                    <div class="flex items-start gap-4 p-4 rounded-lg border bg-green-50">
                        <div class="p-2 rounded-full bg-green-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold">#PKG-1234</p>
                                <span class="text-xs bg-green-500 text-white px-2 py-1 rounded-full">Delivered</span>
                            </div>
                            <p class="text-sm text-muted-foreground mt-1">456 Oak Avenue, Westside</p>
                            <p class="text-xs text-muted-foreground mt-1">Delivered at 10:45 AM</p>
                        </div>
                    </div>

                    <!-- In Progress -->
                    <div class="flex items-start gap-4 p-4 rounded-lg border bg-blue-50">
                        <div class="p-2 rounded-full bg-blue-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                <path d="M15 18H9"></path>
                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                <circle cx="17" cy="18" r="2"></circle>
                                <circle cx="7" cy="18" r="2"></circle>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold">#PKG-1235</p>
                                <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded-full">In Transit</span>
                            </div>
                            <p class="text-sm text-muted-foreground mt-1">123 Main Street, Downtown</p>
                            <p class="text-xs text-muted-foreground mt-1">ETA: 15 minutes</p>
                            <div class="mt-2 flex gap-2">
                                <button class="text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Mark as Delivered</button>
                                <button class="text-xs border px-3 py-1 rounded hover:bg-gray-100">Contact Customer</button>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Deliveries -->
                    <div class="flex items-start gap-4 p-4 rounded-lg border">
                        <div class="p-2 rounded-full bg-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 6v6l4 2"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold">#PKG-1236</p>
                                <span class="text-xs bg-gray-200 px-2 py-1 rounded-full">Pending</span>
                            </div>
                            <p class="text-sm text-muted-foreground mt-1">789 Park Lane, Northside</p>
                            <p class="text-xs text-muted-foreground mt-1">Scheduled for 2:00 PM</p>
                        </div>
                    </div>
                </div>

                <button class="w-full mt-4 border border-input bg-background hover:bg-accent py-2 px-4 rounded-lg font-medium">
                    View Full Schedule
                </button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                <h3 class="text-xl font-semibold">Quick Actions</h3>
            </div>
            <div class="p-4 md:p-6 pt-0">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <button class="flex flex-col items-center gap-2 p-4 border rounded-lg hover:bg-accent hover:shadow-md transition-all">
                        <div class="p-2 rounded-full bg-blue-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                                <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                                <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                                <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Scan Package</span>
                    </button>

                    <button class="flex flex-col items-center gap-2 p-4 border rounded-lg hover:bg-accent hover:shadow-md transition-all">
                        <div class="p-2 rounded-full bg-green-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Proof of Delivery</span>
                    </button>

                    <button class="flex flex-col items-center gap-2 p-4 border rounded-lg hover:bg-accent hover:shadow-md transition-all">
                        <div class="p-2 rounded-full bg-purple-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Contact Support</span>
                    </button>

                    <button class="flex flex-col items-center gap-2 p-4 border rounded-lg hover:bg-accent hover:shadow-md transition-all">
                        <div class="p-2 rounded-full bg-red-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Report Issue</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function toggleStatus() {
    const btn = document.getElementById('statusBtn');
    const text = document.getElementById('statusText');
    
    if (btn.classList.contains('bg-green-500')) {
        btn.classList.remove('bg-green-500', 'hover:bg-green-600');
        btn.classList.add('bg-gray-500', 'hover:bg-gray-600');
        text.textContent = 'Offline';
        
        // Update status in backend
        fetch('/driver/update-status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: 'offline' })
        });
    } else {
        btn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
        btn.classList.add('bg-green-500', 'hover:bg-green-600');
        text.textContent = 'Available for Delivery';
        
        fetch('/driver/update-status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: 'available' })
        });
    }
}
</script>



@endsection