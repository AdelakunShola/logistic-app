@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .isometric {
        transform: perspective(800px) rotateX(25deg) rotateZ(-5deg);
        transform-style: preserve-3d;
    }
    .slot-tooltip {
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .slot-item:hover .slot-tooltip {
        visibility: visible;
        opacity: 1;
    }
    .slot-popover {
        display: none;
    }
    .slot-popover.active {
        display: block;
    }
    .overstay-pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.yards.show', $yard) }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Live Yard Dashboard</h1>
            </div>
            <p class="text-gray-500 mt-1">{{ $yard->name }} &mdash; Real-time slot monitoring</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <span id="last-updated" class="text-sm text-gray-500 bg-white px-3 py-1.5 rounded-lg border shadow-sm">
                Last updated: <span id="update-time">{{ now()->format('H:i:s') }}</span>
            </span>
            <div class="flex bg-white rounded-lg border shadow-sm overflow-hidden">
                <button id="btn-2d" onclick="setView('2d')" class="px-4 py-1.5 text-sm font-medium bg-blue-600 text-white transition-colors">2D</button>
                <button id="btn-iso" onclick="setView('isometric')" class="px-4 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Isometric</button>
            </div>
            <a href="{{ route('admin.yards.show', $yard) }}" class="inline-flex items-center gap-2 bg-white border text-gray-700 hover:bg-gray-50 px-4 py-1.5 rounded-lg text-sm font-medium transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Yard Details
            </a>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Slots</p>
            <p id="stat-total" class="text-2xl font-bold text-gray-900 mt-1">{{ $utilization['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Occupied</p>
            <p id="stat-occupied" class="text-2xl font-bold text-red-600 mt-1">{{ $utilization['occupied'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Available</p>
            <p id="stat-available" class="text-2xl font-bold text-green-600 mt-1">{{ $utilization['available'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Reserved</p>
            <p id="stat-reserved" class="text-2xl font-bold text-yellow-600 mt-1">{{ $utilization['reserved'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Overstay Alerts</p>
            <p class="mt-1">
                <span id="stat-overstay" class="inline-flex items-center justify-center px-3 py-1 text-lg font-bold rounded-full {{ $overstayCount > 0 ? 'bg-red-100 text-red-700 overstay-pulse' : 'bg-gray-100 text-gray-600' }}">
                    {{ $overstayCount }}
                </span>
            </p>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Utilization</p>
            <p id="stat-utilization-pct" class="text-lg font-bold text-gray-900 mt-1">{{ number_format($utilization['utilization_percentage'], 1) }}%</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                <div id="stat-utilization-bar" class="h-2 rounded-full transition-all duration-500 {{ $utilization['utilization_percentage'] > 80 ? 'bg-red-500' : ($utilization['utilization_percentage'] > 50 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ min($utilization['utilization_percentage'], 100) }}%"></div>
            </div>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="flex flex-col xl:flex-row gap-6">

        {{-- Yard Visual Map --}}
        <div class="flex-1">
            <div class="bg-white rounded-xl border shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Yard Map</h2>
                    <div class="flex items-center gap-4 text-xs">
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm" style="background:#22C55E"></span> Available</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm" style="background:#EF4444"></span> Occupied</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm" style="background:#8B5CF6"></span> Reserved</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm" style="background:#6B7280"></span> Maintenance</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm" style="background:#1F2937"></span> Blocked</span>
                    </div>
                </div>

                <div id="yard-map" class="space-y-6 transition-transform duration-500 origin-center">
                    @foreach($yard->zones as $zone)
                    <div class="border-2 rounded-lg p-4 zone-container" data-zone-id="{{ $zone->id }}"
                         style="border-color: {{ $zone->color ?? '#3B82F6' }}; background: {{ $zone->color ?? '#3B82F6' }}10;">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-gray-800 text-sm">
                                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background: {{ $zone->color ?? '#3B82F6' }}"></span>
                                {{ $zone->name }}
                                <span class="text-xs text-gray-500 ml-1">({{ $zone->type ?? 'general' }})</span>
                            </h3>
                            <span class="text-xs text-gray-500">{{ $zone->slots->count() }} slots</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($zone->slots as $slot)
                            @php
                                $colorMap = [
                                    'available' => '#22C55E',
                                    'occupied'  => '#EF4444',
                                    'reserved'  => '#8B5CF6',
                                    'maintenance' => '#6B7280',
                                    'blocked'   => '#1F2937',
                                ];
                                $slotColor = $colorMap[$slot->status] ?? '#22C55E';
                                $textColor = in_array($slot->status, ['blocked', 'maintenance', 'occupied', 'reserved']) ? 'text-white' : 'text-gray-900';
                            @endphp
                            <div class="slot-item relative cursor-pointer"
                                 data-slot-id="{{ $slot->id }}"
                                 data-slot-status="{{ $slot->status }}"
                                 onclick="togglePopover(this, {{ json_encode([
                                     'id' => $slot->id,
                                     'slot_number' => $slot->slot_number,
                                     'status' => $slot->status,
                                     'vehicle_plate' => $slot->activeVisit->vehicle_plate ?? null,
                                     'driver_name' => $slot->activeVisit->driver_name ?? null,
                                     'purpose' => $slot->activeVisit->purpose ?? null,
                                     'check_in_time' => $slot->activeVisit->check_in_time ?? null,
                                 ]) }})">
                                <div class="w-14 h-14 rounded-lg flex items-center justify-center text-xs font-bold shadow-sm border border-white/30 transition-transform hover:scale-110 hover:shadow-md {{ $textColor }}"
                                     style="background: {{ $slotColor }};">
                                    {{ $slot->slot_number }}
                                </div>
                                {{-- Tooltip --}}
                                <div class="slot-tooltip absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap z-30 shadow-lg">
                                    <div class="font-semibold">{{ $slot->slot_number }}</div>
                                    <div class="capitalize">{{ $slot->status }}</div>
                                    @if($slot->activeVisit)
                                    <div>{{ $slot->activeVisit->vehicle_plate }}</div>
                                    @endif
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45 -mt-1"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right Sidebar - Active Visits --}}
        <div class="w-full xl:w-96 flex-shrink-0">
            <div class="bg-white rounded-xl border shadow-sm">
                <div class="p-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center justify-between">
                        Active Visits
                        <span class="text-sm font-normal text-gray-500" id="active-visit-count">{{ $activeVisits->count() }}</span>
                    </h2>
                </div>
                <div id="active-visits-list" class="divide-y max-h-[600px] overflow-y-auto">
                    @forelse($activeVisits as $visit)
                    <div class="p-4 hover:bg-gray-50 transition-colors active-visit-item" data-visit-id="{{ $visit->id }}">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $visit->driver_name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $visit->vehicle_plate }}</p>
                            </div>
                            @if($visit->is_overstay)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 overstay-pulse">
                                OVERSTAY
                            </span>
                            @endif
                        </div>
                        <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $visit->slot->slot_number ?? 'N/A' }}
                            </span>
                            <span class="flex items-center gap-1 visit-timer" data-checkin="{{ $visit->check_in_time->toIso8601String() }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @php
                                    $totalSeconds = max(0, now()->diffInSeconds($visit->check_in_time));
                                    $h = floor($totalSeconds / 3600);
                                    $m = floor(($totalSeconds % 3600) / 60);
                                    $s = $totalSeconds % 60;
                                @endphp
                                <span class="timer-text">{{ sprintf('%02d:%02d:%02d', $h, $m, $s) }}</span>
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        <p class="text-sm">No active visits</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom - Today's Appointments --}}
    <div class="mt-6">
        <div class="bg-white rounded-xl border shadow-sm">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Today's Upcoming Appointments
                </h2>
            </div>
            <div id="appointments-list" class="divide-y">
                @forelse($todayAppointments as $appointment)
                <div class="p-4 hover:bg-gray-50 transition-colors flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="text-center bg-blue-50 rounded-lg px-3 py-2 min-w-[70px]">
                            <p class="text-sm font-bold text-blue-700">{{ \Carbon\Carbon::parse($appointment->scheduled_time)->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $appointment->driver_name ?? $appointment->company_name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $appointment->vehicle_plate ?? 'No plate' }} &middot; {{ ucfirst($appointment->purpose ?? 'general') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($appointment->status ?? 'pending') }}
                        </span>
                        @if($appointment->assigned_slot)
                        <p class="text-xs text-gray-500 mt-1">Slot: {{ $appointment->assigned_slot }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm">No upcoming appointments today</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Slot Popover (floating, positioned via JS) --}}
<div id="slot-popover" class="hidden fixed z-50 bg-white rounded-xl border shadow-xl w-72 p-4" style="top:0;left:0;">
    <div class="flex items-center justify-between mb-3">
        <h4 class="font-bold text-gray-900" id="popover-slot-number"></h4>
        <button onclick="closePopover()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div id="popover-content" class="space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-gray-500">Status:</span> <span id="popover-status" class="font-medium capitalize"></span></div>
        <div id="popover-vehicle-row" class="flex justify-between"><span class="text-gray-500">Vehicle:</span> <span id="popover-vehicle" class="font-medium"></span></div>
        <div id="popover-driver-row" class="flex justify-between"><span class="text-gray-500">Driver:</span> <span id="popover-driver" class="font-medium"></span></div>
        <div id="popover-purpose-row" class="flex justify-between"><span class="text-gray-500">Purpose:</span> <span id="popover-purpose" class="font-medium capitalize"></span></div>
        <div id="popover-checkin-row" class="flex justify-between"><span class="text-gray-500">Check In:</span> <span id="popover-checkin" class="font-medium"></span></div>
        <div id="popover-duration-row" class="flex justify-between"><span class="text-gray-500">Duration:</span> <span id="popover-duration" class="font-medium"></span></div>
    </div>
    <div id="popover-actions" class="mt-4 flex gap-2">
        <button onclick="slotAction('checkout')" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium py-2 px-3 rounded-lg transition-colors">Check Out</button>
        <button onclick="slotAction('reassign')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-3 rounded-lg transition-colors">Reassign</button>
        <button onclick="slotAction('block')" class="flex-1 bg-gray-700 hover:bg-gray-800 text-white text-xs font-medium py-2 px-3 rounded-lg transition-colors">Block</button>
    </div>
</div>

<script>
    // View toggle
    let currentView = '2d';

    function setView(view) {
        currentView = view;
        const map = document.getElementById('yard-map');
        const btn2d = document.getElementById('btn-2d');
        const btnIso = document.getElementById('btn-iso');

        if (view === 'isometric') {
            map.classList.add('isometric');
            btnIso.classList.add('bg-blue-600', 'text-white');
            btnIso.classList.remove('text-gray-600');
            btn2d.classList.remove('bg-blue-600', 'text-white');
            btn2d.classList.add('text-gray-600');
        } else {
            map.classList.remove('isometric');
            btn2d.classList.add('bg-blue-600', 'text-white');
            btn2d.classList.remove('text-gray-600');
            btnIso.classList.remove('bg-blue-600', 'text-white');
            btnIso.classList.add('text-gray-600');
        }
    }

    // Popover
    let currentPopoverSlot = null;

    function togglePopover(element, slotData) {
        const popover = document.getElementById('slot-popover');
        const rect = element.getBoundingClientRect();

        if (currentPopoverSlot === slotData.id && !popover.classList.contains('hidden')) {
            closePopover();
            return;
        }

        currentPopoverSlot = slotData.id;

        document.getElementById('popover-slot-number').textContent = 'Slot ' + slotData.slot_number;
        document.getElementById('popover-status').textContent = slotData.status;

        const hasVisit = slotData.status === 'occupied' && slotData.vehicle_plate;
        document.getElementById('popover-vehicle-row').style.display = hasVisit ? 'flex' : 'none';
        document.getElementById('popover-driver-row').style.display = hasVisit ? 'flex' : 'none';
        document.getElementById('popover-purpose-row').style.display = hasVisit ? 'flex' : 'none';
        document.getElementById('popover-checkin-row').style.display = hasVisit ? 'flex' : 'none';
        document.getElementById('popover-duration-row').style.display = hasVisit ? 'flex' : 'none';
        document.getElementById('popover-actions').style.display = (slotData.status === 'occupied' || slotData.status === 'available') ? 'flex' : 'none';

        if (hasVisit) {
            document.getElementById('popover-vehicle').textContent = slotData.vehicle_plate;
            document.getElementById('popover-driver').textContent = slotData.driver_name || 'N/A';
            document.getElementById('popover-purpose').textContent = slotData.purpose || 'N/A';

            if (slotData.check_in_time) {
                const checkIn = new Date(slotData.check_in_time);
                document.getElementById('popover-checkin').textContent = checkIn.toLocaleTimeString();
                const diffMs = Date.now() - checkIn.getTime();
                const diffMin = Math.floor(diffMs / 60000);
                const hours = Math.floor(diffMin / 60);
                const mins = diffMin % 60;
                document.getElementById('popover-duration').textContent = hours > 0 ? hours + 'h ' + mins + 'm' : mins + ' min';
            }
        }

        // Position the popover
        let top = rect.bottom + window.scrollY + 8;
        let left = rect.left + window.scrollX - 100;

        if (left + 288 > window.innerWidth) left = window.innerWidth - 300;
        if (left < 8) left = 8;

        popover.style.top = top + 'px';
        popover.style.left = left + 'px';
        popover.classList.remove('hidden');
    }

    function closePopover() {
        document.getElementById('slot-popover').classList.add('hidden');
        currentPopoverSlot = null;
    }

    // Close popover when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.slot-item') && !e.target.closest('#slot-popover')) {
            closePopover();
        }
    });

    // Slot actions
    function slotAction(action) {
        if (!currentPopoverSlot) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const slotId = currentPopoverSlot;

        let url = '';
        let method = 'POST';

        switch (action) {
            case 'checkout':
                url = '/admin/yards/slots/' + slotId + '/checkout';
                break;
            case 'reassign':
                url = '/admin/yards/slots/' + slotId + '/reassign';
                break;
            case 'block':
                url = '/admin/yards/slots/' + slotId + '/block';
                break;
        }

        if (!confirm('Are you sure you want to ' + action + ' this slot?')) return;

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                closePopover();
                refreshDashboard();
            } else {
                alert(data.message || 'Action failed');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred');
        });
    }

    // Live timers - update every second
    function updateTimers() {
        document.querySelectorAll('.visit-timer').forEach(function(el) {
            const checkIn = new Date(el.dataset.checkin);
            const now = new Date();
            let diff = Math.max(0, Math.floor((now - checkIn) / 1000));
            const h = Math.floor(diff / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = diff % 60;
            const timerText = el.querySelector('.timer-text');
            if (timerText) {
                timerText.textContent = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
            }
        });
    }
    setInterval(updateTimers, 1000);

    // Auto-refresh
    function refreshDashboard() {
        fetch('{{ route("admin.yards.dashboard.refresh", $yard) }}')
            .then(r => r.json())
            .then(data => updateDashboard(data))
            .catch(err => console.error('Refresh failed:', err));
    }

    setInterval(() => {
        fetch('{{ route("admin.yards.dashboard.refresh", $yard) }}')
            .then(r => r.json())
            .then(data => updateDashboard(data));
    }, 10000);

    function updateDashboard(data) {
        // Update timestamp
        document.getElementById('update-time').textContent = data.timestamp || new Date().toLocaleTimeString();

        // Update stats
        if (data.utilization) {
            document.getElementById('stat-total').textContent = data.utilization.total;
            document.getElementById('stat-occupied').textContent = data.utilization.occupied;
            document.getElementById('stat-available').textContent = data.utilization.available;
            document.getElementById('stat-reserved').textContent = data.utilization.reserved;
            document.getElementById('stat-utilization-pct').textContent = parseFloat(data.utilization.utilization_percentage).toFixed(1) + '%';

            const bar = document.getElementById('stat-utilization-bar');
            bar.style.width = Math.min(data.utilization.utilization_percentage, 100) + '%';
            bar.className = 'h-2 rounded-full transition-all duration-500 ';
            if (data.utilization.utilization_percentage > 80) bar.classList.add('bg-red-500');
            else if (data.utilization.utilization_percentage > 50) bar.classList.add('bg-yellow-500');
            else bar.classList.add('bg-green-500');
        }

        if (data.overstayCount !== undefined) {
            const overstayEl = document.getElementById('stat-overstay');
            overstayEl.textContent = data.overstayCount;
            if (data.overstayCount > 0) {
                overstayEl.className = 'inline-flex items-center justify-center px-3 py-1 text-lg font-bold rounded-full bg-red-100 text-red-700 overstay-pulse';
            } else {
                overstayEl.className = 'inline-flex items-center justify-center px-3 py-1 text-lg font-bold rounded-full bg-gray-100 text-gray-600';
            }
        }

        // Update slot colors
        if (data.slots) {
            const colorMap = {
                'available': '#22C55E',
                'occupied': '#EF4444',
                'reserved': '#8B5CF6',
                'maintenance': '#6B7280',
                'blocked': '#1F2937'
            };

            data.slots.forEach(function(slot) {
                const slotEl = document.querySelector('[data-slot-id="' + slot.id + '"]');
                if (slotEl) {
                    slotEl.setAttribute('data-slot-status', slot.status);
                    const box = slotEl.querySelector('div');
                    if (box) {
                        box.style.background = colorMap[slot.status] || '#22C55E';
                        const isDark = ['blocked', 'maintenance', 'occupied'].includes(slot.status);
                        box.className = box.className.replace(/text-(white|gray-900)/g, '');
                        box.classList.add(isDark ? 'text-white' : 'text-gray-900');
                    }
                }
            });
        }

        // Update active visits list
        if (data.activeVisits) {
            const listEl = document.getElementById('active-visits-list');
            document.getElementById('active-visit-count').textContent = data.activeVisits.length;

            if (data.activeVisits.length === 0) {
                listEl.innerHTML = '<div class="p-8 text-center text-gray-400"><svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg><p class="text-sm">No active visits</p></div>';
            } else {
                let html = '';
                data.activeVisits.forEach(function(visit) {
                    const isOverstay = visit.is_overstay || false;
                    html += '<div class="p-4 hover:bg-gray-50 transition-colors active-visit-item" data-visit-id="' + visit.id + '">';
                    html += '<div class="flex items-start justify-between"><div>';
                    html += '<p class="font-semibold text-gray-900 text-sm">' + (visit.driver_name || 'N/A') + '</p>';
                    html += '<p class="text-xs text-gray-500 mt-0.5">' + (visit.vehicle_plate || 'N/A') + '</p>';
                    html += '</div>';
                    if (isOverstay) {
                        html += '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 overstay-pulse">OVERSTAY</span>';
                    }
                    html += '</div>';
                    html += '<div class="mt-2 flex items-center gap-3 text-xs text-gray-500">';
                    html += '<span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' + (visit.slot_number || 'N/A') + '</span>';
                    const checkInIso = visit.check_in_iso || '';
                    let timerDisplay = '00:00:00';
                    if (visit.duration_minutes !== undefined) {
                        const totalSec = Math.max(0, visit.duration_minutes * 60);
                        const hh = Math.floor(totalSec / 3600);
                        const mm = Math.floor((totalSec % 3600) / 60);
                        const ss = totalSec % 60;
                        timerDisplay = String(hh).padStart(2,'0') + ':' + String(mm).padStart(2,'0') + ':' + String(ss).padStart(2,'0');
                    }
                    html += '<span class="flex items-center gap-1 visit-timer" data-checkin="' + checkInIso + '"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="timer-text">' + timerDisplay + '</span></span>';
                    html += '</div></div>';
                });
                listEl.innerHTML = html;
            }
        }
    }
</script>

@endsection
