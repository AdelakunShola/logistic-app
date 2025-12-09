<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceLog;
use App\Models\Vehicle;
use App\Models\User;

class MaintenanceLogSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = Vehicle::all();
        $users = User::all();

        if ($vehicles->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please seed vehicles and users first!');
            return;
        }

        $maintenanceLogs = [
            [
                'log_number' => 'MNT-001',
                'vehicle_id' => optional($vehicles->where('vehicle_number', 'LG-001-AA')->first())->id,
                'maintenance_type' => 'scheduled',
                'category' => 'Engine',
                'maintenance_date' => now()->subDays(7),
                'next_maintenance_date' => now()->addMonths(3),
                'cost' => 450.00,
                'vendor_name' => 'AutoCare Services',
                'description' => 'Oil change and filter replacement',
                'parts_replaced' => 'Engine oil, Oil filter, Air filter',
                'mileage_at_maintenance' => 45000,
                'status' => 'completed',
                'priority' => 'medium',
                'performed_by' => $users->random()->id,
                'technician_name' => 'Mike Johnson',
                'notes' => 'Regular maintenance completed successfully',
            ],
            [
                'log_number' => 'MNT-002',
                'vehicle_id' => optional($vehicles->where('vehicle_number', 'LG-002-BB')->first())->id,
                'maintenance_type' => 'repair',
                'category' => 'Brakes',
                'maintenance_date' => now()->subDays(3),
                'cost' => 850.00,
                'vendor_name' => 'BrakeMaster Pro',
                'description' => 'Brake pad replacement - front axle',
                'parts_replaced' => 'Front brake pads, Brake rotors',
                'mileage_at_maintenance' => 62000,
                'status' => 'in_progress',
                'priority' => 'high',
                'performed_by' => $users->random()->id,
                'technician_name' => 'Sarah Wilson',
                'notes' => 'Front brake pads worn out, replaced with new ones',
            ],
            [
                'log_number' => 'MNT-003',
                'vehicle_id' => optional($vehicles->where('vehicle_number', 'LG-003-CC')->first())->id,
                'maintenance_type' => 'breakdown',
                'category' => 'Transmission',
                'maintenance_date' => now()->addDays(3),
                'cost' => 1200.00,
                'vendor_name' => 'TransFix Solutions',
                'description' => 'Transmission fluid leak repair',
                'mileage_at_maintenance' => 156000,
                'status' => 'scheduled',
                'priority' => 'critical',
                'performed_by' => $users->random()->id,
                'technician_name' => 'David Brown',
                'notes' => 'Transmission fluid leak detected during routine inspection. Requires immediate attention to prevent transmission damage.',
            ],
            [
                'log_number' => 'MNT-004',
                'vehicle_id' => optional($vehicles->where('vehicle_number', 'LG-001-AA')->first())->id,
                'maintenance_type' => 'inspection',
                'category' => 'Safety',
                'maintenance_date' => now()->subDays(15),
                'cost' => 300.00,
                'vendor_name' => 'SafeCheck Inspections',
                'description' => 'Annual safety inspection',
                'mileage_at_maintenance' => 87000,
                'status' => 'scheduled',
                'priority' => 'high',
                'performed_by' => $users->random()->id,
                'technician_name' => 'Lisa Garcia',
                'notes' => 'Annual safety inspection overdue',
            ],
            [
                'log_number' => 'MNT-005',
                'vehicle_id' => optional($vehicles->where('vehicle_number', 'LG-002-BB')->first())->id,
                'maintenance_type' => 'scheduled',
                'category' => 'Tires',
                'maintenance_date' => now()->subDays(12),
                'next_maintenance_date' => now()->addMonths(6),
                'cost' => 280.00,
                'vendor_name' => 'TirePro Services',
                'description' => 'Tire rotation and alignment',
                'parts_replaced' => 'None - service only',
                'mileage_at_maintenance' => 52000,
                'status' => 'completed',
                'priority' => 'low',
                'performed_by' => $users->random()->id,
                'technician_name' => 'Robert Lee',
                'notes' => 'Tire rotation and alignment completed',
            ],
            [
                'log_number' => 'MNT-006',
                'vehicle_id' => optional($vehicles->where('vehicle_number', 'LG-003-CC')->first())->id,
                'maintenance_type' => 'repair',
                'category' => 'Electrical',
                'maintenance_date' => now()->addDays(6),
                'cost' => 650.00,
                'vendor_name' => 'ElectroFix Auto',
                'description' => 'Alternator replacement',
                'parts_replaced' => 'Alternator, Belt',
                'mileage_at_maintenance' => 98000,
                'status' => 'scheduled',
                'priority' => 'medium',
                'performed_by' => $users->random()->id,
                'technician_name' => 'Jennifer Kim',
                'notes' => 'Alternator showing signs of failure, scheduled for replacement',
            ],
        ];

        foreach ($maintenanceLogs as $log) {
            if ($log['vehicle_id']) {
                MaintenanceLog::create($log);
            } else {
                $this->command->warn("Skipping {$log['log_number']} — Vehicle not found.");
            }
        }

        $this->command->info('Maintenance logs seeded successfully!');
    }
}
