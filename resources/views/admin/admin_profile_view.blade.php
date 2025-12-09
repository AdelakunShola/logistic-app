@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>

<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">My Profile</h1>
            <p class="text-gray-600">View and manage your personal information</p>
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
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="rounded-lg bg-green-50 border border-green-200 p-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 mr-3">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="rounded-lg bg-red-50 border border-red-200 p-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-600 mr-3">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card with Photo Upload -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="photoForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden mb-4">
                                @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->first_name }}" class="w-full h-full object-cover" id="profilePreview"/>
                                @else
                                <span class="text-4xl font-semibold text-gray-600" id="profileInitials">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <label for="photoInput" class="absolute bottom-4 right-0 bg-blue-600 text-white p-2 rounded-full cursor-pointer hover:bg-blue-700 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                            </label>
                            <input type="file" name="profile_photo" accept="image/*" class="hidden" id="photoInput"/>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-center">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="text-gray-600 text-center mt-1">{{ $user->email }}</p>
                        
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

                        <button type="submit" id="uploadPhotoBtn" class="hidden mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            Upload Photo
                        </button>
                    </div>

                    <div class="mt-6 space-y-3 pt-6 border-t">
                        <div class="flex items-center gap-3 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $user->phone ?? 'Not provided' }}</span>
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
                </form>
            </div>

            <!-- Account Info -->
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">Account Information</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Member Since</p>
                        <p class="font-semibold">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email Verified</p>
                        <p class="font-semibold">
                            @if($user->email_verified_at)
                            <span class="text-green-600">✓ Verified</span>
                            @else
                            <span class="text-red-600">Not Verified</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Editable Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information Form -->
            <div class="rounded-lg border bg-white shadow-sm">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">First Name *</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror"/>
                                    @error('first_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Last Name *</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror"/>
                                    @error('last_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Email *</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"/>
                                    @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Phone *</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"/>
                                    @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Date of Birth</label>
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Gender</label>
                                    <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="border-t pt-6">
                            <h2 class="text-xl font-semibold mb-4">Address Information</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Address</label>
                                    <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">City</label>
                                        <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">State</label>
                                        <input type="text" name="state" value="{{ old('state', $user->state) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Country</label>
                                        <input type="text" name="country" value="{{ old('country', $user->country) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Postal Code</label>
                                        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Driver-Specific Fields -->
                        @if($user->role === 'driver')
                        <div class="border-t pt-6">
                            <h2 class="text-xl font-semibold mb-4">Driver Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">License Number *</label>
                                    <input type="text" name="license_number" value="{{ old('license_number', $user->license_number) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Vehicle Type</label>
                                    <input type="text" name="vehicle_type" value="{{ old('vehicle_type', $user->vehicle_type) }}" placeholder="e.g., Van, Truck" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Vehicle Number</label>
                                    <input type="text" name="vehicle_number" value="{{ old('vehicle_number', $user->vehicle_number) }}" placeholder="e.g., ABC-1234" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Vehicle Capacity (kg)</label>
                                    <input type="number" step="0.01" name="vehicle_capacity" value="{{ old('vehicle_capacity', $user->vehicle_capacity) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Employee Information (Read-only) -->
                        <div class="border-t pt-6">
                            <h2 class="text-xl font-semibold mb-4">Employee Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Employee ID</p>
                                    <p class="font-semibold">{{ $user->employee_id ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Department</p>
                                    <p class="font-semibold">{{ $user->department ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Designation</p>
                                    <p class="font-semibold">{{ $user->designation ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Joining Date</p>
                                    <p class="font-semibold">{{ $user->joining_date ? \Carbon\Carbon::parse($user->joining_date)->format('M d, Y') : 'Not provided' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Manager</p>
                                    <p class="font-semibold">
                                        {{ $user->manager ? $user->manager->first_name . ' ' . $user->manager->last_name : 'Not assigned' }}
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
                    </div>

                    <!-- Form Actions -->
                    <div class="p-6 border-t flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="rounded-lg border bg-white shadow-sm">
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold mb-2">Change Password</h2>
                            <p class="text-sm text-gray-600">Update your password to keep your account secure</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Current Password *</label>
                                <input type="password" name="current_password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror"/>
                                @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">New Password *</label>
                                <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"/>
                                @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Confirm Password *</label>
                                <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="p-6 border-t flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Preview and handle profile photo upload
const photoInput = document.getElementById('photoInput');
if (photoInput) {
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePreview');
                const initials = document.getElementById('profileInitials');
                const uploadBtn = document.getElementById('uploadPhotoBtn');
                
                if (preview) {
                    preview.src = e.target.result;
                } else if (initials && initials.parentElement) {
                    initials.parentElement.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="w-full h-full object-cover" id="profilePreview"/>';
                }
                
                // Show upload button
                if (uploadBtn) {
                    uploadBtn.classList.remove('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    });
}
</script>

@endsection