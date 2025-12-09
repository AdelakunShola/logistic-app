<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Hub;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\MaintenanceLog;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FleetController extends Controller
{
    public function indexfleet()
{
    Log::info('=== FLEET STATUS PAGE ACCESSED ===');
    Log::info('User: ' . (auth()->user()->name ?? 'Guest'));
    Log::info('Route: ' . request()->url());
    
    try {
        $viewPath = 'backend.dashboard.fleet-status';
        Log::info('Attempting to load view: ' . $viewPath);
        
        if (!view()->exists($viewPath)) {
            Log::error('VIEW NOT FOUND: ' . $viewPath);
            return response('View not found: ' . $viewPath, 404);
        }
        
        // Get distinct values from database
        $vehicleTypes = DB::table('vehicles')
            ->select('vehicle_type')
            ->distinct()
            ->pluck('vehicle_type')
            ->toArray();
            
        $statuses = DB::table('vehicles')
            ->select('status')
            ->distinct()
            ->pluck('status')
            ->toArray();

             $drivers = User::where('role', 'driver')->where('status', 'active')->get();
             $branches = Branch::all();
        $hubs = Hub::all();
        $warehouse = Warehouse::all();
        
        Log::info('✅ View exists, rendering...');
        Log::info('Vehicle Types: ' . json_encode($vehicleTypes));
        Log::info('Statuses: ' . json_encode($statuses));
        
        return view($viewPath, compact('vehicleTypes', 'statuses','drivers','branches','hubs','warehouse'));
    } catch (\Exception $e) {
        Log::error('❌ Error loading fleet status view: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        throw $e;
    }
} 

    public function getDashboardDatafleet()
    {
        Log::info('========================================');
        Log::info('=== DASHBOARD DATA REQUEST RECEIVED ===');
        Log::info('========================================');
        Log::info('Request URL: ' . request()->url());
        Log::info('Request Method: ' . request()->method());
        Log::info('Time: ' . now()->toDateTimeString());
        
        try {
            // Step 1: Test database connection
            Log::info('--- STEP 1: Testing Database Connection ---');
            try {
                DB::connection()->getPdo();
                Log::info('✅ Database connection successful');
            } catch (\Exception $e) {
                Log::error('❌ Database connection failed: ' . $e->getMessage());
                throw $e;
            }

            // Step 2: Check if vehicles table exists
            Log::info('--- STEP 2: Checking Vehicles Table ---');
            $tableExists = DB::select("SHOW TABLES LIKE 'vehicles'");
            if (empty($tableExists)) {
                Log::error('❌ Vehicles table does NOT exist!');
                return response()->json([
                    'error' => 'Vehicles table does not exist',
                    'message' => 'Please run migrations first'
                ], 500);
            }
            Log::info('✅ Vehicles table exists');

            // Step 3: Count total vehicles
            Log::info('--- STEP 3: Counting Vehicles ---');
            $vehicleCount = Vehicle::count();
            Log::info("Total vehicles in database: {$vehicleCount}");
            
            if ($vehicleCount === 0) {
                Log::warning('⚠️ NO VEHICLES FOUND - Database is empty!');
                Log::info('Please run: php artisan db:seed --class=VehicleSeeder');
            }

            // Step 4: Get fleet stats
            Log::info('--- STEP 4: Getting Fleet Stats ---');
            $stats = $this->getFleetStats();
            Log::info('Stats retrieved: ' . json_encode($stats, JSON_PRETTY_PRINT));
            
            // Step 5: Get vehicles list
            Log::info('--- STEP 5: Getting Vehicles List ---');
            $vehicles = $this->getVehiclesList();
            Log::info('Vehicles retrieved: ' . $vehicles->count());
            if ($vehicles->count() > 0) {
                Log::info('First vehicle sample: ' . json_encode($vehicles->first(), JSON_PRETTY_PRINT));
            }
            
            // Step 6: Get vehicle locations
            Log::info('--- STEP 6: Getting Vehicle Locations ---');
            $locations = $this->getVehicleLocations();
            Log::info('Locations retrieved: ' . $locations->count());
            
            // Step 7: Get performance metrics
            Log::info('--- STEP 7: Getting Performance Metrics ---');
            $performance = $this->getPerformanceMetrics();
            Log::info('Performance metrics keys: ' . implode(', ', array_keys($performance)));
            
            // Step 8: Get maintenance schedule
            Log::info('--- STEP 8: Getting Maintenance Schedule ---');
            $schedule = $this->getMaintenanceSchedule();
            Log::info('Schedule current date: ' . $schedule['current_date']);

            $data = [
                'stats' => $stats,
                'vehicles' => $vehicles,
                'locations' => $locations,
                'performance' => $performance,
                'maintenance_schedule' => $schedule,
            ];
            
            Log::info('========================================');
            Log::info('=== DASHBOARD DATA PREPARED SUCCESSFULLY ===');
            Log::info('Response size: ' . strlen(json_encode($data)) . ' bytes');
            Log::info('Stats: ' . json_encode($stats));
            Log::info('Vehicles count: ' . count($vehicles));
            Log::info('Locations count: ' . count($locations));
            Log::info('========================================');
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            Log::error('========================================');
            Log::error('=== CRITICAL ERROR IN getDashboardDatafleet ===');
            Log::error('========================================');
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('Error File: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('Stack Trace:');
            Log::error($e->getTraceAsString());
            Log::error('========================================');
            
            return response()->json([
                'error' => 'Failed to load dashboard data',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString())
            ], 500);
        }
    }

    private function getFleetStats()
    {
        Log::info('>>> getFleetStats() started');
        
        try {
            $total = Vehicle::count();
            Log::info("   Total vehicles: {$total}");
            
            if ($total === 0) {
                Log::warning('   ⚠️ No vehicles found - returning default stats');
                return [
                    'total_vehicles' => 0,
                    'active' => 0,
                    'maintenance' => 0,
                    'available' => 0,
                    'out_of_service' => 0,
                    'fleet_efficiency' => 0,
                    'fuel_efficiency' => 0,
                    'maintenance_compliance' => 0,
                ];
            }
            
            $active = Vehicle::where('status', 'active')->count();
            Log::info("   Active: {$active}");
            
            $maintenance = Vehicle::where('status', 'maintenance')->count();
            Log::info("   Maintenance: {$maintenance}");
            
            $available = Vehicle::where('status', 'inactive')->count();
            Log::info("   Available (inactive): {$available}");
            
            $outOfService = Vehicle::where('status', 'repair')->count();
            Log::info("   Out of service (repair): {$outOfService}");

            $avgUtilization = Vehicle::where('status', 'active')->avg('utilization_percentage') ?? 0;
            Log::info("   Avg utilization: {$avgUtilization}%");
            
            $avgFuelEfficiency = Vehicle::whereNotNull('fuel_efficiency_mpg')->avg('fuel_efficiency_mpg') ?? 0;
            Log::info("   Avg fuel efficiency: {$avgFuelEfficiency} MPG");
            
            $maintenanceCompliance = $this->calculateMaintenanceCompliance();
            Log::info("   Maintenance compliance: {$maintenanceCompliance}%");
            
            $fleetEfficiency = (($avgUtilization * 0.4) + ($avgFuelEfficiency / 10 * 0.3) + ($maintenanceCompliance * 0.3));
            Log::info("   Calculated fleet efficiency: {$fleetEfficiency}%");

            $industryStandard = 7.5;
            $fuelEfficiencyPercentage = $avgFuelEfficiency > 0 ? ($avgFuelEfficiency / $industryStandard) * 100 : 0;
            Log::info("   Fuel efficiency percentage: {$fuelEfficiencyPercentage}%");

            $stats = [
                'total_vehicles' => $total,
                'active' => $active,
                'maintenance' => $maintenance,
                'available' => $available,
                'out_of_service' => $outOfService,
                'fleet_efficiency' => round($fleetEfficiency, 0),
                'fuel_efficiency' => round($fuelEfficiencyPercentage, 0),
                'maintenance_compliance' => round($maintenanceCompliance, 0),
            ];
            
            Log::info('>>> getFleetStats() completed ✅');
            return $stats;
            
        } catch (\Exception $e) {
            Log::error('>>> getFleetStats() ERROR ❌');
            Log::error('   ' . $e->getMessage());
            throw $e;
        }
    }

    private function getVehiclesList()
    {
        Log::info('>>> getVehiclesList() started');
        
        try {
            $query = Vehicle::with(['assignedDriver'])
                ->select([
                    'id', 'vehicle_number', 'vehicle_type', 'make', 'model', 'status',
                    'assigned_driver_id', 'current_location', 'current_fuel_level',
                    'mileage', 'next_service_date', 'last_service_date'
                ]);
            
            Log::info('   SQL: ' . $query->toSql());
            
            $vehicles = $query->get();
            Log::info("   Retrieved {$vehicles->count()} vehicles from database");
            
            if ($vehicles->isEmpty()) {
                Log::warning('   ⚠️ NO VEHICLES FOUND!');
                Log::info('   Run: php artisan db:seed --class=VehicleSeeder');
                return collect([]);
            }
            
            $mapped = $vehicles->map(function ($vehicle) {
                Log::debug("   Processing: {$vehicle->vehicle_number}");
                
                $driverName = 'Unassigned';
                if ($vehicle->assignedDriver) {
                    $driverName = $vehicle->assignedDriver->first_name . ' ' . $vehicle->assignedDriver->last_name;
                }
                
                return [
                    'id' => $vehicle->id,
                    'vehicle_id' => $vehicle->vehicle_number,
                    'type_model' => $vehicle->make . ' ' . $vehicle->model,
                    'vehicle_type' => $vehicle->vehicle_type,
                    'status' => $vehicle->status,
                    'driver' => $driverName,
                    'location' => $vehicle->current_location ?? 'Unknown',
                    'fuel' => $vehicle->current_fuel_level ?? 0,
                    'mileage' => number_format($vehicle->mileage, 0),
                    'maintenance' => [
                        'next' => $vehicle->next_service_date 
                            ? Carbon::parse($vehicle->next_service_date)->format('n/j/Y') 
                            : null,
                        'last' => $vehicle->last_service_date 
                            ? Carbon::parse($vehicle->last_service_date)->format('n/j/Y') 
                            : null,
                    ],
                ];
            });
            
            Log::info(">>> getVehiclesList() completed ✅ ({$mapped->count()} vehicles)");
            return $mapped;
            
        } catch (\Exception $e) {
            Log::error('>>> getVehiclesList() ERROR ❌');
            Log::error('   ' . $e->getMessage());
            throw $e;
        }
    }

    private function getVehicleLocations()
    {
        Log::info('>>> getVehicleLocations() started');
        
        try {
            $vehicles = Vehicle::whereNotNull('current_latitude')
                ->whereNotNull('current_longitude')
                ->with('assignedDriver')
                ->get();
            
            Log::info("   Found {$vehicles->count()} vehicles with location data");
            
            if ($vehicles->isEmpty()) {
                Log::warning('   ⚠️ No vehicles have location coordinates');
            }
            
            $mapped = $vehicles->map(function ($vehicle) {
                return [
                    'id' => $vehicle->vehicle_number,
                    'name' => $vehicle->vehicle_number,
                    'type' => $vehicle->vehicle_type,
                    'status' => $vehicle->status,
                    'latitude' => (float) $vehicle->current_latitude,
                    'longitude' => (float) $vehicle->current_longitude,
                    'location_name' => $vehicle->current_location,
                    'driver' => $vehicle->assignedDriver 
                        ? $vehicle->assignedDriver->first_name . ' ' . $vehicle->assignedDriver->last_name 
                        : 'Unassigned',
                    'fuel' => $vehicle->current_fuel_level,
                    'mileage' => number_format($vehicle->mileage, 0),
                    'last_update' => $vehicle->last_location_update 
                        ? Carbon::parse($vehicle->last_location_update)->diffForHumans() 
                        : 'Never',
                ];
            });
            
            Log::info(">>> getVehicleLocations() completed ✅ ({$mapped->count()} locations)");
            return $mapped;
            
        } catch (\Exception $e) {
            Log::error('>>> getVehicleLocations() ERROR ❌');
            Log::error('   ' . $e->getMessage());
            throw $e;
        }
    }

    private function getPerformanceMetrics()
{
    Log::info('>>> getPerformanceMetrics() started');
    
    try {
        // Get maintenance costs from the last 6 months
        $maintenanceCosts = MaintenanceLog::where('status', 'completed')
            ->where('maintenance_date', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(maintenance_date, "%Y-%m") as month, 
                         SUM(CASE WHEN maintenance_type IN ("scheduled", "inspection", "service") THEN cost ELSE 0 END) as preventive,
                         SUM(CASE WHEN maintenance_type IN ("breakdown", "repair") THEN cost ELSE 0 END) as repair')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        Log::info("   Maintenance cost records found: {$maintenanceCosts->count()}");
        
        // Format maintenance cost data (only if data exists)
        $maintenanceCostData = [];
        if ($maintenanceCosts->isNotEmpty()) {
            $maintenanceCostData = $maintenanceCosts->map(function ($item) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $item->month)->format('M'),
                    'preventive' => (int) round($item->preventive, 0),
                    'repair' => (int) round($item->repair, 0),
                ];
            })->toArray();
        }
        
        // Calculate preventive vs repair percentages
        $totalPreventive = $maintenanceCosts->sum('preventive');
        $totalRepair = $maintenanceCosts->sum('repair');
        $totalCost = $totalPreventive + $totalRepair;
        
        $preventivePercent = $totalCost > 0 ? round(($totalPreventive / $totalCost) * 100) : 0;
        $repairPercent = $totalCost > 0 ? round(($totalRepair / $totalCost) * 100) : 0;
        
        // Calculate cost per mile from real data
        $totalMiles = Vehicle::sum('mileage');
        $costPerMile = ($totalMiles > 0 && $totalCost > 0) ? $totalCost / $totalMiles : 0;
        
        Log::info("   Preventive: {$preventivePercent}%, Repair: {$repairPercent}%, Cost per mile: {$costPerMile}");
        
        // Get fuel efficiency from vehicles
        $fuelEfficiency = Vehicle::whereNotNull('fuel_efficiency_mpg')
            ->where('fuel_efficiency_mpg', '>', 0)
            ->selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, AVG(fuel_efficiency_mpg) as avg_mpg')
            ->where('updated_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        Log::info("   Fuel efficiency records found: {$fuelEfficiency->count()}");
        
        // Format fuel efficiency data (only if data exists)
        $fuelEfficiencyData = [];
        if ($fuelEfficiency->isNotEmpty()) {
            $fuelEfficiencyData = $fuelEfficiency->map(function ($item) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $item->month)->format('M'),
                    'actual' => round($item->avg_mpg, 2),
                    'target' => 7.5,
                ];
            })->toArray();
        }
        
        // Calculate current average and projected savings from real data
        $currentAverage = Vehicle::whereNotNull('fuel_efficiency_mpg')
            ->where('fuel_efficiency_mpg', '>', 0)
            ->avg('fuel_efficiency_mpg') ?: 0;
            
        $targetMpg = 7.5;
        $totalVehicles = Vehicle::count();
        $avgMileagePerVehicle = $totalVehicles > 0 ? (Vehicle::sum('mileage') / $totalVehicles) : 0;
        $fuelCostPerGallon = 3.50;
        
        // Calculate savings if above target
        $projectedSavings = 0;
        if ($currentAverage > $targetMpg && $avgMileagePerVehicle > 0) {
            $fuelSavedPerVehicle = $avgMileagePerVehicle * (1/$targetMpg - 1/$currentAverage);
            $projectedSavings = $fuelSavedPerVehicle * $fuelCostPerGallon * $totalVehicles;
        }
        
        // Calculate YTD change for preventive maintenance
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        
        $olderPreventive = MaintenanceLog::where('status', 'completed')
            ->whereBetween('maintenance_date', [$sixMonthsAgo, $threeMonthsAgo])
            ->whereIn('maintenance_type', ['scheduled', 'inspection', 'service'])
            ->sum('cost');
            
        $recentPreventive = MaintenanceLog::where('status', 'completed')
            ->where('maintenance_date', '>=', $threeMonthsAgo)
            ->whereIn('maintenance_type', ['scheduled', 'inspection', 'service'])
            ->sum('cost');
        
        $preventiveChange = 0;
        if ($olderPreventive > 0) {
            $preventiveChange = round((($recentPreventive - $olderPreventive) / $olderPreventive) * 100, 0);
        }
        
        // Calculate YTD change for cost per mile
        $olderTotalCost = MaintenanceLog::where('status', 'completed')
            ->whereBetween('maintenance_date', [$sixMonthsAgo, $threeMonthsAgo])
            ->sum('cost');
            
        $recentTotalCost = MaintenanceLog::where('status', 'completed')
            ->where('maintenance_date', '>=', $threeMonthsAgo)
            ->sum('cost');
        
        $costPerMileChange = 0;
        if ($olderTotalCost > 0 && $recentTotalCost > 0) {
            $costPerMileChange = round((($recentTotalCost - $olderTotalCost) / $olderTotalCost) * 100, 0);
        }
        
        // Calculate fuel efficiency change
        $olderFuelEfficiency = Vehicle::whereNotNull('fuel_efficiency_mpg')
            ->where('fuel_efficiency_mpg', '>', 0)
            ->where('updated_at', '>=', $sixMonthsAgo)
            ->where('updated_at', '<', $threeMonthsAgo)
            ->avg('fuel_efficiency_mpg') ?: 0;
            
        $recentFuelEfficiency = Vehicle::whereNotNull('fuel_efficiency_mpg')
            ->where('fuel_efficiency_mpg', '>', 0)
            ->where('updated_at', '>=', $threeMonthsAgo)
            ->avg('fuel_efficiency_mpg') ?: 0;
        
        $fuelEfficiencyChange = 0;
        if ($olderFuelEfficiency > 0) {
            $fuelEfficiencyChange = round((($recentFuelEfficiency - $olderFuelEfficiency) / $olderFuelEfficiency) * 100, 1);
        }
        
        Log::info("   Current average MPG: {$currentAverage}, Projected savings: {$projectedSavings}");
        Log::info("   Changes - Preventive: {$preventiveChange}%, Cost/Mile: {$costPerMileChange}%, Fuel: {$fuelEfficiencyChange}%");

        $performance = [
            'maintenance_cost' => [
                'chart_data' => $maintenanceCostData,
                'preventive_vs_repair' => [
                    'preventive' => $preventivePercent,
                    'repair' => $repairPercent,
                    'change' => $preventiveChange,
                ],
                'cost_per_mile' => [
                    'value' => round($costPerMile, 2),
                    'change' => $costPerMileChange,
                ],
            ],
            'fuel_efficiency' => [
                'chart_data' => $fuelEfficiencyData,
                'current_average' => round($currentAverage, 1),
                'change' => $fuelEfficiencyChange,
                'projected_savings' => number_format($projectedSavings, 0, '.', ','),
            ],
        ];
        
        Log::info('>>> getPerformanceMetrics() completed ✅');
        return $performance;
        
    } catch (\Exception $e) {
        Log::error('>>> getPerformanceMetrics() ERROR ❌');
        Log::error('   ' . $e->getMessage());
        throw $e;
    }
}

  

    private function calculateMaintenanceCompliance()
    {
        Log::info('>>> calculateMaintenanceCompliance() started');
        
        $totalVehicles = Vehicle::count();
        
        if ($totalVehicles === 0) {
            Log::info('   No vehicles, returning 95%');
            return 95;
        }

        $compliantVehicles = Vehicle::where(function ($query) {
            $query->whereNull('next_service_date')
                  ->orWhere('next_service_date', '>', Carbon::now());
        })->count();
        
        $percentage = ($compliantVehicles / $totalVehicles) * 100;
        Log::info("   Compliance: {$percentage}%");
        Log::info('>>> calculateMaintenanceCompliance() completed ✅');
        
        return $percentage;
    }

    public function getScheduleByDatefleet(Request $request, $date = null)
    {
        Log::info('=== SCHEDULE BY DATE REQUEST ===');
        Log::info('Date: ' . ($date ?? $request->input('date', 'today')));
        
        try {
            $date = $date ?? $request->input('date', Carbon::today()->format('Y-m-d'));
            $schedule = $this->getMaintenanceSchedule($date);
            
            Log::info('=== SCHEDULE RETRIEVED SUCCESSFULLY ===');
            return response()->json($schedule);
            
        } catch (\Exception $e) {
            Log::error('=== SCHEDULE ERROR ===');
            Log::error($e->getMessage());
            
            return response()->json([
                'error' => 'Failed to load schedule',
                'message' => $e->getMessage()
            ], 500);
        }
    }





private function getMaintenanceSchedule($date = null)
{
    Log::info('>>> getMaintenanceSchedule() started');
    Log::info("   Date parameter: " . ($date ?? 'null (will use today)'));
    
    try {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        Log::info("   Target date: " . $targetDate->toDateString());
        
        $monthStart = $targetDate->copy()->startOfMonth();
        $monthEnd = $targetDate->copy()->endOfMonth();
        
        Log::info("   Month range: {$monthStart->toDateString()} to {$monthEnd->toDateString()}");

        // Get all maintenance records for the month
        $logs = MaintenanceLog::with('vehicle')
            ->whereBetween('maintenance_date', [$monthStart, $monthEnd])
            ->whereIn('status', ['scheduled', 'in_progress', 'pending'])
            ->get();
        
        Log::info("   Found {$logs->count()} scheduled maintenance records for the month");

        // Get maintenance for the specific selected date
        $todayLogs = MaintenanceLog::with('vehicle')
            ->whereDate('maintenance_date', $targetDate)
            ->whereIn('status', ['scheduled', 'in_progress', 'pending'])
            ->get();
        
        Log::info("   Found {$todayLogs->count()} maintenance records for selected date: {$targetDate->toDateString()}");
        
        // Log each maintenance item found
        foreach ($todayLogs as $log) {
            Log::info("   - Vehicle: {$log->vehicle->vehicle_number}, Type: {$log->maintenance_type}, Desc: {$log->description}");
        }

        // Build calendar data with dates that have maintenance
        $calendarData = [];
        foreach ($logs as $log) {
            $logDate = Carbon::parse($log->maintenance_date)->format('Y-m-d');
            if (!isset($calendarData[$logDate])) {
                $calendarData[$logDate] = [];
            }
            $calendarData[$logDate][] = $log->id;
        }
        
        Log::info("   Calendar data contains " . count($calendarData) . " dates with maintenance");

        // Build today's maintenance list
       $todayMaintenance = $todayLogs->map(function ($log) {
        return [
            'id' => $log->id,
            'vehicle_id' => $log->vehicle->vehicle_number ?? 'Unknown',
            'vehicle_name' => ($log->vehicle->make ?? '') . ' ' . ($log->vehicle->model ?? ''),
            'type' => $log->maintenance_type,
            'category' => $log->category ?? 'General',
            'description' => $log->description,
            'priority' => $log->priority ?? 'medium',
            'status' => $log->status,
            'vendor' => $log->vendor_name ?? 'N/A',
            'technician' => $log->technician_name ?? 'N/A',
            'estimated_cost' => $log->cost ? '$' . number_format($log->cost, 2) : 'N/A',
            'time' => Carbon::parse($log->maintenance_date)->format('g:i A'),
            // Add completion_date handling
            'completed_date' => $log->status === 'completed' && $log->next_maintenance_date 
                ? Carbon::parse($log->next_maintenance_date)->format('n/j/Y') 
                : null,
        ];
    })->toArray();
        
        Log::info("   Prepared " . count($todayMaintenance) . " maintenance items for response");

        $schedule = [
            'current_date' => $targetDate->format('l, F j, Y'),
            'month' => $targetDate->format('F Y'),
            'calendar' => $calendarData,
            'today' => $todayMaintenance,
            'no_maintenance' => empty($todayMaintenance),
        ];
        
        Log::info('>>> getMaintenanceSchedule() completed ✅');
        Log::info('   Response structure:');
        Log::info('   - current_date: ' . $schedule['current_date']);
        Log::info('   - month: ' . $schedule['month']);
        Log::info('   - calendar dates with maintenance: ' . count($schedule['calendar']));
        Log::info('   - today items: ' . count($schedule['today']));
        Log::info('   - no_maintenance flag: ' . ($schedule['no_maintenance'] ? 'true' : 'false'));
        
        return $schedule;
        
    } catch (\Exception $e) {
        Log::error('>>> getMaintenanceSchedule() ERROR ❌');
        Log::error('   ' . $e->getMessage());
        Log::error('   Stack trace: ' . $e->getTraceAsString());
        throw $e;
    }
}
}