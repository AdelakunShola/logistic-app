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
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground bg-accent text-accent-foreground" href="{{ route('driver.dashboard') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard h-4 w-4" aria-hidden="true">
						<rect width="7" height="9" x="3" y="3" rx="1"></rect>
						<rect width="7" height="5" x="14" y="3" rx="1"></rect>
						<rect width="7" height="9" x="14" y="12" rx="1"></rect>
						<rect width="7" height="5" x="3" y="16" rx="1"></rect>
					</svg>
					<span>Overview</span>
				</a>
				<!--<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map h-4 w-4" aria-hidden="true">
						<path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
						<path d="M15 5.764v15"></path>
						<path d="M9 3.236v15"></path>
					</svg>
					<span>Live Route Map</span>
				</a>-->
			</div>
		</div>

		<!-- My Deliveries Section -->
		<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">My Deliveries </h2>
						<div class="space-y-1">
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('driver.active-deliveries') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-4 w-4" aria-hidden="true">
									<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
									<path d="M15 18H9"></path>
									<path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
									<circle cx="17" cy="18" r="2"></circle>
									<circle cx="7" cy="18" r="2"></circle>
								</svg>
								<span>Active Deliveries</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('driver.completed-deliveries') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-navigation h-4 w-4" aria-hidden="true">
									<polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
								</svg>
								<span>Completed Deliveries</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('driver.delayed-deliveries') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4" aria-hidden="true">
									<path d="M12 6v6l4 2"></path>
									<circle cx="12" cy="12" r="10"></circle>
								</svg>
								<span>Delayed Deliveries</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-open h-4 w-4" aria-hidden="true">
						<path d="M12 22v-9"></path>
						<path d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"></path>
						<path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"></path>
						<path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"></path>
					</svg>
					<span>Pickup Requests</span>
				</a>
						</div>
					</div>
		<!-- Delivery Tasks Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Delivery Tasks</h2>
			<div class="space-y-1">
				
				
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-barcode h-4 w-4" aria-hidden="true">
						<path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
						<path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
						<path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
						<path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
						<path d="M8 7v10"></path>
						<path d="M12 7v10"></path>
						<path d="M17 7v10"></path>
					</svg>
					<span>Scan Package</span>
				</a>
			
			</div>
		</div>

		<!-- My Vehicle Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">My Vehicle</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('driver.vehicle.details') }}">
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
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('driver.maintenance.index') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench h-4 w-4" aria-hidden="true">
						<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
					</svg>
					<span>Report Maintenance</span>
				</a>
			</div>
		</div>

		<!-- Earnings & Performance Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Earnings</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign h-4 w-4" aria-hidden="true">
						<line x1="12" x2="12" y1="2" y2="22"></line>
						<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
					</svg>
					<span>Today's Earnings</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up h-4 w-4" aria-hidden="true">
						<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
						<polyline points="16 7 22 7 22 13"></polyline>
					</svg>
					<span>Performance Stats</span>
				</a>
			</div>
		</div>

		<!-- Support & Communication Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Support</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square h-4 w-4" aria-hidden="true">
						<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
					</svg>
					<span>Messages</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle h-4 w-4" aria-hidden="true">
						<circle cx="12" cy="12" r="10"></circle>
						<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
						<path d="M12 17h.01"></path>
					</svg>
					<span>Help & Support</span>
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

		<!-- Settings Section -->
		<div class="px-3 py-2">
			<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Settings</h2>
			<div class="space-y-1">
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4" aria-hidden="true">
						<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
						<circle cx="12" cy="7" r="4"></circle>
					</svg>
					<span>My Profile</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell h-4 w-4" aria-hidden="true">
						<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
						<path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
					</svg>
					<span>Notifications</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings h-4 w-4" aria-hidden="true">
						<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
						<circle cx="12" cy="12" r="3"></circle>
					</svg>
					<span>App Preferences</span>
				</a>
				<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-red-600 hover:text-red-700" href="">
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