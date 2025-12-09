@extends('admin.admin_dashboard')
@section('admin')

<div id="returns-management">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Returns Management</h1>
                <p class="text-muted-foreground">Process and track product returns and refunds</p>
            </div>
            <div class="flex gap-2">
                <!-- Export Dropdown -->
                <div class="relative" id="exportDropdown">
                    <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3" type="button" id="exportBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download h-4 w-4 mr-2">
                            <path d="M12 15V3"></path>
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <path d="m7 10 5 5 5-5"></path>
                        </svg>
                        Export
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 ml-2">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="exportMenu" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden">
                        <div class="py-1">
                            <a href="{{ route('admin.returns.export', 'csv') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-4 w-4 mr-2">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                    <path d="M10 9H8"></path>
                                    <path d="M16 13H8"></path>
                                    <path d="M16 17H8"></path>
                                </svg>
                                Export as CSV
                            </a>
                            <a href="{{ route('admin.returns.export', 'excel') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-spreadsheet h-4 w-4 mr-2">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                    <path d="M8 13h2"></path>
                                    <path d="M14 13h2"></path>
                                    <path d="M8 17h2"></path>
                                    <path d="M14 17h2"></path>
                                </svg>
                                Export as Excel
                            </a>
                            <a href="{{ route('admin.returns.export', 'pdf') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-4 w-4 mr-2">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                </svg>
                                Export as PDF
                            </a>
                            <hr class="my-1">
                            <a href="javascript:void(0)" onclick="window.print()" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer h-4 w-4 mr-2">
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                    <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"></path>
                                    <rect x="6" y="14" width="12" height="8" rx="1"></rect>
                                </svg>
                                Print
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Refresh Button -->
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw h-4 w-4 mr-2">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                        <path d="M21 3v5h-5"></path>
                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                        <path d="M8 16H3v5"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            <!-- Total Returns -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Total Returns</h3>
                    <div class="p-2 bg-blue-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package h-4 w-4 text-blue-500">
                            <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                            <path d="M12 22V12"></path>
                            <polyline points="3.29 7 12 12 20.71 7"></polyline>
                            <path d="m7.5 4.27 9 5.15"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                    <p class="text-xs text-muted-foreground">
                        {{ $stats['growth'] > 0 ? '+' : '' }}{{ $stats['growth'] }}% from last month
                    </p>
                </div>
            </div>

            <!-- Pending Review -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Pending Review</h3>
                    <div class="p-2 bg-yellow-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4 text-yellow-500">
                            <path d="M12 6v6l4 2"></path>
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['pending'] }}</div>
                    <p class="text-xs text-muted-foreground">Requires attention</p>
                </div>
            </div>

            <!-- Processing -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Processing</h3>
                    <div class="p-2 bg-green-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw h-4 w-4 text-green-500">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                            <path d="M21 3v5h-5"></path>
                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                            <path d="M8 16H3v5"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['processing'] }}</div>
                    <p class="text-xs text-muted-foreground">In progress</p>
                </div>
            </div>

            <!-- Total Value -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-4 md:p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="sm:text-2xl tracking-tight text-sm font-medium">Total Value</h3>
                    <div class="p-2 bg-blue-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign h-4 w-4 text-blue-500">
                            <line x1="12" x2="12" y1="2" y2="22"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-4 md:p-6 pt-0">
                    <div class="text-2xl font-bold">${{ number_format($stats['total_value'], 2) }}</div>
                    <p class="text-xs text-muted-foreground">Potential refunds</p>
                </div>
            </div>
        </div>

        <!-- Returns List Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight">Returns List</h3>
                <div class="text-sm text-muted-foreground">View and manage all return requests</div>
            </div>
            
            <div class="p-4 md:p-6 pt-0">
                <!-- Search and Filter -->
                <div class="flex flex-col gap-4 md:flex-row md:items-center mb-6">
                    <!-- Search -->
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2 top-2.5 h-4 w-4 text-muted-foreground">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                        <input type="text" id="searchInput" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8" placeholder="Search by return ID, order ID, or customer..." value="{{ request('search') }}">
                    </div>
                    
                    <!-- Status Filter -->
                    <select id="statusFilter" class="flex h-10 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-full md:w-[180px]">
                        <option value="">All Status</option>
                        <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Returns Table -->
                <div class="rounded-md border">
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="border-b bg-muted/50">
                                    <th class="p-4 text-left font-medium">Return ID</th>
                                    <th class="p-4 text-left font-medium">Customer</th>
                                    <th class="p-4 text-left font-medium">Items</th>
                                    <th class="p-4 text-left font-medium">Reason</th>
                                    <th class="p-4 text-left font-medium">Status</th>
                                    <th class="p-4 text-left font-medium">Value</th>
                                    <th class="p-4 text-left font-medium">Date</th>
                                    <th class="p-4 text-left font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returns as $return)
                                <tr class="border-b hover:bg-muted/50 cursor-pointer" onclick="openReturnDetails({{ $return->id }})">
                                    <td class="p-4">
                                        <div class="font-medium">{{ $return->return_number }}</div>
                                        <div class="text-sm text-muted-foreground">{{ $return->order_number ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-medium">{{ $return->customer->first_name }} {{ $return->customer->last_name }}</div>
                                        <div class="text-sm text-muted-foreground">{{ $return->customer->email }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-sm">{{ $return->items_list }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span class="text-sm">{{ $return->formatted_return_reason }}</span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            @if($return->status == 'pending_review')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4">
                                                <path d="M12 6v6l4 2"></path>
                                                <circle cx="12" cy="12" r="10"></circle>
                                            </svg>
                                            @elseif($return->status == 'approved' || $return->status == 'completed')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big h-4 w-4">
                                                <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                                                <path d="m9 11 3 3L22 4"></path>
                                            </svg>
                                            @elseif($return->status == 'processing')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw h-4 w-4">
                                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                                <path d="M21 3v5h-5"></path>
                                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                                <path d="M8 16H3v5"></path>
                                            </svg>
                                            @elseif($return->status == 'rejected')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x h-4 w-4">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="m15 9-6 6"></path>
                                                <path d="m9 9 6 6"></path>
                                            </svg>
                                            @endif
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                                {{ $return->status_badge['text'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-4">
    <span class="font-medium">${{ number_format($return->refund_amount ?? $return->refund_amount ?? 0, 2) }}</span>
</td>
                                    <td class="p-4">
                                        <span class="text-sm">{{ $return->request_date->format('Y-m-d') }}</span>
                                    </td>
                                    <td class="p-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-4 w-4">
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-muted-foreground">
                                        No returns found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($returns->hasPages())
                <div class="mt-6">
                    {{ $returns->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Return Details Modal -->
<div id="returnDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b">
            <div>
                <h2 class="text-2xl font-bold" id="modalTitle">Return Details - RET-001</h2>
                <p class="text-sm text-muted-foreground">Review and process return request</p>
            </div>
            <button onclick="closeReturnModal()" class="p-2 hover:bg-gray-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-5 w-5">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Tabs -->
        <div class="border-b">
            <div class="flex gap-2 px-6">
                <button onclick="switchTab('details')" class="tab-button px-4 py-3 text-sm font-medium border-b-2 border-transparent hover:border-gray-300" data-tab="details">Details</button>
                <button onclick="switchTab('items')" class="tab-button px-4 py-3 text-sm font-medium border-b-2 border-transparent hover:border-gray-300" data-tab="items">Items</button>
                <button onclick="switchTab('customer')" class="tab-button px-4 py-3 text-sm font-medium border-b-2 border-transparent hover:border-gray-300" data-tab="customer">Customer</button>
                <button onclick="switchTab('actions')" class="tab-button px-4 py-3 text-sm font-medium border-b-2 border-transparent hover:border-gray-300 active-tab" data-tab="actions">Actions</button>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="flex-1 overflow-y-auto p-6" id="modalContent">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Export Dropdown
document.getElementById('exportBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    const menu = document.getElementById('exportMenu');
    menu.classList.toggle('hidden');
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('exportDropdown');
    const menu = document.getElementById('exportMenu');
    if (!dropdown.contains(e.target)) {
        menu.classList.add('hidden');
    }
});

// Search functionality
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function() {
    applyFilters();
});

function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    
    const url = new URL(window.location.href);
    url.searchParams.set('search', search);
    url.searchParams.set('status', status);
    
    window.location.href = url.toString();
}

// Open return details modal
function openReturnDetails(returnId) {
    const modal = document.getElementById('returnDetailsModal');
    modal.classList.remove('hidden');
    
    // Load return details via AJAX
    fetch(`/admin/returns/${returnId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadReturnData(data.return);
                switchTab('actions'); // Show actions tab by default
            }
        })
        .catch(error => {
            console.error('Error loading return details:', error);
        });
}

// Close modal
function closeReturnModal() {
    document.getElementById('returnDetailsModal').classList.add('hidden');
}

// Switch tabs
function switchTab(tabName) {
    // Update active tab styling
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active-tab', 'border-primary', 'text-primary');
        btn.classList.add('border-transparent');
    });
    
    const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
    activeBtn.classList.add('active-tab', 'border-primary', 'text-primary');
    activeBtn.classList.remove('border-transparent');
    
    // Load tab content
    loadTabContent(tabName);
}

let currentReturnData = null;

function loadReturnData(returnData) {
    currentReturnData = returnData;
    document.getElementById('modalTitle').textContent = `Return Details - ${returnData.return_number}`;
}

function loadTabContent(tabName) {
    if (!currentReturnData) return;
    
    const content = document.getElementById('modalContent');
    
    switch(tabName) {
        case 'details':
            content.innerHTML = getDetailsTabContent(currentReturnData);
            break;
        case 'items':
            content.innerHTML = getItemsTabContent(currentReturnData);
            break;
        case 'customer':
            content.innerHTML = getCustomerTabContent(currentReturnData);
            break;
        case 'actions':
            content.innerHTML = getActionsTabContent(currentReturnData);
            break;
    }
}

function getDetailsTabContent(data) {
    return `
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-2">Return ID</h3>
                <p>${data.return_number}</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Order ID</h3>
                <p>${data.order_number || 'N/A'}</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Status</h3>
                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold">${data.status_text}</span>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Request Date</h3>
                <p>${data.request_date}</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Warehouse</h3>
                <p>${data.warehouse || 'Main Warehouse'}</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Tracking Number</h3>
                <p>${data.tracking_number || 'N/A'}</p>
            </div>
            <div class="col-span-2">
                <h3 class="font-semibold mb-2">Return Reason</h3>
                <div class="bg-muted p-4 rounded-md">
                    <p class="font-medium">${data.return_reason_text}</p>
                    <p class="text-sm text-muted-foreground mt-2">${data.description || 'No additional details provided'}</p>
                </div>
            </div>
            ${data.attached_images && data.attached_images.length > 0 ? `
            <div class="col-span-2">
                <h3 class="font-semibold mb-2">Attached Images</h3>
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image h-4 w-4">
                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                        <circle cx="9" cy="9" r="2"></circle>
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                    </svg>
                    <span class="text-sm">${data.attached_images.length} image(s) uploaded by customer</span>
                </div>
            </div>
            ` : ''}
        </div>
    `;
}

function getItemsTabContent(data) {
    let itemsHtml = '';
    if (data.items && data.items.length > 0) {
        data.items.forEach(item => {
            itemsHtml += `
                <div class="p-4 border rounded-md">
                    <div class="font-medium">${item.description || item.name}</div>
                    <div class="text-sm text-muted-foreground">SKU: ${item.sku || 'N/A'}</div>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="text-sm">Quantity: ${item.quantity || 1}</span>
                        <span class="font-medium">Returning</span>
                    </div>
                </div>
            `;
        });
    } else {
        itemsHtml = '<p class="text-muted-foreground">No items information available</p>';
    }
    
    return `
        <div class="space-y-4">
            ${itemsHtml}
            <div class="mt-6 p-4 bg-muted rounded-md flex justify-between items-center">
                <span class="text-lg font-semibold">Total Return Value</span>
                <span class="text-2xl font-bold">${data.refund_amount}</span>
            </div>
        </div>
    `;
}

function getCustomerTabContent(data) {
    return `
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-2">Customer Name</h3>
                <p>${data.customer_name}</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Email</h3>
                <p>${data.customer_email}</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Order History</h3>
                <p class="text-sm text-muted-foreground">${data.customer_order_count} orders (${data.customer_return_count} previous returns)</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Customer Since</h3>
                <p>${data.customer_since}</p>
            </div>
        </div>
    `;
}

function getActionsTabContent(data) {
    return `
        <div class="space-y-6">
            ${data.status === 'pending_review' ? `
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle h-5 w-5 text-yellow-600 mt-0.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" x2="12" y1="8" y2="12"></line>
                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                </svg>
                <div>
                    <h4 class="font-semibold text-yellow-900">Action Required</h4>
                    <p class="text-sm text-yellow-700">This return request needs to be reviewed and approved or rejected.</p>
                </div>
            </div>
            ` : ''}
            
            <div>
                <h3 class="font-semibold mb-3">Internal Notes</h3>
                <textarea id="internalNotes" class="flex min-h-[120px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Add notes about this return...">${data.internal_notes || ''}</textarea>
            </div>
            
            <div class="flex gap-3">
                ${data.status === 'pending_review' ? `
                <button onclick="approveReturn(${data.id})" class="flex-1 inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-11 rounded-md px-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle h-4 w-4 mr-2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <path d="m9 11 3 3L22 4"></path>
                    </svg>
                    Approve Return
                </button>
                <button onclick="rejectReturn(${data.id})" class="flex-1 inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-destructive text-destructive-foreground hover:bg-destructive/90 h-11 rounded-md px-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle h-4 w-4 mr-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m15 9-6 6"></path>
                        <path d="m9 9 6 6"></path>
                    </svg>
                    Reject Return
                </button>
                ` : ''}
            </div>
            
            <hr>
            
            <button onclick="contactCustomer(${data.id})" class="w-full inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 rounded-md px-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-2">
                    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                </svg>
                Contact Customer
            </button>
        </div>
    `;
}

// Action functions
function approveReturn(returnId) {
    if (!confirm('Are you sure you want to approve this return?')) return;
    
    const notes = document.getElementById('internalNotes').value;
    
    fetch(`/admin/returns/${returnId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Return approved successfully!');
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function rejectReturn(returnId) {
    const reason = prompt('Please provide a reason for rejection:');
    if (!reason) return;
    
    fetch(`/admin/returns/${returnId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Return rejected');
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function contactCustomer(returnId) {
    alert('Contact customer functionality - implement email/SMS interface');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReturnModal();
    }
});

// Initialize active tab styling
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = document.querySelector('[data-tab="actions"]');
    if (activeTab) {
        activeTab.classList.add('active-tab', 'border-primary', 'text-primary');
    }
});
</script>

<style>
.active-tab {
    border-bottom-color: hsl(var(--primary)) !important;
    color: hsl(var(--primary)) !important;
}
</style>

@endsection