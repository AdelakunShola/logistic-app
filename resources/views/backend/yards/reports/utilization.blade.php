@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.yards.show', $yard) }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Utilization Report</h1>
            </div>
            <p class="text-gray-500 mt-1">{{ $yard->name }} &mdash; Yard utilization analytics</p>
        </div>

        {{-- Date Range Filter --}}
        <form method="GET" action="{{ route('admin.yards.reports.utilization', $yard) }}" class="flex items-end gap-3 bg-white p-4 rounded-xl border shadow-sm">
            <div>
                <label for="date_from" class="block text-xs font-medium text-gray-600 mb-1">From</label>
                <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div>
                <label for="date_to" class="block text-xs font-medium text-gray-600 mb-1">To</label>
                <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Apply
            </button>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Visits</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalVisits) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Avg Dwell Time</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($avgDwellTime, 1) }} <span class="text-lg text-gray-500">min</span></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Overstay Count</p>
                    <p class="text-3xl font-bold {{ $overstayCount > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($overstayCount) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Daily Utilization Chart --}}
    <div class="bg-white rounded-xl border shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Daily Utilization</h2>
        <div class="h-80">
            <canvas id="dailyUtilizationChart"></canvas>
        </div>
    </div>

    {{-- Zone Breakdown Table --}}
    <div class="bg-white rounded-xl border shadow-sm mb-6">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Zone Breakdown</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">Zone Name</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">Type</th>
                        <th class="text-center px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">Total Slots</th>
                        <th class="text-center px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">Occupied</th>
                        <th class="text-center px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">Available</th>
                        <th class="text-center px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">Utilization %</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($zoneStats as $zone)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            <span class="inline-block w-3 h-3 rounded-full mr-2" style="background: {{ $zone['color'] ?? '#3B82F6' }}"></span>
                            {{ $zone['name'] }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 capitalize">{{ $zone['type'] ?? 'general' }}</td>
                        <td class="px-6 py-4 text-center text-gray-900 font-medium">{{ $zone['total_slots'] }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $zone['occupied'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $zone['available'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    @php $zonePct = $zone['total_slots'] > 0 ? round(($zone['occupied'] / $zone['total_slots']) * 100, 1) : 0; @endphp
                                    <div class="h-2 rounded-full {{ $zonePct > 80 ? 'bg-red-500' : ($zonePct > 50 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ min($zonePct, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-700 min-w-[40px]">{{ $zonePct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">No zone data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Hourly Distribution Chart --}}
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Hourly Visit Distribution</h2>
        <div class="h-80">
            <canvas id="hourlyDistributionChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Daily Utilization Line Chart
        const dailyData = @json($dailyUtilization);
        const dailyLabels = dailyData.map(item => item.date);
        const dailyValues = dailyData.map(item => item.visits ?? item.count ?? 0);

        new Chart(document.getElementById('dailyUtilizationChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Visits per Day',
                    data: dailyValues,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { usePointStyle: true, padding: 20 }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, maxRotation: 45 }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { size: 11 }, precision: 0 }
                    }
                }
            }
        });

        // Hourly Distribution Bar Chart
        const hourlyData = @json($hourlyDistribution);
        const hourlyLabels = [];
        const hourlyValues = [];

        for (let h = 0; h < 24; h++) {
            hourlyLabels.push(h.toString().padStart(2, '0') + ':00');
            const entry = hourlyData.find(item => parseInt(item.hour) === h);
            hourlyValues.push(entry ? (entry.visits ?? entry.count ?? 0) : 0);
        }

        new Chart(document.getElementById('hourlyDistributionChart'), {
            type: 'bar',
            data: {
                labels: hourlyLabels,
                datasets: [{
                    label: 'Visits',
                    data: hourlyValues,
                    backgroundColor: hourlyValues.map((v, i) => {
                        if (i >= 6 && i < 10) return 'rgba(34, 197, 94, 0.7)';
                        if (i >= 10 && i < 16) return 'rgba(59, 130, 246, 0.7)';
                        if (i >= 16 && i < 20) return 'rgba(245, 158, 11, 0.7)';
                        return 'rgba(107, 114, 128, 0.5)';
                    }),
                    borderColor: hourlyValues.map((v, i) => {
                        if (i >= 6 && i < 10) return '#22C55E';
                        if (i >= 10 && i < 16) return '#3B82F6';
                        if (i >= 16 && i < 20) return '#F59E0B';
                        return '#6B7280';
                    }),
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { size: 11 }, precision: 0 }
                    }
                }
            }
        });
    });
</script>

@endsection
