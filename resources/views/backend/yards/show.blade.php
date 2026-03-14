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
                <a href="{{ route('admin.yards.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 6V4a2 2 0 012-2h8a2 2 0 012 2v2"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                    {{ $yard->name }}
                </h1>
            </div>
            <div class="flex items-center gap-3 ml-8">
                <span class="text-sm text-gray-500 font-mono">{{ $yard->yard_code }}</span>
                @php
                    $statusColors = ['active' => 'bg-green-100 text-green-800', 'inactive' => 'bg-gray-100 text-gray-800', 'maintenance' => 'bg-yellow-100 text-yellow-800'];
                @endphp
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$yard->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($yard->status) }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.yards.edit', $yard) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.yards.designer', $yard) }}" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Designer
            </a>
            <a href="{{ route('admin.yards.dashboard', $yard) }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <form action="{{ route('admin.yards.destroy', $yard) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this yard? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M3 9h18M9 3v18"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Slots</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_slots'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Occupied</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['occupied_slots'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Available</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['available_slots'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Utilization</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $stats['utilization'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.yards.designer', $yard) }}" class="bg-white rounded-xl border p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-3 group">
            <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <span class="font-medium text-gray-700 group-hover:text-purple-700">Open Designer</span>
        </a>
        <a href="{{ route('admin.yards.dashboard', $yard) }}" class="bg-white rounded-xl border p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-3 group">
            <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </div>
            <span class="font-medium text-gray-700 group-hover:text-green-700">Live Dashboard</span>
        </a>
        <a href="{{ route('admin.yards.visits.index', $yard) }}" class="bg-white rounded-xl border p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-3 group">
            <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <span class="font-medium text-gray-700 group-hover:text-blue-700">View Visits</span>
        </a>
        <a href="{{ route('admin.yards.appointments.index', $yard) }}" class="bg-white rounded-xl border p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-3 group">
            <div class="p-2 bg-orange-100 rounded-lg group-hover:bg-orange-200 transition-colors">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="font-medium text-gray-700 group-hover:text-orange-700">Appointments</span>
        </a>
    </div>

    <!-- Two Columns: Recent Visits & Upcoming Appointments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Visits -->
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Recent Visits</h2>
                <a href="{{ route('admin.yards.visits.index', $yard) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slot</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentVisits as $visit)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $visit->driver_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 font-mono">{{ $visit->vehicle_plate }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $visit->slot ? $visit->slot->slot_number : 'N/A' }}</td>
                            <td class="px-4 py-3">
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
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $visitStatusColors[$visit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $visit->check_in_time ? \Carbon\Carbon::parse($visit->check_in_time)->format('M d, H:i') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">No recent visits</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Upcoming Appointments</h2>
                <a href="{{ route('admin.yards.appointments.index', $yard) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scheduled</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($upcomingAppointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $appointment->driver_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $appointment->scheduled_arrival ? \Carbon\Carbon::parse($appointment->scheduled_arrival)->format('M d, H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($appointment->purpose) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 font-mono">{{ $appointment->confirmation_code }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $apptStatusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'checked_in' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-gray-100 text-gray-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'no_show' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $apptStatusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">No upcoming appointments</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Yard Details -->
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Yard Details</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Address -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Address</h3>
                    <p class="text-gray-900">{{ $yard->address ?? 'N/A' }}</p>
                    <p class="text-gray-600 text-sm">{{ $yard->city ?? '' }}{{ $yard->state ? ', ' . $yard->state : '' }} {{ $yard->postal_code ?? '' }}</p>
                    @if($yard->latitude && $yard->longitude)
                    <p class="text-gray-400 text-xs mt-1">{{ $yard->latitude }}, {{ $yard->longitude }}</p>
                    @endif
                </div>

                <!-- Operating Hours -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Operating Hours</h3>
                    @if($yard->operating_hours_start && $yard->operating_hours_end)
                    <p class="text-gray-900">{{ $yard->operating_hours_start }} - {{ $yard->operating_hours_end }}</p>
                    @else
                    <p class="text-gray-400">Not specified</p>
                    @endif
                    @if($yard->timezone)
                    <p class="text-gray-500 text-sm mt-1">Timezone: {{ $yard->timezone }}</p>
                    @endif
                </div>

                <!-- Settings -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Settings</h3>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            @if($yard->auto_assign_enabled)
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            @else
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            @endif
                            <span class="text-sm text-gray-700">Auto-assign Slots</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($yard->allow_self_registration)
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            @else
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            @endif
                            <span class="text-sm text-gray-700">Self-registration</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($yard->require_appointment)
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            @else
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            @endif
                            <span class="text-sm text-gray-700">Appointment Required</span>
                        </div>
                    </div>
                </div>

                <!-- Linked Entities -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Manager</h3>
                    @if($yard->manager)
                    <p class="text-gray-900">{{ $yard->manager->name }}</p>
                    <p class="text-gray-500 text-sm">{{ $yard->manager->email ?? '' }}</p>
                    @else
                    <p class="text-gray-400">Not assigned</p>
                    @endif
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Warehouse</h3>
                    @if($yard->warehouse)
                    <p class="text-gray-900">{{ $yard->warehouse->name }}</p>
                    @else
                    <p class="text-gray-400">Not linked</p>
                    @endif
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Hub / Branch</h3>
                    @if($yard->hub)
                    <p class="text-gray-900">Hub: {{ $yard->hub->name }}</p>
                    @endif
                    @if($yard->branch)
                    <p class="text-gray-900">Branch: {{ $yard->branch->name }}</p>
                    @endif
                    @if(!$yard->hub && !$yard->branch)
                    <p class="text-gray-400">Not linked</p>
                    @endif
                </div>
            </div>

            <!-- Zone Summary -->
            <div class="mt-8 pt-6 border-t">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Zones ({{ $stats['total_zones'] }})</h3>
                @if($yard->zones && $yard->zones->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($yard->zones as $zone)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900">{{ $zone->name }}</p>
                                <p class="text-sm text-gray-500">{{ $zone->zone_code ?? '' }}</p>
                            </div>
                            <span class="text-sm text-gray-600">{{ $zone->slots ? $zone->slots->count() : 0 }} slots</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-400 text-sm">No zones configured. Use the <a href="{{ route('admin.yards.designer', $yard) }}" class="text-blue-600 hover:underline">Designer</a> to add zones and slots.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
