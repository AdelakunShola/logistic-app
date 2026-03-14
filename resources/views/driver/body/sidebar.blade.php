<div class="fixed inset-y-0 z-50 flex w-64 flex-col border-r bg-background transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0" data-aria-hidden="true" aria-hidden="true">
	<div class="flex justify-between items-center border-b px-4">
		<a class="flex items-center gap-2 font-semibold h-16" href="/">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-6 w-6 text-primary" aria-hidden="true">
				<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
				<path d="M15 18H9"></path>
				<path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
				<circle cx="17" cy="18" r="2"></circle>
				<circle cx="7" cy="18" r="2"></circle>
			</svg>
			<span class="text-xl">WebMotionCargo</span>
		</a>
		<button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10 lg:hidden">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-5 w-5" aria-hidden="true">
				<path d="M18 6 6 18"></path>
				<path d="m6 6 12 12"></path>
			</svg>
			<span class="sr-only">Toggle sidebar</span>
		</button>
	</div>

	<!-- Quick Status Toggle -->
	<div class="border-b px-4 py-3 bg-accent/50">
		<div class="flex items-center justify-between">
			<span class="text-sm font-medium">Status</span>
			<button class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold bg-green-500 text-white hover:bg-green-600 transition-colors">
				<span class="h-2 w-2 rounded-full bg-white"></span>
				Online
			</button>
		</div>
	</div>

	<div class="overflow-auto py-2">
		<!-- Dashboard Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Dashboard</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.dashboard') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.dashboard') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard h-4 w-4" aria-hidden="true">
						<rect width="7" height="9" x="3" y="3" rx="1"></rect>
						<rect width="7" height="5" x="14" y="3" rx="1"></rect>
						<rect width="7" height="9" x="14" y="12" rx="1"></rect>
						<rect width="7" height="5" x="3" y="16" rx="1"></rect>
					</svg>
					<span>Overview</span>
				</a>
			</div>
		</div>

		<!-- My Deliveries Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">My Deliveries</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.active-deliveries') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.active-deliveries') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-4 w-4" aria-hidden="true">
						<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
						<path d="M15 18H9"></path>
						<path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
						<circle cx="17" cy="18" r="2"></circle>
						<circle cx="7" cy="18" r="2"></circle>
					</svg>
					<span>Active Deliveries</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.completed-deliveries') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.completed-deliveries') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check h-4 w-4" aria-hidden="true">
						<circle cx="12" cy="12" r="10"></circle>
						<path d="m9 12 2 2 4-4"></path>
					</svg>
					<span>Completed Deliveries</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.delayed-deliveries') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.delayed-deliveries') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4" aria-hidden="true">
						<path d="M12 6v6l4 2"></path>
						<circle cx="12" cy="12" r="10"></circle>
					</svg>
					<span>Delayed Deliveries</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.available-shipments') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.available-shipments') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-search h-4 w-4" aria-hidden="true">
						<path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"></path>
						<path d="m7.5 4.27 9 5.15"></path>
						<polyline points="3.29 7 12 12 20.71 7"></polyline>
						<line x1="12" x2="12" y1="22" y2="12"></line>
						<circle cx="18.5" cy="15.5" r="2.5"></circle>
						<path d="M20.27 17.27 22 19"></path>
					</svg>
					<span>Available Shipments</span>
				</a>
			</div>
		</div>

		<!-- Yard Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Yard</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.yard.check-in') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.yard.check-in') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in h-4 w-4" aria-hidden="true">
						<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
						<polyline points="10 17 15 12 10 7"></polyline>
						<line x1="15" x2="3" y1="12" y2="12"></line>
					</svg>
					<span>Yard Check-in</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.yard.my-visit') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.yard.my-visit') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4" aria-hidden="true">
						<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
						<circle cx="12" cy="10" r="3"></circle>
					</svg>
					<span>My Yard Visit</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.yard.appointments') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.yard.appointments') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-clock h-4 w-4" aria-hidden="true">
						<path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"></path>
						<path d="M16 2v4"></path>
						<path d="M8 2v4"></path>
						<path d="M3 10h5"></path>
						<path d="M17.5 17.5 16 16.3V14"></path>
						<circle cx="16" cy="16" r="6"></circle>
					</svg>
					<span>Yard Appointments</span>
				</a>
			</div>
		</div>

		<!-- My Vehicle Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">My Vehicle</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.vehicle.details') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.vehicle.details') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car-front h-4 w-4" aria-hidden="true">
						<path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8"></path>
						<path d="M7 14h.01"></path>
						<path d="M17 14h.01"></path>
						<rect width="18" height="8" x="3" y="10" rx="2"></rect>
						<path d="M5 18v2"></path>
						<path d="M19 18v2"></path>
					</svg>
					<span>Vehicle Details</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.maintenance.*') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.maintenance.index') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench h-4 w-4" aria-hidden="true">
						<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
					</svg>
					<span>Report Maintenance</span>
				</a>
			</div>
		</div>

		<!-- Support Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Support</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.support-tickets.*') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.support-tickets.index') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket h-4 w-4" aria-hidden="true">
						<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
						<path d="M13 5v2"></path>
						<path d="M13 17v2"></path>
						<path d="M13 11v2"></path>
					</svg>
					<span>Support Tickets</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="tel:+1234567890">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-4 w-4" aria-hidden="true">
						<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
						<path d="M14.05 2a9 9 0 0 1 8 7.94"></path>
						<path d="M14.05 6A5 5 0 0 1 18 10"></path>
					</svg>
					<span>Emergency Contact</span>
				</a>
			</div>
		</div>

		<!-- Team Chat Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Communication</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground {{ request()->routeIs('driver.chat.*') ? 'bg-accent text-accent-foreground' : 'text-foreground' }}" href="{{ route('driver.chat.index') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square h-4 w-4" aria-hidden="true">
						<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
					</svg>
					<span>Team Chat</span>
					<span id="chat-unread-badge" class="ml-auto hidden bg-blue-600 text-white text-xs font-bold rounded-full h-5 min-w-[20px] flex items-center justify-center px-1.5"></span>
				</a>
			</div>
		</div>

		<!-- Account Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Account</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-red-600 hover:text-red-700" href="{{ route('admin.logout') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out h-4 w-4" aria-hidden="true">
						<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
						<polyline points="16 17 21 12 16 7"></polyline>
						<line x1="21" x2="9" y1="12" y2="12"></line>
					</svg>
					<span>Logout</span>
				</a>
			</div>
		</div>
	</div>
</div>

<script>
// Fetch unread chat count for driver sidebar badge
function updateChatBadge() {
    fetch('{{ route("driver.chat.unread-count") }}')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('chat-unread-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        })
        .catch(() => {});
}
updateChatBadge();
setInterval(updateChatBadge, 30000);
</script>