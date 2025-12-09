@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

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
    .modal {
        display: none;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .modal.show {
        display: flex;
        opacity: 1;
    }
    .modal-content {
        transform: scale(0.95);
        transition: transform 0.3s;
    }
    .modal.show .modal-content {
        transform: scale(1);
    }
</style>

<div class="p-6 space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">User Management</h1>
            <p class="text-gray-600">Manage all system users and their roles</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button id="refreshBtn" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                    <path d="M21 3v5h-5"></path>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                    <path d="M8 16H3v5"></path>
                </svg>
                Refresh
            </button>
            
            <a href="{{ route('admin.users.export') }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export
            </a>
            
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                Add User
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-blue-600">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">Active: {{ $stats['active'] }}</div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Drivers</p>
                    <p class="text-2xl font-bold">{{ $stats['drivers'] }}</p>
                </div>
                <div class="p-2 bg-green-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-green-600">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">Available: {{ $stats['available_drivers'] }}</div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Customers</p>
                    <p class="text-2xl font-bold">{{ $stats['customers'] }}</p>
                </div>
                <div class="p-2 bg-purple-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-purple-600">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">All customers</div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Managers</p>
                    <p class="text-2xl font-bold">{{ $stats['managers'] }}</p>
                </div>
                <div class="p-2 bg-orange-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-orange-600">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">Management team</div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Admins</p>
                    <p class="text-2xl font-bold">{{ $stats['admins'] }}</p>
                </div>
                <div class="p-2 bg-red-50 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-red-600">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5"></path>
                        <path d="M2 12l10 5 10-5"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">System administrators</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border bg-white shadow-sm p-4 md:p-6">
        <form id="filterForm" method="GET" action="{{ route('admin.users.index') }}">
            <div class="flex flex-col gap-4">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="search" name="search" id="searchInput" value="{{ request('search') }}" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by name, email, phone, or employee ID..."/>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-2">
                    <!-- Role Filter -->
                    <select name="role" id="roleFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                        <option value="all">All Roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                            {{ ucwords($role) }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Status Filter -->
                    <select name="status" id="statusFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                        <option value="all">All Status</option>
                        @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Warehouse Filter -->
                    <select name="warehouse_id" id="warehouseFilter" class="flex h-10 items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Date From -->
                    <input type="date" name="date_from" id="dateFrom" value="{{ request('date_from') }}" class="flex h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm"/>

                    <!-- Date To -->
                    <input type="date" name="date_to" id="dateTo" value="{{ request('date_to') }}" class="flex h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm"/>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-4 md:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h3 class="text-xl sm:text-2xl font-semibold">Users List</h3>
                <div class="text-sm text-gray-600">{{ $users->total() }} users found</div>
            </div>
            <div class="flex items-center gap-2">
                <button id="bulkDeleteBtn" class="hidden inline-flex items-center justify-center text-sm font-medium border border-red-300 text-red-600 hover:bg-red-50 h-9 rounded-md px-3">
                    Delete Selected
                </button>
            </div>
        </div>
        
        <div class="p-4 md:p-6 pt-0">
            <div class="rounded-md border overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="h-12 px-4 text-left w-[40px]">
                                <input type="checkbox" id="selectAll" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                            </th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">User</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Contact</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Role</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600">Status</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 hidden lg:table-cell">Warehouse</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 hidden xl:table-cell">Joined</th>
                            <th class="h-12 px-4 text-left font-medium text-gray-600 w-[60px]"></th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50" data-user-id="{{ $user->id }}">
                            <td class="p-4">
                                <input type="checkbox" class="user-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $user->id }}"/>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                        @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->first_name }}" class="w-full h-full object-cover"/>
                                        @else
                                        <span class="text-sm font-semibold text-gray-600">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $user->first_name }} {{ $user->last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->user_name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="text-sm">
                                    <p class="text-gray-900">{{ $user->email }}</p>
                                    <p class="text-gray-500">{{ $user->phone }}</p>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $user->role === 'driver' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $user->role === 'customer' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $user->role === 'manager' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $user->role === 'dispatcher' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ ucwords($user->role) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                    {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $user->status === 'inactive' ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ $user->status === 'suspended' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $user->status === 'on_leave' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                    {{ ucwords(str_replace('_', ' ', $user->status)) }}
                                </span>
                            </td>
                            <td class="p-4 hidden lg:table-cell">
                                @if($user->assignedWarehouse)
                                <div class="text-sm">
                                    <p class="font-medium text-blue-600">{{ $user->assignedWarehouse->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->assignedWarehouse->warehouse_code }}</p>
                                </div>
                                @else
                                <span class="text-xs text-gray-400">Not assigned</span>
                                @endif
                            </td>
                            <td class="p-4 hidden xl:table-cell">
                                <div class="text-xs text-gray-600">
                                    {{ $user->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="relative">
                                    <button class="action-menu-btn hover:bg-gray-100 rounded-md p-2" data-user-id="{{ $user->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg>
                                    </button>
                                    <div class="action-dropdown dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                        <div class="py-1">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                View Details
                                            </a>
                                            
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                </svg>
                                                Edit User
                                            </a>

                                            <button class="assign-warehouse-btn flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-user-id="{{ $user->id }}" data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                                                    <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                                                </svg>
                                                Assign Warehouse
                                            </button>

                                            <button class="reset-password-btn flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" data-user-id="{{ $user->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                </svg>
                                                Reset Password
                                            </button>
                                            
                                            @if($user->id !== auth()->id())
                                            <div class="border-t border-gray-200 my-1"></div>
                                            <button class="delete-user-btn flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left" data-user-id="{{ $user->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                                Delete User
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-500">
                                No users found. Try adjusting your filters or create a new user.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Reset Password</h2>
            <p class="text-sm text-gray-600 mt-1">Set a new password for this user</p>
        </div>
        <form id="resetPasswordForm">
            @csrf
            <input type="hidden" id="reset_user_id" name="user_id">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">New Password *</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" id="cancelResetBtn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Reset Password</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign Warehouse Modal -->
<div id="assignWarehouseModal" class="modal fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Assign Warehouse</h2>
            <p class="text-sm text-gray-600 mt-1">Assign a warehouse to <span id="assign_user_name" class="font-semibold"></span></p>
        </div>
        <form id="assignWarehouseForm">
            @csrf
            <input type="hidden" id="assign_user_id" name="user_id">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Select Warehouse *</label>
                    <select name="assigned_warehouse_id" id="warehouse_select" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose warehouse</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->warehouse_code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" id="cancelAssignBtn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Assign Warehouse</button>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// Dropdown handlers
document.querySelectorAll('.action-menu-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdown = btn.nextElementSibling;
        
        document.querySelectorAll('.action-dropdown').forEach(d => {
            if (d !== dropdown) d.classList.remove('show');
        });
        
        dropdown.classList.toggle('show');
    });
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
        document.querySelectorAll('.dropdown-menu').forEach(d => {
            d.classList.remove('show');
        });
    }
});

// Checkbox functionality
const selectAllCheckbox = document.getElementById('selectAll');
const userCheckboxes = document.querySelectorAll('.user-checkbox');
const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

selectAllCheckbox?.addEventListener('change', function() {
    userCheckboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkActions();
});

userCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allChecked = Array.from(userCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(userCheckboxes).some(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
        toggleBulkActions();
    });
});

function toggleBulkActions() {
    const anyChecked = Array.from(userCheckboxes).some(cb => cb.checked);
    if (anyChecked) {
        bulkDeleteBtn.classList.remove('hidden');
    } else {
        bulkDeleteBtn.classList.add('hidden');
    }
}

// Bulk delete
bulkDeleteBtn?.addEventListener('click', function() {
    const selectedIds = Array.from(userCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    
    if (selectedIds.length === 0) return;
    
    if (confirm(`Are you sure you want to delete ${selectedIds.length} user(s)?`)) {
        fetch('{{ route("admin.users.bulk.delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ ids: selectedIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
});

// Refresh button
document.getElementById('refreshBtn')?.addEventListener('click', () => {
    location.reload();
});

// Filter form auto-submit
document.querySelectorAll('#roleFilter, #statusFilter, #warehouseFilter, #dateFrom, #dateTo').forEach(select => {
    select.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Search with debounce
let searchTimeout;
document.getElementById('searchInput')?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});

// Reset Password Modal
document.querySelectorAll('.reset-password-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const userId = this.dataset.userId;
        document.getElementById('reset_user_id').value = userId;
        openModal('resetPasswordModal');
        document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
    });
});

document.getElementById('cancelResetBtn')?.addEventListener('click', () => {
    closeModal('resetPasswordModal');
});


// Assign Warehouse Modal
document.querySelectorAll('.assign-warehouse-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const userId = this.dataset.userId;
        const userName = this.dataset.userName;
        document.getElementById('assign_user_id').value = userId;
        document.getElementById('assign_user_name').textContent = userName;
        openModal('assignWarehouseModal');
        document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
    });
});

document.getElementById('cancelAssignBtn')?.addEventListener('click', () => {
    closeModal('assignWarehouseModal');
});

// Replace the assignWarehouseForm event listener in your blade file with this:

document.getElementById('assignWarehouseForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const userId = document.getElementById('assign_user_id').value;
    const formData = new FormData(this);
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    // FIXED: Use the correct route path
    fetch(`/admin/users/${userId}/assign-warehouse`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while assigning the warehouse');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Also fix the resetPasswordForm event listener:

document.getElementById('resetPasswordForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const userId = document.getElementById('reset_user_id').value;
    const formData = new FormData(this);
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    // FIXED: Use the correct route path
    fetch(`/admin/users/${userId}/reset-password`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('resetPasswordModal');
            alert('Password reset successfully');
            this.reset();
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        } else {
            alert('Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while resetting the password');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Also fix the delete user button:

document.querySelectorAll('.delete-user-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const userId = this.dataset.userId;
        
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            // FIXED: Use the correct route path
            fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the user');
            });
        }
        document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
    });
});

// Close modals when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    });
});

// Auto-hide success/error messages
setTimeout(() => {
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>

@endsection