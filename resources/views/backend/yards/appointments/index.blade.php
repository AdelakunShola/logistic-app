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

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.yards.show', $yard) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Yard Appointments
                </h1>
            </div>
            <p class="text-gray-500 mt-1 ml-8">{{ $yard->name }} &mdash; Schedule and manage appointments</p>
        </div>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Appointment
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Today</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['today'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Confirmed</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.yards.appointments.index', $yard) }}" class="flex flex-col md:flex-row gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Status</label>
                <select name="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">Filter</button>
            </div>
        </form>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scheduled Arrival</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scheduled Departure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($appointments as $appt)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $appt->driver_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $appt->vehicle_plate }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($appt->purpose) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $appt->scheduled_arrival ? \Carbon\Carbon::parse($appt->scheduled_arrival)->format('M d, H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $appt->scheduled_departure ? \Carbon\Carbon::parse($appt->scheduled_departure)->format('M d, H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-mono font-bold">{{ $appt->confirmation_code }}</td>
                        <td class="px-6 py-4">
                            @php
                                $apptColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'checked_in' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'no_show' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $apptColors[$appt->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $appt->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($appt->status === 'pending')
                                <button onclick="confirmAppointment({{ $appt->id }})" class="px-3 py-1.5 bg-green-50 text-green-700 hover:bg-green-100 rounded-lg text-sm font-medium transition-colors">Confirm</button>
                                @endif
                                @if(in_array($appt->status, ['pending', 'confirmed']))
                                <button onclick="cancelAppointment({{ $appt->id }})" class="px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors">Cancel</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-gray-500 font-medium">No appointments found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appointments->hasPages())
        <div class="px-6 py-4 border-t">{{ $appointments->appends(request()->query())->links() }}</div>
        @endif
    </div>
</div>

<!-- Create Appointment Modal -->
<div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="document.getElementById('createModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6 z-10">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">New Appointment</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('admin.yards.appointments.store', $yard) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name <span class="text-red-500">*</span></label>
                            <input type="text" name="driver_name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Plate <span class="text-red-500">*</span></label>
                            <input type="text" name="vehicle_plate" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                            <select name="vehicle_type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
                                <option value="">Select type</option>
                                <option value="truck">Truck</option>
                                <option value="van">Van</option>
                                <option value="trailer">Trailer</option>
                                <option value="container">Container</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Purpose <span class="text-red-500">*</span></label>
                            <select name="purpose" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
                                <option value="">Select purpose</option>
                                <option value="pickup">Pickup</option>
                                <option value="delivery">Delivery</option>
                                <option value="staging">Staging</option>
                                <option value="parking">Parking</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Arrival <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="scheduled_arrival" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Departure <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="scheduled_departure" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium">Create Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmAppointment(id) {
    if (!confirm('Confirm this appointment?')) return;
    fetch(`/admin/yard-appointments/${id}/confirm`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) window.location.reload();
        else alert(data.message || 'Failed');
    })
    .catch(() => alert('An error occurred'));
}

function cancelAppointment(id) {
    if (!confirm('Cancel this appointment?')) return;
    fetch(`/admin/yard-appointments/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: 'cancelled' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) window.location.reload();
        else alert(data.message || 'Failed');
    })
    .catch(() => alert('An error occurred'));
}
</script>

@endsection
