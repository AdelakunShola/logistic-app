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
            <h1 class="text-2xl font-bold text-gray-900">My Yard Visit</h1>
            <p class="text-gray-500 mt-1">Your current yard check-in status</p>
        </div>

        @if($activeVisit)
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            {{-- Status Header --}}
            <div class="bg-blue-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-200 text-sm">Currently Checked In</p>
                        <p class="text-xl font-bold">{{ $activeVisit->yard->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-200 text-sm">Status</p>
                        <p class="text-lg font-semibold capitalize">{{ str_replace('_', ' ', $activeVisit->status) }}</p>
                    </div>
                </div>
            </div>

            {{-- Visit Details --}}
            <div class="p-6 space-y-4">
                {{-- Assigned Slot --}}
                @if($activeVisit->slot)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                    <p class="text-sm text-green-600 font-medium">Your Assigned Slot</p>
                    <p class="text-4xl font-bold text-green-700 mt-1">{{ $activeVisit->slot->slot_number }}</p>
                    @if($activeVisit->slot->zone)
                    <p class="text-sm text-green-600 mt-1">Zone: {{ $activeVisit->slot->zone->name }}</p>
                    @endif
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
                    <p class="text-sm text-yellow-700 font-medium">No slot assigned yet</p>
                    <p class="text-xs text-yellow-600 mt-1">Please wait for an assignment from the yard manager</p>
                </div>
                @endif

                {{-- Details --}}
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Vehicle</span>
                        <span class="text-sm font-medium text-gray-900 font-mono">{{ $activeVisit->vehicle_plate }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Purpose</span>
                        <span class="text-sm font-medium text-gray-900 capitalize">{{ $activeVisit->purpose }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Check-in Time</span>
                        <span class="text-sm font-medium text-gray-900">{{ $activeVisit->check_in_time ? \Carbon\Carbon::parse($activeVisit->check_in_time)->format('M d, H:i') : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Time Elapsed</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if($activeVisit->check_in_time)
                                @php
                                    $elapsed = now()->diffInMinutes(\Carbon\Carbon::parse($activeVisit->check_in_time));
                                    $hours = floor($elapsed / 60);
                                    $mins = $elapsed % 60;
                                @endphp
                                {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $mins }}m
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-500">Expected Duration</span>
                        <span class="text-sm font-medium text-gray-900">{{ $activeVisit->expected_duration_minutes }} min</span>
                    </div>
                </div>

                {{-- Check Out Button --}}
                <form action="{{ route('driver.yard.check-out') }}" method="POST" class="pt-4" onsubmit="return confirm('Are you sure you want to check out?')">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold text-base transition-colors shadow-sm">
                        Check Out
                    </button>
                </form>
            </div>
        </div>
        @else
        {{-- No active visit --}}
        <div class="bg-white rounded-xl border shadow-sm p-8 text-center">
            <div class="inline-flex p-4 bg-gray-100 rounded-2xl mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">No Active Visit</h2>
            <p class="text-gray-500 mt-1 mb-6">You are not currently checked into any yard.</p>
            <a href="{{ route('driver.yard.check-in') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                Check In to a Yard
            </a>
        </div>
        @endif
    </div>
</div>

@endsection
