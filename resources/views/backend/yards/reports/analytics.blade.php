@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.yards.show', $yard) }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Yard Analytics</h1>
            </div>
            <p class="text-gray-500 mt-1">{{ $yard->name }} &mdash; Performance and operational insights</p>
        </div>

        <form method="GET" action="{{ route('admin.yards.reports.analytics', $yard) }}" class="flex items-end gap-3 bg-white p-4 rounded-xl border shadow-sm">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">Apply</button>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Visits</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalVisits) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Avg Turnaround</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($avgTurnaround, 0) }} <span class="text-lg text-gray-500">min</span></p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Daily Throughput</p>
            <p class="text-3xl font-bold text-blue-600">{{ $dailyThroughput }} <span class="text-lg text-gray-500">vehicles/day</span></p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Slot Turnover Rate</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $slotTurnover }}x</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Purpose Breakdown --}}
        <div class="bg-white rounded-xl border shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Visit Purpose Breakdown</h2>
            <div class="h-64">
                <canvas id="purposeChart"></canvas>
            </div>
        </div>

        {{-- Registration Method --}}
        <div class="bg-white rounded-xl border shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Registration Method</h2>
            <div class="h-64">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Purpose Details Table --}}
    <div class="bg-white rounded-xl border shadow-sm mb-6">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Purpose Analysis</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">Purpose</th>
                        <th class="text-center px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">Visit Count</th>
                        <th class="text-center px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">Avg Duration (min)</th>
                        <th class="text-center px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">% of Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($purposeBreakdown as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 capitalize">{{ $item->purpose }}</td>
                        <td class="px-6 py-4 text-center text-gray-900 font-medium">{{ $item->count }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ $item->avg_duration ? number_format($item->avg_duration, 0) : 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">
                            @php $pct = $totalVisits > 0 ? round(($item->count / $totalVisits) * 100, 1) : 0; @endphp
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-blue-500" style="width: {{ min($pct, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-700">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Overstay Stats --}}
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Overstay Analysis</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-red-100 rounded-xl">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Overstay Incidents</p>
                    <p class="text-3xl font-bold {{ $overstayStats['count'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $overstayStats['count'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="p-4 bg-orange-100 rounded-xl">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Avg Overstay Duration</p>
                    <p class="text-3xl font-bold text-orange-600">{{ number_format($overstayStats['avg_overstay'], 0) }} <span class="text-lg text-gray-500">min</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Purpose breakdown pie chart
    const purposeData = @json($purposeBreakdown);
    const purposeLabels = purposeData.map(item => item.purpose.charAt(0).toUpperCase() + item.purpose.slice(1));
    const purposeValues = purposeData.map(item => item.count);
    const purposeColors = ['#3B82F6', '#22C55E', '#F59E0B', '#EF4444', '#8B5CF6', '#6B7280'];

    new Chart(document.getElementById('purposeChart'), {
        type: 'doughnut',
        data: {
            labels: purposeLabels,
            datasets: [{
                data: purposeValues,
                backgroundColor: purposeColors.slice(0, purposeLabels.length),
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { usePointStyle: true, padding: 15 } },
            }
        }
    });

    // Registration method chart
    const regData = @json($registrationBreakdown);
    const regLabels = regData.map(item => {
        const labels = { self: 'Self Check-in', admin: 'Admin', kiosk: 'Kiosk', system: 'System' };
        return labels[item.registered_by] || item.registered_by;
    });
    const regValues = regData.map(item => item.count);
    const regColors = ['#3B82F6', '#22C55E', '#F59E0B', '#8B5CF6'];

    new Chart(document.getElementById('registrationChart'), {
        type: 'bar',
        data: {
            labels: regLabels,
            datasets: [{
                label: 'Registrations',
                data: regValues,
                backgroundColor: regColors.slice(0, regLabels.length),
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
});
</script>

@endsection
