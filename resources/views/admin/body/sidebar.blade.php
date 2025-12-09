    <div class="fixed inset-y-0 z-50 flex w-64 flex-col border-r bg-background transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0" data-aria-hidden="true" aria-hidden="true">
				<div class="flex justify-between  items-center border-b px-4">
					<a class="flex items-center  gap-2 font-semibold h-16" href="/">
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
				<div class="overflow-auto py-2">
					<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Dashboard</h2>
						<div class="space-y-1">
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground bg-accent text-accent-foreground" href="{{ route('admin.dashboard') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard h-4 w-4" aria-hidden="true">
									<rect width="7" height="9" x="3" y="3" rx="1"></rect>
									<rect width="7" height="5" x="14" y="3" rx="1"></rect>
									<rect width="7" height="9" x="14" y="12" rx="1"></rect>
									<rect width="7" height="5" x="3" y="16" rx="1"></rect>
								</svg>
								<span>Overview</span>
							</a>
							<!--<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/dashboard/map">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map h-4 w-4" aria-hidden="true">
									<path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
									<path d="M15 5.764v15"></path>
									<path d="M9 3.236v15"></path>
								</svg>
								<span>Live Shipment Map</span>
							</a>--->
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.fleet.status') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity h-4 w-4" aria-hidden="true">
									<path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"></path>
								</svg>
								<span>Fleet Status</span>
							</a>
						</div>
					</div>
					<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Shipments</h2>
						<div class="space-y-1">
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.shipments.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-4 w-4" aria-hidden="true">
									<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
									<path d="M15 18H9"></path>
									<path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
									<circle cx="17" cy="18" r="2"></circle>
									<circle cx="7" cy="18" r="2"></circle>
								</svg>
								<span>All Shipments</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.shipment.track.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-navigation h-4 w-4" aria-hidden="true">
									<polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
								</svg>
								<span>Track Shipment</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.shipments.create') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-plus h-4 w-4" aria-hidden="true">
									<rect width="18" height="18" x="3" y="3" rx="2"></rect>
									<path d="M8 12h8"></path>
									<path d="M12 8v8"></path>
								</svg>
								<span>Create Shipment</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.delayed-shipments.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4" aria-hidden="true">
									<path d="M12 6v6l4 2"></path>
									<circle cx="12" cy="12" r="10"></circle>
								</svg>
								<span>Delayed Shipments</span>
							</a>


							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.schedule.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-4 w-4" aria-hidden="true">
									<path d="M8 2v4"></path>
									<path d="M16 2v4"></path>
									<rect width="18" height="18" x="3" y="4" rx="2"></rect>
									<path d="M3 10h18"></path>
								</svg>
								<span>Scheduled Deliveries</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.returns.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw h-4 w-4" aria-hidden="true">
									<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
									<path d="M3 3v5h5"></path>
								</svg>
								<span>Returns</span>
							</a>


							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.issues.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4" aria-hidden="true">
									<path d="M12 6v6l4 2"></path>
									<circle cx="12" cy="12" r="10"></circle>
								</svg>
								<span>Shipments Issues</span>
							</a>


							
						</div>
					</div>
					<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Fleet Management</h2>
						<div class="space-y-1">
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.vehicles.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bus h-4 w-4" aria-hidden="true">
									<path d="M8 6v6"></path>
									<path d="M15 6v6"></path>
									<path d="M2 12h19.6"></path>
									<path d="M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"></path>
									<circle cx="7" cy="18" r="2"></circle>
									<path d="M9 18h5"></path>
									<circle cx="16" cy="18" r="2"></circle>
								</svg>
								<span>Vehicle List</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.maintenance.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench h-4 w-4" aria-hidden="true">
									<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
								</svg>
								<span>Maintenance Logs</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.drivers.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-cog h-4 w-4" aria-hidden="true">
									<path d="M10 15H6a4 4 0 0 0-4 4v2"></path>
									<path d="m14.305 16.53.923-.382"></path>
									<path d="m15.228 13.852-.923-.383"></path>
									<path d="m16.852 12.228-.383-.923"></path>
									<path d="m16.852 17.772-.383.924"></path>
									<path d="m19.148 12.228.383-.923"></path>
									<path d="m19.53 18.696-.382-.924"></path>
									<path d="m20.772 13.852.924-.383"></path>
									<path d="m20.772 16.148.924.383"></path>
									<circle cx="18" cy="15" r="3"></circle>
									<circle cx="9" cy="7" r="4"></circle>
								</svg>
								<span>Driver Assignments</span>
							</a>
						</div>
					</div>
					<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Warehouses</h2>
						<div class="space-y-1">
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.warehouses.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-warehouse h-4 w-4" aria-hidden="true">
									<path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"></path>
									<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"></path>
									<path d="M6 13h12"></path>
									<path d="M6 17h12"></path>
								</svg>
								<span>Warehouse Locations</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.warehouse-inventory.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-boxes h-4 w-4" aria-hidden="true">
									<path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z"></path>
									<path d="m7 16.5-4.74-2.85"></path>
									<path d="m7 16.5 5-3"></path>
									<path d="M7 16.5v5.17"></path>
									<path d="M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z"></path>
									<path d="m17 16.5-5-3"></path>
									<path d="m17 16.5 4.74-2.85"></path>
									<path d="M17 16.5v5.17"></path>
									<path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z"></path>
									<path d="M12 8 7.26 5.15"></path>
									<path d="m12 8 4.74-2.85"></path>
									<path d="M12 13.5V8"></path>
								</svg>
								<span>Inventory Levels</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.warehouse.transfers.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-plus h-4 w-4" aria-hidden="true">
									<path d="M16 16h6"></path>
									<path d="M19 13v6"></path>
									<path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"></path>
									<path d="m7.5 4.27 9 5.15"></path>
									<polyline points="3.29 7 12 12 20.71 7"></polyline>
									<line x1="12" x2="12" y1="22" y2="12"></line>
								</svg>
								<span>Warehouse Transfer</span>
							</a>
						</div>
					</div>
					<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Clients</h2>
						<div class="space-y-1">
						
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/clients/feedback">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-messages-square h-4 w-4" aria-hidden="true">
									<path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2z"></path>
									<path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"></path>
								</svg>
								<span>Client Feedback</span>
							</a>
						</div>
					</div>

					<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Staffs</h2>
						<div class="space-y-1">
							
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.users.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4" aria-hidden="true">
									<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
									<path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
									<path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
									<circle cx="9" cy="7" r="4"></circle>
								</svg>
								<span>Staff List</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.users.create') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-messages-square h-4 w-4" aria-hidden="true">
									<path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2z"></path>
									<path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"></path>
								</svg>
								<span>Add Staff</span>
							</a>
						</div>
					</div>
				
					<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Reports</h2>
						<div class="space-y-1">
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.performance.show') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-no-axes-column-increasing h-4 w-4" aria-hidden="true">
									<line x1="12" x2="12" y1="20" y2="10"></line>
									<line x1="18" x2="18" y1="20" y2="4"></line>
									<line x1="6" x2="6" y1="20" y2="16"></line>
								</svg>
								<span>Delivery Performance</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/reports/revenue">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-line h-4 w-4" aria-hidden="true">
									<path d="M3 3v16a2 2 0 0 0 2 2h16"></path>
									<path d="m19 9-5 5-4-4-3 3"></path>
								</svg>
								<span>Revenue Analysis</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/reports/fleet">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-pie h-4 w-4" aria-hidden="true">
									<path d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z"></path>
									<path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
								</svg>
								<span>Fleet Efficiency</span>
							</a>
						</div>
					</div>
					<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">System Tools</h2>
						<div class="space-y-1">
							<a class="{{ request()->routeIs('settings.*') ? 'bg-gray-100 text-gray-900' : '' }} flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('settings.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings h-4 w-4" aria-hidden="true">
									<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
									<circle cx="12" cy="12" r="3"></circle>
								</svg>
								<span>Settings</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/settings/roles">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check h-4 w-4" aria-hidden="true">
									<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
									<path d="m9 12 2 2 4-4"></path>
								</svg>
								<span>Roles &amp; Permissions</span>
							</a>
							<!--<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/settings/notifications">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell h-4 w-4" aria-hidden="true">
									<path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
									<path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
								</svg>
								<span>Notifications Setup</span>
							</a>-->
						</div>
					</div>
					<div class="px-3 py-2">
						<h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Help &amp; Logs</h2>
						<div class="space-y-1">
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/help">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-life-buoy h-4 w-4" aria-hidden="true">
									<circle cx="12" cy="12" r="10"></circle>
									<path d="m4.93 4.93 4.24 4.24"></path>
									<path d="m14.83 9.17 4.24-4.24"></path>
									<path d="m14.83 14.83 4.24 4.24"></path>
									<path d="m9.17 14.83-4.24 4.24"></path>
									<circle cx="12" cy="12" r="4"></circle>
								</svg>
								<span>Help Center</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/contact">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-contact-round h-4 w-4" aria-hidden="true">
									<path d="M16 2v2"></path>
									<path d="M17.915 22a6 6 0 0 0-12 0"></path>
									<path d="M8 2v2"></path>
									<circle cx="12" cy="12" r="4"></circle>
									<rect x="3" y="4" width="18" height="18" rx="2"></rect>
								</svg>
								<span>Contact</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/email">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail h-4 w-4" aria-hidden="true">
									<path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
									<rect x="2" y="4" width="20" height="16" rx="2"></rect>
								</svg>
								<span>Email</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/chat">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4" aria-hidden="true">
									<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
								</svg>
								<span>Chat</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="/help/tickets">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket h-4 w-4" aria-hidden="true">
									<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
									<path d="M13 5v2"></path>
									<path d="M13 17v2"></path>
									<path d="M13 11v2"></path>
								</svg>
								<span>Support Tickets</span>
							</a>
							<a class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground text-foreground" href="{{ route('admin.activity-logs.index') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scroll h-4 w-4" aria-hidden="true">
									<path d="M19 17V5a2 2 0 0 0-2-2H4"></path>
									<path d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3"></path>
								</svg>
								<span>Audit Logs</span>
							</a>
						
						</div>
					</div>
				</div>
    </div>