@extends('driver.driver_dashboard')
@section('driver')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-r-lg shadow-sm flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-r-lg shadow-sm flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm-1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="max-w-lg mx-auto">
        <div class="text-center mb-8">
            <div class="inline-flex p-4 bg-blue-100 rounded-2xl mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Yard Check-In</h1>
            <p class="text-gray-500 mt-1">Register your arrival at the yard</p>
        </div>

        <form action="{{ route('driver.yard.check-in.store') }}" method="POST" class="bg-white rounded-xl border shadow-sm p-6 space-y-5">
            @csrf

            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg">
                <ul class="list-disc pl-4 text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Yard <span class="text-red-500">*</span></label>
                <select name="yard_id" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                    <option value="">-- Choose a yard --</option>
                    @foreach($yards as $yard)
                    <option value="{{ $yard->id }}" {{ old('yard_id') == $yard->id ? 'selected' : '' }}>{{ $yard->name }} ({{ $yard->city }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Plate <span class="text-red-500">*</span></label>
                <input type="text" name="vehicle_plate" value="{{ old('vehicle_plate', $driverVehicle->vehicle_number ?? '') }}" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 text-base {{ $driverVehicle ? 'bg-gray-50' : '' }}" placeholder="e.g., ABC-1234" {{ $driverVehicle ? 'readonly' : '' }}>
                @if($driverVehicle)
                <p class="text-xs text-green-600 mt-1">Auto-filled from your assigned vehicle</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                @php $vType = old('vehicle_type', $driverVehicle->vehicle_type ?? ''); @endphp
                <select name="vehicle_type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 text-base {{ $driverVehicle ? 'bg-gray-50' : '' }}" {{ $driverVehicle ? 'disabled' : '' }}>
                    <option value="">Select type</option>
                    <option value="truck" {{ $vType == 'truck' ? 'selected' : '' }}>Truck</option>
                    <option value="van" {{ $vType == 'van' ? 'selected' : '' }}>Van</option>
                    <option value="trailer" {{ $vType == 'trailer' ? 'selected' : '' }}>Trailer</option>
                    <option value="container" {{ $vType == 'container' ? 'selected' : '' }}>Container</option>
                    <option value="car" {{ $vType == 'car' ? 'selected' : '' }}>Car</option>
                </select>
                @if($driverVehicle)
                <input type="hidden" name="vehicle_type" value="{{ $driverVehicle->vehicle_type ?? '' }}">
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Purpose <span class="text-red-500">*</span></label>
                <select name="purpose" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 text-base">
                    <option value="">Select purpose</option>
                    <option value="pickup" {{ old('purpose') == 'pickup' ? 'selected' : '' }}>Pickup</option>
                    <option value="delivery" {{ old('purpose') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                    <option value="staging" {{ old('purpose') == 'staging' ? 'selected' : '' }}>Staging</option>
                    <option value="parking" {{ old('purpose') == 'parking' ? 'selected' : '' }}>Parking</option>
                    <option value="maintenance" {{ old('purpose') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Confirmation Code</label>
                <input type="text" name="confirmation_code" value="{{ old('confirmation_code') }}" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 text-base" placeholder="Enter code if you have an appointment">
                <p class="text-xs text-gray-400 mt-1">Required if the yard requires appointments</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold text-base transition-colors shadow-sm">
                Check In
            </button>
        </form>
    </div>
</div>

@endsection
