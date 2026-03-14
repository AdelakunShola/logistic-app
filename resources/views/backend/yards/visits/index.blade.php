@extends('admin.admin_dashboard')
@section('admin')

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

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.yards.show', $yard) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Yard Visits
                </h1>
            </div>
            <p class="text-gray-500 mt-1 ml-8">{{ $yard->name }} &mdash; Visit history and management</p>
        </div>
        <button onclick="document.getElementById('checkInModal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Check In Vehicle
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Active Visits</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Today's Visits</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['today'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Overstay Alerts</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['overstay'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.yards.visits.index', $yard) }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 flex gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Status</label>
                <select name="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Status</option>
                    <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                    <option value="loading" {{ request('status') == 'loading' ? 'selected' : '' }}>Loading</option>
                    <option value="unloading" {{ request('status') == 'unloading' ? 'selected' : '' }}>Unloading</option>
                    <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                    <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                    <option value="overstay" {{ request('status') == 'overstay' ? 'selected' : '' }}>Overstay</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Purpose</label>
                <select name="purpose" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Purposes</option>
                    <option value="pickup" {{ request('purpose') == 'pickup' ? 'selected' : '' }}>Pickup</option>
                    <option value="delivery" {{ request('purpose') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                    <option value="staging" {{ request('purpose') == 'staging' ? 'selected' : '' }}>Staging</option>
                    <option value="parking" {{ request('purpose') == 'parking' ? 'selected' : '' }}>Parking</option>
                    <option value="maintenance" {{ request('purpose') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Filter</button>
            </div>
        </form>
    </div>

    <!-- Visits Table -->
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle Plate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slot</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Zone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($visits as $visit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $visit->driver_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $visit->vehicle_plate }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $visit->slot ? $visit->slot->slot_number : 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $visit->slot && $visit->slot->zone ? $visit->slot->zone->name : 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($visit->purpose ?? '-') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $visit->check_in_time ? \Carbon\Carbon::parse($visit->check_in_time)->format('M d, H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $visit->check_out_time ? \Carbon\Carbon::parse($visit->check_out_time)->format('M d, H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($visit->check_in_time)
                                @php
                                    $checkIn = \Carbon\Carbon::parse($visit->check_in_time);
                                    $checkOut = $visit->check_out_time ? \Carbon\Carbon::parse($visit->check_out_time) : now();
                                    $diffMinutes = $checkIn->diffInMinutes($checkOut);
                                    $hours = floor($diffMinutes / 60);
                                    $minutes = $diffMinutes % 60;
                                @endphp
                                {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes }}m
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $visitStatusColors = [
                                    'checked_in' => 'bg-blue-100 text-blue-800',
                                    'loading' => 'bg-yellow-100 text-yellow-800',
                                    'unloading' => 'bg-yellow-100 text-yellow-800',
                                    'waiting' => 'bg-gray-100 text-gray-800',
                                    'checked_out' => 'bg-green-100 text-green-800',
                                    'overstay' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $visitStatusColors[$visit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(!in_array($visit->status, ['checked_out']))
                            <button onclick="checkOutVisit({{ $visit->id }})" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Check Out
                            </button>
                            @else
                            <span class="text-sm text-gray-400">Completed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-gray-500 font-medium">No visits found</p>
                            <p class="text-sm text-gray-400 mt-1">Adjust your filters or check in a vehicle</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($visits->hasPages())
        <div class="px-6 py-4 border-t">{{ $visits->appends(request()->query())->links() }}</div>
        @endif
    </div>
</div>

<!-- Check-In Modal -->
<div id="checkInModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="document.getElementById('checkInModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6 z-10">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Check In Vehicle</h3>
                <button onclick="document.getElementById('checkInModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('admin.yards.visits.check-in', $yard) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name <span class="text-red-500">*</span></label>
                        <input type="text" name="driver_name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter driver name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Plate <span class="text-red-500">*</span></label>
                        <input type="text" name="vehicle_plate" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter vehicle plate number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                        <select name="vehicle_type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select vehicle type</option>
                            <option value="truck">Truck</option>
                            <option value="van">Van</option>
                            <option value="trailer">Trailer</option>
                            <option value="container">Container</option>
                            <option value="car">Car</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose <span class="text-red-500">*</span></label>
                        <select name="purpose" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select purpose</option>
                            <option value="pickup">Pickup</option>
                            <option value="delivery">Delivery</option>
                            <option value="staging">Staging</option>
                            <option value="parking">Parking</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expected Duration (minutes)</label>
                        <input type="number" name="expected_duration_minutes" min="1" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 60">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('checkInModal').classList.add('hidden')" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">Check In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function checkOutVisit(visitId) {
    if (!confirm('Are you sure you want to check out this vehicle?')) return;

    fetch(`/admin/yard-visits/${visitId}/check-out`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Failed to check out vehicle.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while checking out the vehicle.');
    });
}
</script>

@endsection
