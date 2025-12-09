@extends('admin.admin_dashboard')
@section('admin')

<div class="container mx-auto p-6" x-data="ordersManager()">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">All Orders</h1>
                <p class="text-gray-600">Manage and track all customer orders</p>
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <button @click="exportMenuOpen = !exportMenuOpen" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                            <path d="M12 15V3"></path>
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <path d="m7 10 5 5 5-5"></path>
                        </svg>
                        Export
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 ml-2">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </button>
                    <div x-show="exportMenuOpen" @click.away="exportMenuOpen = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                        <div class="py-1">
                            <a :href="`/admin/orders/export/csv?${getFilterParams()}`" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                Export as CSV
                            </a>
                            <a :href="`/admin/orders/export/excel?${getFilterParams()}`" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                Export as Excel
                            </a>
                            <a :href="`/admin/orders/export/pdf?${getFilterParams()}`" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                Export as PDF
                            </a>
                        </div>
                    </div>
                </div>
                <button @click="openCreateModal()" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-9 rounded-md px-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 mr-2">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    New Order
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5">
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium">Total Orders</h3>
                    <div class="p-2 bg-green-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold" x-text="stats.all || 0"></div>
                <p class="text-xs text-gray-600"><span class="text-green-600">+12%</span> from last month</p>
            </div>

            <div class="rounded-lg border bg-white shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium">In Transit</h3>
                    <div class="p-2 bg-blue-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                            <circle cx="7" cy="18" r="2"></circle>
                            <circle cx="17" cy="18" r="2"></circle>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold" x-text="stats.in_transit || 0"></div>
                <p class="text-xs text-gray-600"><span class="text-blue-600">+5%</span> from yesterday</p>
            </div>

            <div class="rounded-lg border bg-white shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium">Delivered</h3>
                    <div class="p-2 bg-green-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold" x-text="stats.delivered || 0"></div>
                <p class="text-xs text-gray-600"><span class="text-green-600">+8%</span> from last week</p>
            </div>

            <div class="rounded-lg border bg-white shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium">Delayed</h3>
                    <div class="p-2 bg-yellow-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold" x-text="stats.delayed || 0"></div>
                <p class="text-xs text-gray-600"><span class="text-red-600">-2%</span> from last week</p>
            </div>

            <div class="rounded-lg border bg-white shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium">Revenue</h3>
                    <div class="p-2 bg-orange-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-orange-600">
                            <line x1="12" y1="2" x2="12" y2="22"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold" x-text="'$' + (stats.revenue || 0).toFixed(2)"></div>
                <p class="text-xs text-gray-600"><span class="text-green-600">+15%</span> from last month</p>
            </div>
        </div>

        <!-- Order Management Table -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-2">Order Management</h3>
                <p class="text-sm text-gray-600 mb-4">Search, filter, and manage all orders</p>

                <!-- Filters -->
                <div class="flex flex-wrap gap-4 mb-4">
                    <div class="relative flex-1 min-w-[250px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" x-model="filters.search" @input.debounce.500ms="fetchOrders()" placeholder="Search orders..." class="w-full h-10 pl-10 pr-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <select x-model="filters.status" @change="fetchOrders()" class="h-10 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="assigned">Assigned</option>
                        <option value="in_transit">In Transit</option>
                        <option value="in_progress">In Progress</option>
                        <option value="delivered">Delivered</option>
                        <option value="completed">Completed</option>
                        <option value="delayed">Delayed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select x-model="filters.priority" @change="fetchOrders()" class="h-10 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Priority</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    <select x-model="filters.payment_status" @change="fetchOrders()" class="h-10 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Payment</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="refunded">Refunded</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>

                <!-- Tabs -->
                <div class="flex flex-wrap gap-2 mb-4 bg-gray-100 p-1 rounded-md">
                    <button @click="setTab('all')" :class="activeTab === 'all' ? 'bg-white shadow-sm' : 'hover:bg-white'" class="px-3 py-1.5 text-sm font-medium rounded">
                        All (<span x-text="stats.all || 0"></span>)
                    </button>
                    <button @click="setTab('processing')" :class="activeTab === 'processing' ? 'bg-white shadow-sm' : 'hover:bg-white'" class="px-3 py-1.5 text-sm font-medium rounded">
                        Processing (<span x-text="stats.processing || 0"></span>)
                    </button>
                    <button @click="setTab('in_transit')" :class="activeTab === 'in_transit' ? 'bg-white shadow-sm' : 'hover:bg-white'" class="px-3 py-1.5 text-sm font-medium rounded">
                        In Transit (<span x-text="stats.in_transit || 0"></span>)
                    </button>
                    <button @click="setTab('delivered')" :class="activeTab === 'delivered' ? 'bg-white shadow-sm' : 'hover:bg-white'" class="px-3 py-1.5 text-sm font-medium rounded">
                        Delivered (<span x-text="stats.delivered || 0"></span>)
                    </button>
                    <button @click="setTab('delayed')" :class="activeTab === 'delayed' ? 'bg-white shadow-sm' : 'hover:bg-white'" class="px-3 py-1.5 text-sm font-medium rounded">
                        Delayed (<span x-text="stats.delayed || 0"></span>)
                    </button>
                    <button @click="setTab('cancelled')" :class="activeTab === 'cancelled' ? 'bg-white shadow-sm' : 'hover:bg-white'" class="px-3 py-1.5 text-sm font-medium rounded">
                        Cancelled (<span x-text="stats.cancelled || 0"></span>)
                    </button>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
                </div>

                <!-- Table -->
                <div x-show="!loading" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b">
                            <tr>
                                <th class="text-left p-4 text-sm font-medium text-gray-600">Order ID</th>
                                <th class="text-left p-4 text-sm font-medium text-gray-600">Customer</th>
                                <th class="text-left p-4 text-sm font-medium text-gray-600">Items</th>
                                <th class="text-left p-4 text-sm font-medium text-gray-600">Total Value</th>
                                <th class="text-left p-4 text-sm font-medium text-gray-600">Status</th>
                                <th class="text-left p-4 text-sm font-medium text-gray-600">Priority</th>
                                <th class="text-left p-4 text-sm font-medium text-gray-600">Payment</th>
                                <th class="text-left p-4 text-sm font-medium text-gray-600">Delivery Date</th>
                                <th class="text-right p-4 text-sm font-medium text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="order in orders" :key="order.id">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium" x-text="order.order_number"></span>
                                            <button @click="copyToClipboard(order.order_number)" class="p-1 hover:bg-gray-200 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect width="14" height="14" x="8" y="8" rx="2"></rect>
                                                    <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm font-medium" x-text="getInitials(order.customer?.name || 'N/A')"></div>
                                            <div>
                                                <div class="font-medium" x-text="order.customer?.name || order.customer_email || 'N/A'"></div>
                                                <div class="text-sm text-gray-600" x-text="order.customer_email || ''"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-sm" x-html="getItemsPreview(order.items)"></div>
                                    </td>
                                    <td class="p-4 font-medium" x-text="'$' + parseFloat(order.total_amount || 0).toFixed(2)"></td>
                                    <td class="p-4">
                                        <span :class="getStatusClass(order.status)" class="px-2.5 py-0.5 text-xs font-semibold rounded-full" x-text="formatStatus(order.status)"></span>
                                    </td>
                                    <td class="p-4">
                                        <span :class="getPriorityClass(order.priority)" class="px-2.5 py-0.5 text-xs font-semibold rounded-full" x-text="formatText(order.priority)"></span>
                                    </td>
                                    <td class="p-4">
                                        <span :class="getPaymentClass(order.payment_status)" class="px-2.5 py-0.5 text-xs font-semibold rounded-full" x-text="formatText(order.payment_status)"></span>
                                    </td>
                                    <td class="p-4" x-text="formatDate(order.scheduled_date || order.order_date)"></td>
                                    <td class="p-4 text-right">
                                        <div class="relative inline-block" x-data="{ open: false }">
                                            <button @click="open = !open" class="p-2 hover:bg-gray-200 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="19" cy="12" r="1"></circle>
                                                    <circle cx="5" cy="12" r="1"></circle>
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                                <div class="py-1">
                                                    <a href="#" @click.prevent="viewOrder(order)" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                        View Details
                                                    </a>
                                                    <a href="#" @click.prevent="editOrder(order)" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                        </svg>
                                                        Edit Order
                                                    </a>
                                                    <a href="#" @click.prevent="cancelOrder(order)" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                        </svg>
                                                        Cancel Order
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="orders.length === 0">
                                <td colspan="9" class="p-8 text-center text-gray-500">
                                    No orders found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Order Modal -->
    <div x-show="createModalOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div @click.away="createModalOpen = false" class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b flex justify-between items-center sticky top-0 bg-white z-10">
                <div>
                    <h2 class="text-2xl font-bold">Create New Order</h2>
                    <p class="text-gray-600">Add a new order to the system</p>
                </div>
                <button @click="createModalOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitOrder()" class="p-6">
                <!-- Tabs -->
                <div class="flex gap-2 mb-6 border-b">
                    <button type="button" @click="currentTab = 0" :class="currentTab === 0 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600'" class="px-4 py-2 font-medium">Customer</button>
                    <button type="button" @click="currentTab = 1" :class="currentTab === 1 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600'" class="px-4 py-2 font-medium">Items</button>
                    <button type="button" @click="currentTab = 2" :class="currentTab === 2 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600'" class="px-4 py-2 font-medium">Shipping</button>
                    <button type="button" @click="currentTab = 3" :class="currentTab === 3 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600'" class="px-4 py-2 font-medium">Payment</button>
                </div>

                <!-- Tab Content 0: Customer -->
                <div x-show="currentTab === 0" class="space-y-4">
                    <h3 class="text-xl font-bold mb-4">Customer Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Customer *</label>
                            <select x-model="orderForm.customer_id" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                <option value="">Select Customer</option>
                                @foreach(\App\Models\User::where('role', 'customer')->get() as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Email Address</label>
                            <input type="email" x-model="orderForm.customer_email" placeholder="customer@email.com" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Phone Number</label>
                            <input type="tel" x-model="orderForm.customer_phone" placeholder="+1 (555) 123-4567" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Company</label>
                            <input type="text" x-model="orderForm.customer_company" placeholder="Company name (optional)" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>

                <!-- Tab Content 1: Items -->
                <div x-show="currentTab === 1" class="space-y-4">
                    <h3 class="text-xl font-bold mb-4">Order Items</h3>
                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="grid grid-cols-12 gap-2 items-end">
                                <div class="col-span-5">
                                    <label class="block text-sm font-medium mb-2">Item Name</label>
                                    <input type="text" x-model="item.name" required placeholder="Enter item name" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium mb-2">Quantity</label>
                                    <input type="number" x-model="item.quantity" @input="calculateTotal()" min="1" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium mb-2">Price</label>
                                    <input type="number" x-model="item.price" @input="calculateTotal()" step="0.01" min="0" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium mb-2">Total</label>
                                    <input type="text" :value="' + ((item.quantity || 0) * (item.price || 0)).toFixed(2)" disabled class="w-full h-10 px-3 border border-gray-300 rounded-md bg-gray-100">
                                </div>
                                <div class="col-span-1">
                                    <button type="button" @click="items.splice(index, 1); calculateTotal()" class="w-10 h-10 bg-red-500 text-white rounded-md hover:bg-red-600">+</button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="items.push({ name: '', quantity: 1, price: 0 })" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">
                        + Add Item
                    </button>
                    <div class="mt-6 space-y-2 border-t pt-4">
                        <div class="flex justify-between"><span>Subtotal:</span><span class="font-bold" x-text="' + (orderForm.order_value || 0).toFixed(2)"></span></div>
                        <div class="flex justify-between">
                            <span>Tax ($):</span>
                            <input type="number" x-model="orderForm.tax_amount" @input="calculateTotal()" step="0.01" min="0" class="w-32 h-8 px-2 border border-gray-300 rounded-md text-right">
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping ($):</span>
                            <input type="number" x-model="orderForm.shipping_cost" @input="calculateTotal()" step="0.01" min="0" class="w-32 h-8 px-2 border border-gray-300 rounded-md text-right">
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span>Total:</span>
                            <span x-text="' + (orderForm.total_amount || 0).toFixed(2)"></span>
                        </div>
                    </div>
                </div>

                <!-- Tab Content 2: Shipping -->
                <div x-show="currentTab === 2" class="space-y-4">
                    <h3 class="text-xl font-bold mb-4">Shipping Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Order Type *</label>
                            <select x-model="orderForm.order_type" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                <option value="delivery">Delivery</option>
                                <option value="pickup">Pickup</option>
                                <option value="return">Return</option>
                                <option value="exchange">Exchange</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Order Date *</label>
                            <input type="date" x-model="orderForm.order_date" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Street Address</label>
                            <input type="text" x-model="orderForm.street_address" placeholder="123 Main Street" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">City</label>
                            <input type="text" x-model="orderForm.city" placeholder="New York" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">State/Province</label>
                            <input type="text" x-model="orderForm.state" placeholder="NY" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">ZIP/Postal Code</label>
                            <input type="text" x-model="orderForm.zip_code" placeholder="10001" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Country</label>
                            <input type="text" x-model="orderForm.country" placeholder="United States" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Scheduled Delivery Date</label>
                            <input type="date" x-model="orderForm.scheduled_date" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium mb-2">Delivery Address</label>
                            <textarea x-model="orderForm.delivery_address" rows="2" placeholder="Full delivery address" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Shipping Method</label>
                            <select x-model="orderForm.shipping_method" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                <option value="">Select shipping method</option>
                                <option value="standard">Standard Shipping</option>
                                <option value="express">Express Shipping</option>
                                <option value="overnight">Overnight</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Priority *</label>
                            <select x-model="orderForm.priority" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tab Content 3: Payment -->
                <div x-show="currentTab === 3" class="space-y-4">
                    <h3 class="text-xl font-bold mb-4">Payment Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Payment Method *</label>
                            <select x-model="orderForm.payment_method" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                <option value="">Select payment method</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash on Delivery</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Payment Status *</label>
                                <select x-model="orderForm.payment_status" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="failed">Failed</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Payment Terms</label>
                                <select x-model="orderForm.payment_terms" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                    <option value="">Select terms</option>
                                    <option value="net_30">Net 30</option>
                                    <option value="net_60">Net 60</option>
                                    <option value="due_on_receipt">Due on receipt</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Assign Driver (Optional)</label>
                            <select x-model="orderForm.assigned_driver_id" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                                <option value="">No driver assigned</option>
                                @foreach(\App\Models\User::where('role', 'driver')->get() as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Special Instructions</label>
                            <textarea x-model="orderForm.special_instructions" placeholder="Any special delivery instructions or notes..." rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Internal Notes</label>
                            <textarea x-model="orderForm.internal_notes" placeholder="Internal notes for staff (not visible to customer)..." rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">Create Order</button>
                </div>
            </form>
        </div>
    </div>










	   <!-- View Order Details Modal -->
    <div x-show="viewModalOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div @click.away="viewModalOpen = false" class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b flex justify-between items-center sticky top-0 bg-white z-10">
                <div>
                    <h2 class="text-2xl font-bold" x-text="'Order Details - ' + (selectedOrder.order_number || '')"></h2>
                    <p class="text-gray-600">Complete information about this order</p>
                </div>
                <button @click="viewModalOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 px-6 pt-4 border-b">
                <button @click="viewTab = 'overview'" :class="viewTab === 'overview' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600'" class="px-4 py-2 font-medium">Overview</button>
                <button @click="viewTab = 'items'" :class="viewTab === 'items' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600'" class="px-4 py-2 font-medium">Items</button>
                <button @click="viewTab = 'shipping'" :class="viewTab === 'shipping' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600'" class="px-4 py-2 font-medium">Shipping</button>
                <button @click="viewTab = 'history'" :class="viewTab === 'history' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600'" class="px-4 py-2 font-medium">History</button>
            </div>

            <div class="p-6">
                <!-- Overview Tab -->
                <div x-show="viewTab === 'overview'" class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-xl font-bold mb-4">Customer Information</h3>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-lg font-medium" x-text="getInitials(selectedOrder.customer?.name || 'N/A')"></div>
                            <div>
                                <div class="font-semibold" x-text="selectedOrder.customer?.name || 'N/A'"></div>
                                <div class="text-sm text-gray-600" x-text="selectedOrder.customer_email || ''"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Date:</span>
                                <span class="font-medium" x-text="formatDate(selectedOrder.order_date)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery Date:</span>
                                <span class="font-medium" x-text="formatDate(selectedOrder.scheduled_date)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Value:</span>
                                <span class="font-medium" x-text="'$' + parseFloat(selectedOrder.total_amount || 0).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span :class="getStatusClass(selectedOrder.status)" class="px-2.5 py-0.5 text-xs font-semibold rounded-full" x-text="formatStatus(selectedOrder.status)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Tab -->
                <div x-show="viewTab === 'items'">
                    <h3 class="text-xl font-bold mb-4">Order Items</h3>
                    <div class="space-y-3">
                        <template x-for="item in parseItems(selectedOrder.items)" :key="item.name">
                            <div class="flex justify-between items-center p-4 border rounded-lg">
                                <div>
                                    <div class="font-semibold" x-text="item.name"></div>
                                    <div class="text-sm text-gray-600" x-text="'Quantity: ' + item.quantity"></div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold" x-text="'$' + parseFloat(item.price || 0).toFixed(2)"></div>
                                    <div class="text-sm text-gray-600">per item</div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="mt-6 pt-4 border-t">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span x-text="'$' + parseFloat(selectedOrder.total_amount || 0).toFixed(2)"></span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Tab -->
                <div x-show="viewTab === 'shipping'">
                    <h3 class="text-xl font-bold mb-4">Shipping Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Shipping Address</label>
                            <p class="text-gray-900" x-text="selectedOrder.delivery_address || 'N/A'"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tracking Number</label>
                            <p class="text-gray-900" x-text="selectedOrder.tracking_number || 'TRK123456789'"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Delivery Progress</label>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                                <div class="bg-gray-900 h-2.5 rounded-full" :style="'width: ' + (selectedOrder.delivery_progress || 30) + '%'"></div>
                            </div>
                            <p class="text-sm text-gray-600" x-text="selectedOrder.status === 'processing' ? 'Package being processed' : 'In transit'"></p>
                        </div>
                    </div>
                </div>

                <!-- History Tab -->
                <div x-show="viewTab === 'history'">
                    <h3 class="text-xl font-bold mb-4">Order History</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <div class="font-semibold">Order Placed</div>
                                <div class="text-sm text-gray-600" x-text="formatDate(selectedOrder.order_date) + ' - Order was successfully placed'"></div>
                            </div>
                        </div>
                        <div class="flex gap-3 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                    <rect width="14" height="14" x="8" y="8" rx="2"></rect>
                                    <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <div class="font-semibold" x-text="formatStatus(selectedOrder.status)"></div>
                                <div class="text-sm text-gray-600">Order is being prepared for shipment</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Edit Order Modal -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div @click.away="editModalOpen = false" class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b flex justify-between items-center sticky top-0 bg-white z-10">
                <div>
                    <h2 class="text-2xl font-bold">Edit Order</h2>
                    <p class="text-gray-600" x-text="'Order: ' + (editForm.order_number || '')"></p>
                </div>
                <button @click="editModalOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="updateOrder()" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Status *</label>
                        <select x-model="editForm.status" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="assigned">Assigned</option>
                            <option value="in_transit">In Transit</option>
                            <option value="in_progress">In Progress</option>
                            <option value="delivered">Delivered</option>
                            <option value="completed">Completed</option>
                            <option value="delayed">Delayed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Priority *</label>
                        <select x-model="editForm.priority" required class="w-full h-10 px-3 border border-gray-300 rounded-md">
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Payment Status</label>
                        <select x-model="editForm.payment_status" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Delivery Progress (%)</label>
                        <input type="number" x-model="editForm.delivery_progress" min="0" max="100" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-2">Scheduled Date</label>
                        <input type="date" x-model="editForm.scheduled_date" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-2">Tracking Number</label>
                        <input type="text" x-model="editForm.tracking_number" placeholder="Enter tracking number" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-2">Assign Driver</label>
                        <select x-model="editForm.assigned_driver_id" class="w-full h-10 px-3 border border-gray-300 rounded-md">
                            <option value="">No driver assigned</option>
                            @foreach(\App\Models\User::where('role', 'driver')->get() as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-2">Internal Notes</label>
                        <textarea x-model="editForm.internal_notes" placeholder="Add any notes about this order..." rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>

<script>
function ordersManager() {
    return {
        orders: [],
        stats: {
            all: 0,
            processing: 0,
            in_transit: 0,
            delivered: 0,
            delayed: 0,
            cancelled: 0,
            revenue: 0
        },
        filters: {
            search: '',
            status: 'all',
            priority: 'all',
            payment_status: 'all'
        },
        activeTab: 'all',
        loading: false,
        exportMenuOpen: false,
        createModalOpen: false,
        editModalOpen: false,
        viewModalOpen: false,       
        currentTab: 0,
        viewTab: 'overview',          
        selectedOrder: {},                
        items: [{ name: '', quantity: 1, price: 0 }],
        orderForm: {},
        editForm: {},

        init() {
            this.resetForm();
            this.fetchStats();
            this.fetchOrders();
        },

        resetForm() {
            this.currentTab = 0;
            this.items = [{ name: '', quantity: 1, price: 0 }];
            this.orderForm = {
                customer_id: '',
                order_type: 'delivery',
                order_date: new Date().toISOString().split('T')[0],
                priority: 'medium',
                payment_status: 'pending',
                payment_method: '',
                order_value: 0,
                tax_amount: 0,
                shipping_cost: 0,
                total_amount: 0
            };
        },

        calculateTotal() {
            let subtotal = 0;
            this.items.forEach(item => {
                subtotal += (parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0);
            });
            
            this.orderForm.order_value = subtotal;
            const tax = parseFloat(this.orderForm.tax_amount) || 0;
            const shipping = parseFloat(this.orderForm.shipping_cost) || 0;
            this.orderForm.total_amount = subtotal + tax + shipping;
        },

        async fetchStats() {
            try {
                const response = await fetch('/admin/orders-statistics');
                const data = await response.json();
                this.stats = data;
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        },

        async fetchOrders() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.filters.search) params.append('search', this.filters.search);
                if (this.filters.status !== 'all') params.append('status', this.filters.status);
                if (this.filters.priority !== 'all') params.append('priority', this.filters.priority);
                if (this.filters.payment_status !== 'all') params.append('payment_status', this.filters.payment_status);
                
                const response = await fetch(`/admin/orders?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                this.orders = data.orders.data || [];
                this.loading = false;
            } catch (error) {
                console.error('Error fetching orders:', error);
                this.loading = false;
            }
        },

        setTab(tab) {
            this.activeTab = tab;
            this.filters.status = tab === 'all' ? 'all' : tab;
            this.fetchOrders();
        },

        getFilterParams() {
            const params = new URLSearchParams();
            if (this.filters.search) params.append('search', this.filters.search);
            if (this.filters.status !== 'all') params.append('status', this.filters.status);
            if (this.filters.priority !== 'all') params.append('priority', this.filters.priority);
            if (this.filters.payment_status !== 'all') params.append('payment_status', this.filters.payment_status);
            return params.toString();
        },

        openCreateModal() {
            this.resetForm();
            this.createModalOpen = true;
        },

        async submitOrder() {
            if (this.items.length === 0) {
                alert('Please add at least one item');
                return;
            }

            const addressParts = [
                this.orderForm.street_address,
                this.orderForm.city,
                this.orderForm.state,
                this.orderForm.zip_code,
                this.orderForm.country
            ].filter(Boolean);
            
            this.orderForm.delivery_address = addressParts.join(', ');
            this.orderForm.items = this.items;

            try {
                const response = await fetch('/admin/orders/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.orderForm)
                });

                const data = await response.json();

                if (data.success) {
                    alert('Order created successfully!');
                    this.createModalOpen = false;
                    this.fetchOrders();
                    this.fetchStats();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create order'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error creating order');
            }
        },

       


		viewOrder(order) {
            this.selectedOrder = order;
            this.viewTab = 'overview';
            this.viewModalOpen = true;
        },

		

        editOrder(order) {
            this.editForm = {
                id: order.id,
                order_number: order.order_number,
                status: order.status,
                priority: order.priority,
                payment_status: order.payment_status,
                scheduled_date: order.scheduled_date,
                tracking_number: order.tracking_number || '',
                delivery_progress: order.delivery_progress || 0,
                assigned_driver_id: order.assigned_driver_id || '',
                internal_notes: order.internal_notes || ''
            };
            this.editModalOpen = true;
        },

        async updateOrder() {
            try {
                const response = await fetch(`/admin/orders/${this.editForm.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.editForm)
                });

                const data = await response.json();

                if (data.success) {
                    alert('Order updated successfully!');
                    this.editModalOpen = false;
                    this.fetchOrders();
                    this.fetchStats();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update order'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating order');
            }
        },

        async cancelOrder(order) {
            if (!confirm(`Are you sure you want to cancel order ${order.order_number}?`)) return;
            
            const reason = prompt('Please provide a cancellation reason:');
            if (!reason) return;

            try {
                const response = await fetch(`/admin/orders/${order.id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ cancellation_reason: reason })
                });

                if (response.ok) {
                    this.fetchOrders();
                    this.fetchStats();
                    alert('Order cancelled successfully!');
                }
            } catch (error) {
                console.error('Error cancelling order:', error);
                alert('Error cancelling order');
            }
        },

        getStatusClass(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'processing': 'bg-blue-500 text-white',
                'confirmed': 'bg-green-100 text-green-800',
                'assigned': 'bg-purple-100 text-purple-800',
                'in_transit': 'bg-blue-100 text-blue-800',
                'in_progress': 'bg-indigo-100 text-indigo-800',
                'delivered': 'bg-green-500 text-white',
                'completed': 'bg-green-600 text-white',
                'delayed': 'bg-orange-100 text-orange-800',
                'cancelled': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },

        getPriorityClass(priority) {
            const classes = {
                'high': 'bg-red-100 text-red-800',
                'medium': 'bg-yellow-100 text-yellow-800',
                'low': 'bg-green-100 text-green-800'
            };
            return classes[priority] || 'bg-gray-100 text-gray-800';
        },

        getPaymentClass(status) {
            const classes = {
                'paid': 'bg-green-100 text-green-800',
                'pending': 'bg-yellow-100 text-yellow-800',
                'refunded': 'bg-blue-100 text-blue-800',
                'failed': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },

        formatStatus(status) {
            return status ? status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : '';
        },

        formatText(text) {
            return text ? text.charAt(0).toUpperCase() + text.slice(1) : '';
        },

        formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        },

        getInitials(name) {
            if (!name) return 'NA';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
        },

        getItemsPreview(items) {
            if (!items) return '<div class="text-sm text-gray-500">No items</div>';
            
            try {
                const itemsArray = typeof items === 'string' ? JSON.parse(items) : items;
                if (!Array.isArray(itemsArray) || itemsArray.length === 0) {
                    return '<div class="text-sm text-gray-500">No items</div>';
                }
                
                const preview = itemsArray.slice(0, 2).map(item => 
                    `<div class="text-sm">${item.name || 'Item'} (x${item.quantity || 1})</div>`
                ).join('');
                
                if (itemsArray.length > 2) {
                    return preview + `<div class="text-sm text-gray-500">+${itemsArray.length - 2} more</div>`;
                }
                return preview;
            } catch (e) {
                return '<div class="text-sm text-gray-500">Invalid items</div>';
            }
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Order number copied to clipboard!');
            });
        },


		 parseItems(items) {
            if (!items) return [];
            try {
                const itemsArray = typeof items === 'string' ? JSON.parse(items) : items;
                return Array.isArray(itemsArray) ? itemsArray : [];
            } catch (e) {
                console.error('Error parsing items:', e);
                return [];
            }
        }
    }
}
</script>

<!-- Add Alpine.js CDN if not already included in your layout -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@endsection