@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Create New User</h1>
            <p class="text-gray-600">Add a new user to the system</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                <path d="m12 19-7-7 7-7"></path>
                <path d="M19 12H5"></path>
            </svg>
            Back to Users
        </a>
    </div>

    <!-- Form -->
    <div class="rounded-lg border bg-white shadow-sm">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">First Name *</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror"/>
                            @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Last Name *</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror"/>
                            @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"/>
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Phone *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"/>
                            @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Gender</label>
                            <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Role & Status -->
                <div class="border-t pt-6">
                    <h2 class="text-xl font-semibold mb-4">Role & Status</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Role *</label>
                            <select name="role" id="roleSelect" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucwords($role) }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Status *</label>
                            <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="border-t pt-6">
                    <h2 class="text-xl font-semibold mb-4">Security</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Password *</label>
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

                <!-- Address Information -->
                <div class="border-t pt-6">
                    <h2 class="text-xl font-semibold mb-4">Address Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Address</label>
                            <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address') }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">City</label>
                                <input type="text" name="city" value="{{ old('city') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">State</label>
                                <input type="text" name="state" value="{{ old('state') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Country</label>
                                <input type="text" name="country" value="{{ old('country') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Postal Code</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Driver-Specific Fields -->
                <div id="driverFields" class="border-t pt-6 hidden">
                    <h2 class="text-xl font-semibold mb-4">Driver Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">License Number *</label>
                            <input type="text" name="license_number" value="{{ old('license_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Vehicle Type</label>
                            <input type="text" name="vehicle_type" value="{{ old('vehicle_type') }}" placeholder="e.g., Van, Truck" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Vehicle Number</label>
                            <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}" placeholder="e.g., ABC-1234" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Vehicle Capacity (kg)</label>
                            <input type="number" step="0.01" name="vehicle_capacity" value="{{ old('vehicle_capacity') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>
                </div>

                <!-- Employee Information -->
                <div class="border-t pt-6">
                    <h2 class="text-xl font-semibold mb-4">Employee Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Employee ID</label>
                            <input type="text" name="employee_id" value="{{ old('employee_id') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Department</label>
                            <input type="text" name="department" value="{{ old('department') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Designation</label>
                            <input type="text" name="designation" value="{{ old('designation') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Joining Date</label>
                            <input type="date" name="joining_date" value="{{ old('joining_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Salary</label>
                            <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Manager</label>
                            <select name="manager_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Manager</option>
                                @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->first_name }} {{ $manager->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Assigned Warehouse</label>
                            <select name="assigned_warehouse_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('assigned_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }} ({{ $warehouse->warehouse_code }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Profile Photo -->
                <div class="border-t pt-6">
                    <h2 class="text-xl font-semibold mb-4">Profile Photo</h2>
                    <div>
                        <label class="block text-sm font-medium mb-2">Upload Photo</label>
                        <input type="file" name="profile_photo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        <p class="text-xs text-gray-500 mt-1">Maximum file size: 2MB. Supported formats: JPG, PNG</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="p-6 border-t flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Show/hide driver fields based on role selection
document.getElementById('roleSelect').addEventListener('change', function() {
    const driverFields = document.getElementById('driverFields');
    const licenseInput = document.querySelector('input[name="license_number"]');
    
    if (this.value === 'driver') {
        driverFields.classList.remove('hidden');
        licenseInput.required = true;
    } else {
        driverFields.classList.add('hidden');
        licenseInput.required = false;
    }
});

// Trigger on page load if role is already selected
if (document.getElementById('roleSelect').value === 'driver') {
    document.getElementById('driverFields').classList.remove('hidden');
    document.querySelector('input[name="license_number"]').required = true;
}
</script>

@endsection