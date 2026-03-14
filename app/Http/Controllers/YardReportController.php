<?php

namespace App\Http\Controllers;

use App\Models\Yard;
use App\Models\YardVisit;
use App\Models\YardSlotHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class YardReportController extends Controller
{
    public function utilization(Request $request, Yard $yard)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        // Daily utilization trend
        $dailyUtilization = $yard->visits()
            ->selectRaw('DATE(check_in_time) as date, COUNT(*) as visit_count')
            ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupByRaw('DATE(check_in_time)')
            ->orderBy('date')
            ->get();

        // Zone breakdown
        $zoneStats = $yard->zones()->withCount([
            'slots',
            'slots as occupied_count' => function ($q) {
                $q->where('status', 'occupied');
            },
            'slots as available_count' => function ($q) {
                $q->where('status', 'available');
            },
        ])->get();

        // Hourly distribution (peak hours)
        $hourlyDistribution = $yard->visits()
            ->selectRaw('HOUR(check_in_time) as hour, COUNT(*) as count')
            ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupByRaw('HOUR(check_in_time)')
            ->orderBy('hour')
            ->get();

        // Overall stats
        $totalVisits = $yard->visits()
            ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->count();

        $avgDwellTime = $yard->visits()
            ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->whereNotNull('actual_duration_minutes')
            ->avg('actual_duration_minutes');

        $overstayCount = $yard->visits()
            ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->where('status', 'overstay')
            ->count();

        return view('backend.yards.reports.utilization', compact(
            'yard', 'dateFrom', 'dateTo', 'dailyUtilization', 'zoneStats',
            'hourlyDistribution', 'totalVisits', 'avgDwellTime', 'overstayCount'
        ));
    }

    public function analytics(Request $request, Yard $yard)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        // Purpose breakdown
        $purposeBreakdown = $yard->visits()
            ->selectRaw('purpose, COUNT(*) as count, AVG(actual_duration_minutes) as avg_duration')
            ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('purpose')
            ->get();

        // Average turnaround time
        $avgTurnaround = $yard->visits()
            ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->whereNotNull('actual_duration_minutes')
            ->avg('actual_duration_minutes');

        // Throughput (vehicles per day)
        $days = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) ?: 1;
        $totalVisits = $yard->visits()
            ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->count();
        $dailyThroughput = round($totalVisits / $days, 1);

        // Slot turnover rate
        $totalSlots = $yard->slots()->count();
        $slotTurnover = $totalSlots > 0 ? round($totalVisits / ($totalSlots * $days), 2) : 0;

        // Registration method breakdown
        $registrationBreakdown = $yard->visits()
            ->selectRaw('registered_by, COUNT(*) as count')
            ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('registered_by')
            ->get();

        // Overstay analysis
        $overstayStats = [
            'count' => $yard->visits()->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])->where('status', 'overstay')->count(),
            'avg_overstay' => $yard->visits()
                ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
                ->where('status', 'overstay')
                ->whereNotNull('actual_duration_minutes')
                ->selectRaw('AVG(actual_duration_minutes - expected_duration_minutes) as avg_overstay')
                ->value('avg_overstay') ?? 0,
        ];

        return view('backend.yards.reports.analytics', compact(
            'yard', 'dateFrom', 'dateTo', 'purposeBreakdown', 'avgTurnaround',
            'dailyThroughput', 'slotTurnover', 'registrationBreakdown', 'overstayStats', 'totalVisits'
        ));
    }
}
