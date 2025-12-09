<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use App\Models\MaintenanceLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class DiagnoseFleetStatus extends Command
{
    protected $signature = 'fleet:diagnose';
    protected $description = 'Diagnose Fleet Status Dashboard Issues';

    public function handle()
    {
        $this->info('═══════════════════════════════════════════════');
        $this->info('   FLEET STATUS DASHBOARD DIAGNOSTICS');
        $this->info('═══════════════════════════════════════════════');
        $this->newLine();

        // 1. Check Database Connection
        $this->info('1️⃣  Checking Database Connection...');
        try {
            DB::connection()->getPdo();
            $this->info('   ✅ Database connection successful');
        } catch (\Exception $e) {
            $this->error('   ❌ Database connection FAILED: ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // 2. Check Tables Exist
        $this->info('2️⃣  Checking Required Tables...');
        $tables = ['vehicles', 'maintenance_logs', 'users', 'branches', 'hubs', 'warehouses'];
        foreach ($tables as $table) {
            $exists = DB::select("SHOW TABLES LIKE '{$table}'");
            if (empty($exists)) {
                $this->error("   ❌ Table '{$table}' does NOT exist");
            } else {
                $this->info("   ✅ Table '{$table}' exists");
            }
        }
        $this->newLine();

        // 3. Check Vehicle Data
        $this->info('3️⃣  Checking Vehicle Data...');
        $vehicleCount = Vehicle::count();
        $this->info("   Total vehicles: {$vehicleCount}");
        
        if ($vehicleCount === 0) {
            $this->error('   ❌ NO VEHICLES FOUND!');
            $this->warn('   Run: php artisan db:seed --class=VehicleSeeder');
        } else {
            $this->info('   ✅ Vehicles found');
            
            // Status breakdown
            $statuses = Vehicle::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();
            
            $this->table(
                ['Status', 'Count'],
                $statuses->map(fn($s) => [$s->status, $s->count])
            );
        }
        $this->newLine();

        // 4. Check Vehicle Sample Data
        $this->info('4️⃣  Sample Vehicle Data...');
        $sampleVehicle = Vehicle::with('assignedDriver')->first();
        if ($sampleVehicle) {
            $this->table(
                ['Field', 'Value'],
                [
                    ['Vehicle Number', $sampleVehicle->vehicle_number],
                    ['Type', $sampleVehicle->vehicle_type],
                    ['Make/Model', "{$sampleVehicle->make} {$sampleVehicle->model}"],
                    ['Status', $sampleVehicle->status],
                    ['Driver', $sampleVehicle->assignedDriver 
                        ? $sampleVehicle->assignedDriver->first_name . ' ' . $sampleVehicle->assignedDriver->last_name 
                        : 'Unassigned'],
                    ['Location', $sampleVehicle->current_location ?? 'N/A'],
                    ['Fuel Level', $sampleVehicle->current_fuel_level ?? 'N/A'],
                    ['Mileage', $sampleVehicle->mileage],
                    ['Has GPS', ($sampleVehicle->current_latitude && $sampleVehicle->current_longitude) ? 'Yes' : 'No'],
                ]
            );
        }
        $this->newLine();

        // 5. Check Maintenance Logs
        $this->info('5️⃣  Checking Maintenance Logs...');
        $maintenanceCount = MaintenanceLog::count();
        $this->info("   Total maintenance records: {$maintenanceCount}");
        
        if ($maintenanceCount === 0) {
            $this->warn('   ⚠️  No maintenance records found');
        } else {
            $this->info('   ✅ Maintenance records exist');
        }
        $this->newLine();

        // 6. Check Routes
        $this->info('6️⃣  Checking Routes...');
        $routes = [
            'admin.fleet.status' => 'GET /admin/fleet/status',
            'admin.fleet.dashboard-data' => 'GET /admin/fleet/dashboard-data',
            'admin.fleet.schedule' => 'GET /admin/fleet/schedule/{date}',
        ];
        
        foreach ($routes as $name => $path) {
            if (Route::has($name)) {
                $this->info("   ✅ Route '{$name}' exists");
            } else {
                $this->error("   ❌ Route '{$name}' NOT FOUND");
            }
        }
        $this->newLine();

        // 7. Check View Files
        $this->info('7️⃣  Checking View Files...');
        $viewPath = 'backend.dashboard.fleet-status';
        if (view()->exists($viewPath)) {
            $this->info("   ✅ View '{$viewPath}' exists");
        } else {
            $this->error("   ❌ View '{$viewPath}' NOT FOUND");
        }
        $this->newLine();

        // 8. Test API Endpoint
        $this->info('8️⃣  Testing API Response...');
        try {
            $controller = new \App\Http\Controllers\FleetController();
            $response = $controller->getDashboardDatafleet();
            $data = json_decode($response->getContent(), true);
            
            if (isset($data['error'])) {
                $this->error('   ❌ API returned error: ' . $data['message']);
            } else {
                $this->info('   ✅ API response successful');
                $this->info("      - Stats: " . json_encode($data['stats'] ?? []));
                $this->info("      - Vehicles count: " . count($data['vehicles'] ?? []));
                $this->info("      - Locations count: " . count($data['locations'] ?? []));
            }
        } catch (\Exception $e) {
            $this->error('   ❌ API test failed: ' . $e->getMessage());
        }
        $this->newLine();

        // 9. Check JavaScript Issues
        $this->info('9️⃣  Common JavaScript Issues to Check...');
        $this->warn('   • Open browser console (F12) and check for errors');
        $this->warn('   • Verify Chart.js is loaded');
        $this->warn('   • Check if CSRF token is present in page');
        $this->warn('   • Verify jQuery/Alpine is loaded if needed');
        $this->newLine();

        // 10. Recommendations
        $this->info('🔟  Recommendations...');
        if ($vehicleCount === 0) {
            $this->comment('   → Run: php artisan db:seed --class=VehicleSeeder');
        }
        $this->comment('   → Check Laravel logs: storage/logs/laravel.log');
        $this->comment('   → Check browser console for JavaScript errors');
        $this->comment('   → Verify route: php artisan route:list | grep fleet');
        $this->comment('   → Clear cache: php artisan cache:clear');
        $this->newLine();

        $this->info('═══════════════════════════════════════════════');
        $this->info('   Diagnostic Complete!');
        $this->info('═══════════════════════════════════════════════');

        return 0;
    }
}