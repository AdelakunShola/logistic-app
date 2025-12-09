<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\ShipmentDelay;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipmentDelaySeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚚 Starting Shipment Delay Seeder...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Clear old data
            $this->command->info('Clearing existing shipment delays...');
            ShipmentDelay::truncate();

            // Fetch shipments that are still active
            $shipments = Shipment::whereIn('status', ['in_transit', 'pending', 'out_for_delivery'])
                ->with(['assignedDriver', 'customer'])
                ->get();

            if ($shipments->isEmpty()) {
                $this->command->warn('⚠️ No eligible shipments found for delay creation.');
                $this->command->warn('Run: php artisan db:seed --class=ShipmentSeeder');
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                return;
            }

            $this->command->info("Found {$shipments->count()} eligible shipments.");

            // Updated to match your database ENUM options
            $delayReasons = [
                'traffic_congestion',
                'weather_conditions',
                'vehicle_issues',
                'address_issues',
                'customer_unavailable',
                'customs_delay',
                'port_congestion',
                'documentation_issues',
                'mechanical_failure',
                'road_closure',
                'other',
            ];

            $delayDescriptions = [
                'traffic_congestion' => 'Heavy traffic congestion on major delivery routes.',
                'weather_conditions' => 'Severe weather conditions impacting delivery schedules.',
                'vehicle_issues' => 'Vehicle maintenance or performance problems causing delay.',
                'address_issues' => 'Incorrect or incomplete address provided by customer.',
                'customer_unavailable' => 'Customer was not available at delivery time.',
                'customs_delay' => 'Shipment held up due to customs inspection or clearance.',
                'port_congestion' => 'Delays at port due to congestion and processing backlogs.',
                'documentation_issues' => 'Missing or incorrect shipment documents.',
                'mechanical_failure' => 'Mechanical failure of transport equipment.',
                'road_closure' => 'Unexpected road closure or detour along delivery route.',
                'other' => 'Other unforeseen operational delays.',
            ];

            $reporters = User::whereIn('role', ['admin', 'driver'])->pluck('id')->toArray();
            if (empty($reporters)) {
                $this->command->warn('⚠️ No admin/driver users found. Defaulting to user ID 1.');
                $reporters = [1];
            }

            // Delay types by severity
            $delaysToCreate = [
                ['delay_hours' => rand(72, 96), 'severity' => 'critical'],
                ['delay_hours' => rand(48, 71), 'severity' => 'high'],
                ['delay_hours' => rand(48, 60), 'severity' => 'high'],
                ['delay_hours' => rand(24, 47), 'severity' => 'medium'],
                ['delay_hours' => rand(8, 23), 'severity' => 'low'],
            ];

            $successCount = 0;

            foreach ($delaysToCreate as $delayConfig) {
                try {
                    $shipment = $shipments->random();

                    $delayReason = $delayReasons[array_rand($delayReasons)];
                    $delayHours = $delayConfig['delay_hours'];
                    $delayedAt = Carbon::now()->subHours($delayHours);

                    // Safely handle delivery dates
                    $originalDelivery = $shipment->expected_delivery_date
                        ? Carbon::parse($shipment->expected_delivery_date)
                        : Carbon::now()->addDays(2);

                    $newDelivery = $originalDelivery->copy()->addHours($delayHours);

                    $delayData = [
                        'shipment_id' => $shipment->id,
                        'driver_id' => $shipment->assigned_driver_id ?? null,
                        'delay_reason' => $delayReason,
                        'delay_description' => $delayDescriptions[$delayReason] .
                            "\n\nReported at: " . $delayedAt->format('Y-m-d H:i:s'),
                        'delay_duration_minutes' => $delayHours * 60,
                        'delayed_at' => $delayedAt,
                        'resolved_at' => null,
                        'original_delivery_date' => $originalDelivery,
                        'new_delivery_date' => $newDelivery,
                        'delay_hours' => $delayHours,
                        'reported_by' => $reporters[array_rand($reporters)],
                        'customer_notified' => (bool)rand(0, 1),
                        'created_at' => $delayedAt,
                        'updated_at' => now(),
                    ];

                    ShipmentDelay::create($delayData);

                    // Update shipment expected delivery
                    $shipment->update(['expected_delivery_date' => $newDelivery]);

                    $successCount++;
                    $this->command->info("✅ Created {$delayConfig['severity']} delay ({$delayHours}h) for shipment #{$shipment->id}");
                } catch (\Exception $e) {
                    $this->command->error("❌ Failed to create delay: " . $e->getMessage());
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->command->info("\n🎯 Seeder Summary");
            $this->command->info("Total delays created: {$successCount}");
            $this->command->info("- Critical: 1");
            $this->command->info("- High: 2");
            $this->command->info("- Medium: 1");
            $this->command->info("- Low: 1");
            $this->command->info("✅ Shipment Delay Seeder completed successfully!");

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->error('❌ Shipment Delay Seeder failed: ' . $e->getMessage());
            $this->command->error($e->getTraceAsString());
        }
    }
}
