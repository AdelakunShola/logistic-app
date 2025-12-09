  <meta name="csrf-token" content="{{ csrf_token() }}">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

<style>
    .dropdown-menu {
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.2s, transform 0.2s;
        }
        .dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .overlay {
            display: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .overlay.show {
            display: block;
            opacity: 1;
        }
    </style>
   
 


<header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b bg-white px-4 md:px-6">
        <div class="flex items-center gap-2">
            <button id="toggleSidebar" class="items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 hover:bg-gray-100 h-10 w-10 flex lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M4 12h16"></path>
                    <path d="M4 18h16"></path>
                    <path d="M4 6h16"></path>
                </svg>
                <span class="sr-only">Toggle sidebar</span>
            </button>
            <div class="hidden sm:block md:w-[350px]">
                <div class="relative w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500">
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                    <input class="flex h-10 rounded-md border border-gray-300 px-3 py-2 text-sm w-full bg-white pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search shipments, clients, orders..." type="search">
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-2 md:gap-4">
            <!-- Theme Toggle -->
            <button class="inline-flex items-center justify-center text-sm font-medium transition-colors hover:bg-gray-100 h-8 w-8 p-2 bg-blue-50 rounded-full" aria-label="Switch to dark theme">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <circle cx="12" cy="12" r="4"></circle>
                    <path d="M12 2v2"></path>
                    <path d="M12 20v2"></path>
                    <path d="m4.93 4.93 1.41 1.41"></path>
                    <path d="m17.66 17.66 1.41 1.41"></path>
                    <path d="M2 12h2"></path>
                    <path d="M20 12h2"></path>
                    <path d="m6.34 17.66-1.41 1.41"></path>
                    <path d="m19.07 4.93-1.41 1.41"></path>
                </svg>
            </button>
            
          
        
  <div class="relative" 
     x-data="{ 
         isOpen: false, 
         notifications: [], 
         unreadCount: 0
     }"
     x-init="
         fetch('/admin/notifications/unread-count', {
             headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
         }).then(r => r.json()).then(d => unreadCount = d.count || 0);
         
         setInterval(() => {
             fetch('/admin/notifications/unread-count', {
                 headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
             }).then(r => r.json()).then(d => unreadCount = d.count || 0);
         }, 30000);
     ">
    
    <!-- Notification Button -->
    <button 
        @click="isOpen = !isOpen; if(isOpen) {
            fetch('/admin/notifications/dropdown', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            }).then(r => r.json()).then(d => notifications = d.notifications || []);
        }"
        type="button"
        class="inline-flex items-center justify-center text-sm font-medium transition-colors hover:bg-gray-100 h-8 w-8 relative p-2 bg-blue-50 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-blue-600">
            <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
            <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
        </svg>
        <span 
            x-show="unreadCount > 0" 
            x-text="unreadCount"
            class="absolute -right-1 -top-1 h-4 w-4 rounded-full bg-red-500 text-white flex items-center justify-center text-[10px] font-semibold">
        </span>
    </button>
    
    <!-- Dropdown Menu -->
    <div 
        x-show="isOpen" 
        @click.away="isOpen = false"
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50"
        style="display: none;">
         
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <h3 class="font-semibold text-gray-900">Notifications</h3>
            <button 
                @click="
                    fetch('/admin/notifications/mark-all-as-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\\'csrf-token\\']').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()).then(d => {
                        if(d.success) {
                            unreadCount = 0;
                            fetch('/admin/notifications/dropdown', {
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                            }).then(r => r.json()).then(d => notifications = d.notifications || []);
                        }
                    });
                " 
                x-show="unreadCount > 0"
                class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                Mark all as read
            </button>
        </div>
        
        <!-- Notifications List -->
        <div class="divide-y divide-gray-100 overflow-y-auto" style="max-height: 400px;">
            <!-- Empty State -->
            <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto mb-2 text-gray-300">
                    <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
                    <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
                </svg>
                <p class="text-sm">No notifications</p>
            </div>
            
            <!-- Notification Items - NOW LINKS TO DETAILS PAGE -->
            <template x-for="notif in notifications" :key="notif.id">
                <a 
                    :href="'/admin/notifications/' + notif.id + '/view'"
                    class="block px-4 py-3 hover:bg-gray-50 transition-colors" 
                    :class="{ 'bg-blue-50/30': !notif.is_read }">
                    <div class="flex gap-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium" :class="notif.is_read ? 'text-gray-700' : 'text-gray-900'" x-text="notif.title"></p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2" x-text="notif.message"></p>
                            <p class="text-xs mt-1" :class="notif.is_read ? 'text-gray-400' : 'text-blue-600'" x-text="notif.time_ago"></p>
                        </div>
                        
                        <!-- Unread Indicator -->
                        <div class="flex-shrink-0 pt-1">
                            <span x-show="!notif.is_read" class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                        </div>
                    </div>
                </a>
            </template>
        </div>
        
        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 text-center bg-gray-50">
            <a href="{{ route('admin.notifications.all') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all notifications</a>
        </div>
    </div>
</div>


            <!-- User Dropdown -->
            <div class="relative">
                <button id="userMenuButton" class="inline-flex items-center justify-center text-sm font-medium transition-colors hover:bg-gray-100 h-8 w-8 p-2 bg-blue-50 rounded-full" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-blue-600">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="userDropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200">
                    <div class="py-1">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Profile</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <span>Inbox</span>
                        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <a href="{{ route('admin.logout') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>


<script>
// User Dropdown Toggle
const userMenuButton = document.getElementById('userMenuButton');
const userDropdown = document.getElementById('userDropdown');

if (userMenuButton && userDropdown) {
    userMenuButton.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdown.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
        if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('show');
        }
    });
}

// Sidebar Toggle
const toggleSidebar = document.getElementById('toggleSidebar');
const closeSidebar = document.getElementById('closeSidebar');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

if (toggleSidebar) {
    toggleSidebar.addEventListener('click', () => {
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    });
}

if (closeSidebar) {
    closeSidebar.addEventListener('click', () => {
        if (sidebar && sidebarOverlay) {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    });
}

if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
        if (sidebar && sidebarOverlay) {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    });
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && sidebar && sidebarOverlay) {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }
});
</script>