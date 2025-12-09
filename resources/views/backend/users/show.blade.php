@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .info-card {
        transition: transform 0.2s;
    }
    .info-card:hover {
        transform: translateY(-2px);
    }
</style>

<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold tracking-tight">User Details</h1>
            </div>
            <p class="text-gray-600">Complete information about {{ $user->first_name }} {{ $user->last_name }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Status Badge -->
            <span class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-medium
                {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                {{ $user->status === 'inactive' ? 'bg-gray-100 text-gray-700' : '' }}
                {{ $user->status === 'suspended' ? 'bg-red-100 text-red-700' : '' }}
                {{ $user->status === 'on_leave' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                {{ ucwords(str_replace('_', ' ', $user->status)) }}
            </span>

            <!-- Action Buttons -->
            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center justify-center text-sm font-medium border border-blue-600 text-blue-600 hover:bg-blue-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                </svg>
                Edit User
            </a>

            @if($user->id !== auth()->id())
            <button id="deleteUserBtn" class="inline-flex items-center justify-center text-sm font-medium border border-red-600 text-red-600 hover:bg-red-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                </svg>
                Delete
            </button>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden mb-4">
                        @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->first_name }}" class="w-full h-full object-cover"/>
                        @else
                        <span class="text-4xl font-semibold text-gray-600">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}</span>
                        @endif
                    </div>
                    
                    <h2 class="text-2xl font-bold text-center">{{ $user->first_name }} {{ $user->last_name }}</h2>
                    <p class="text-gray-600 text-center mt-1">@<strong>{{ $user->user_name }}</strong></p>
                    
                    <div class="mt-4 flex flex-wrap gap-2 justify-center">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $user->role === 'driver' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $user->role === 'customer' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $user->role === 'manager' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $user->role === 'dispatcher' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ ucwords($user->role) }}
                        </span>
                    </div>

                    @if($user->role === 'driver' && $user->is_available)
                    <div class="mt-3">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-green-100 text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" class="mr-1">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Available
                        </span>
                    </div>
                    @endif
                </div>

                <div class="mt-6 space-y-3 pt-6 border-t">
                    <div class="flex items-center gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <span class="text-gray-700">{{ $user->email }}</span>
                    </div>

                    <div class="flex items-center gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <span class="text-gray-700">{{ $user->phone ?? 'N/A' }}</span>
                    </div>

                    @if($user->address)
                    <div class="flex items-start gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500 mt-0.5">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="text-gray-700">{{ $user->address }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats (for drivers) -->
            @if($user->role === 'driver')
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">Performance Stats</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Total Deliveries</span>
                            <span class="font-semibold">{{ $user->total_deliveries }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Successful</span>
                            <span class="font-semibold text-green-600">{{ $user->successful_deliveries }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Failed</span>
                            <span class="font-semibold text-red-600">{{ $user->failed_deliveries }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">On-Time Rate</span>
                            <span class="font-semibold">{{ number_format($user->on_time_rate, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $user->on_time_rate }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Rating</span>
                            <span class="font-semibold">{{ number_format($user->rating, 1) }}/5.0</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-6">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Date of Birth</p>
                        <p class="font-semibold">{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Gender</p>
                        <p class="font-semibold">{{ $user->gender ? ucwords($user->gender) : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">City</p>
                        <p class="font-semibold">{{ $user->city ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">State</p>
                        <p class="font-semibold">{{ $user->state ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Country</p>
                        <p class="font-semibold">{{ $user->country ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Postal Code</p>
                        <p class="font-semibold">{{ $user->postal_code ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Employee Information -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-6">Employee Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Employee ID</p>
                        <p class="font-semibold">{{ $user->employee_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Department</p>
                        <p class="font-semibold">{{ $user->department ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Designation</p>
                        <p class="font-semibold">{{ $user->designation ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Joining Date</p>
                        <p class="font-semibold">{{ $user->joining_date ? \Carbon\Carbon::parse($user->joining_date)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Salary</p>
                        <p class="font-semibold">{{ $user->salary ? '$' . number_format($user->salary, 2) : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Manager</p>
                        <p class="font-semibold">
                            @if($user->manager)
                            <a href="{{ route('admin.users.show', $user->manager->id) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $user->manager->first_name }} {{ $user->manager->last_name }}
                            </a>
                            @else
                            N/A
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Assigned Warehouse</p>
                        <p class="font-semibold">
                            @if($user->assignedWarehouse)
                            <span class="text-blue-600">{{ $user->assignedWarehouse->name }}</span>
                            <span class="text-xs text-gray-500 block">{{ $user->assignedWarehouse->warehouse_code }}</span>
                            @else
                            <span class="text-gray-400">Not assigned</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Driver Information -->
            @if($user->role === 'driver')
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-6">Driver Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">License Number</p>
                        <p class="font-semibold">{{ $user->license_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Vehicle Type</p>
                        <p class="font-semibold">{{ $user->vehicle_type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Vehicle Number</p>
                        <p class="font-semibold">{{ $user->vehicle_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Vehicle Capacity</p>
                        <p class="font-semibold">{{ $user->vehicle_capacity ? $user->vehicle_capacity . ' kg' : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Availability</p>
                        <p class="font-semibold">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $user->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Experience</p>
                        <p class="font-semibold">{{ $user->experience_years ? $user->experience_years . ' years' : 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Account Activity -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-6">Account Activity</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Account Created</p>
                        <p class="font-semibold">{{ $user->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                        <p class="font-semibold">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Last Login</p>
                        <p class="font-semibold">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('M d, Y H:i') : 'Never' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Last Login IP</p>
                        <p class="font-semibold">{{ $user->last_login_ip ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email Verified</p>
                        <p class="font-semibold">
                            @if($user->email_verified_at)
                            <span class="text-green-600">Verified</span>
                            @else
                            <span class="text-red-600">Not Verified</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Two-Factor Auth</p>
                        <p class="font-semibold">
                            @if($user->two_factor_enabled)
                            <span class="text-green-600">Enabled</span>
                            @else
                            <span class="text-gray-600">Disabled</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Delete User
document.getElementById('deleteUserBtn')?.addEventListener('click', function() {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch('/admin/users/{{ $user->id }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.users.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the user');
        });
    }
});
</script>

@endsection