@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.yards.show', $yard) }}" class="p-2 hover:bg-white rounded-lg transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Yard: {{ $yard->name }}</h1>
            <p class="text-gray-500 mt-1">{{ $yard->yard_code }}</p>
        </div>
    </div>

    <form action="{{ route('admin.yards.update', $yard) }}" method="POST" class="space-y-6 max-w-4xl">
        @csrf
        @method('PUT')

        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg">
            <ul class="list-disc pl-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <!-- Basic Information -->
        <div class="bg-white rounded-xl border shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Yard Code *</label>
                    <input type="text" name="yard_code" value="{{ old('yard_code', $yard->yard_code) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Yard Name *</label>
                    <input type="text" name="name" value="{{ old('name', $yard->name) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Capacity *</label>
                    <input type="number" name="total_capacity" value="{{ old('total_capacity', $yard->total_capacity) }}" required min="1" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Manager</label>
                    <select name="manager_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Manager --</option>
                        @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id', $yard->manager_id) == $manager->id ? 'selected' : '' }}>{{ $manager->user_name ?? $manager->first_name . ' ' . $manager->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $yard->description) }}</textarea>
            </div>
        </div>

        <!-- Address -->
        <div class="bg-white rounded-xl border shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Address</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                    <input type="text" name="address" value="{{ old('address', $yard->address) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                        <input type="text" name="city" value="{{ old('city', $yard->city) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                        <input type="text" name="state" value="{{ old('state', $yard->state) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $yard->postal_code) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $yard->phone) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $yard->email) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Linked Entities -->
        <div class="bg-white rounded-xl border shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Linked Entities</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse</label>
                    <select name="warehouse_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- None --</option>
                        @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" {{ old('warehouse_id', $yard->warehouse_id) == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
               
            </div>
        </div>

        <!-- Operating Settings -->
        <div class="bg-white rounded-xl border shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Operating Settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operating Hours Start</label>
                    <input type="time" name="operating_hours_start" value="{{ old('operating_hours_start', $yard->operating_hours_start ? \Carbon\Carbon::parse($yard->operating_hours_start)->format('H:i') : '06:00') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operating Hours End</label>
                    <input type="time" name="operating_hours_end" value="{{ old('operating_hours_end', $yard->operating_hours_end ? \Carbon\Carbon::parse($yard->operating_hours_end)->format('H:i') : '22:00') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Stay (hours)</label>
                    <input type="number" name="max_stay_hours" value="{{ old('max_stay_hours', $yard->max_stay_hours) }}" min="1" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Overstay Alert (minutes)</label>
                    <input type="number" name="overstay_alert_minutes" value="{{ old('overstay_alert_minutes', $yard->overstay_alert_minutes) }}" min="1" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="mt-4 space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="auto_assign_enabled" value="1" {{ old('auto_assign_enabled', $yard->auto_assign_enabled) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Enable automatic slot assignment</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="allow_self_registration" value="1" {{ old('allow_self_registration', $yard->allow_self_registration) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Allow driver self check-in</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="require_appointment" value="1" {{ old('require_appointment', $yard->require_appointment) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Require appointment for entry</span>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors shadow-sm">Update Yard</button>
            <a href="{{ route('admin.yards.show', $yard) }}" class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">Cancel</a>
        </div>
    </form>
</div>

@endsection
