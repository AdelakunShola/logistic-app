@extends('driver.driver_dashboard')
@section('driver')

<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">My Yard Appointments</h1>
            <p class="text-gray-500 mt-1">View your scheduled yard appointments</p>
        </div>

        <div class="space-y-4">
            @forelse($appointments as $appt)
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $appt->yard->name ?? 'Unknown Yard' }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ ucfirst($appt->purpose) }} &middot; {{ $appt->vehicle_plate }}</p>
                    </div>
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
                </div>

                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Arrival:</span>
                        <span class="font-medium text-gray-900">{{ $appt->scheduled_arrival ? \Carbon\Carbon::parse($appt->scheduled_arrival)->format('M d, H:i') : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Departure:</span>
                        <span class="font-medium text-gray-900">{{ $appt->scheduled_departure ? \Carbon\Carbon::parse($appt->scheduled_departure)->format('M d, H:i') : '-' }}</span>
                    </div>
                </div>

                @if($appt->confirmation_code && in_array($appt->status, ['pending', 'confirmed']))
                <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-center">
                    <p class="text-xs text-blue-600 font-medium">Confirmation Code</p>
                    <p class="text-2xl font-bold text-blue-800 tracking-wider mt-1">{{ $appt->confirmation_code }}</p>
                    <p class="text-xs text-blue-500 mt-1">Present this code at check-in</p>
                </div>
                @endif

                @if($appt->slot)
                <div class="mt-3 text-sm text-gray-600">
                    <span class="text-gray-500">Assigned Slot:</span> <span class="font-medium">{{ $appt->slot->slot_number }}</span>
                </div>
                @endif
            </div>
            @empty
            <div class="bg-white rounded-xl border shadow-sm p-8 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-gray-500 font-medium">No appointments</p>
                <p class="text-sm text-gray-400 mt-1">You don't have any scheduled yard appointments.</p>
            </div>
            @endforelse
        </div>

        @if($appointments->hasPages())
        <div class="mt-6">{{ $appointments->links() }}</div>
        @endif
    </div>
</div>

@endsection
