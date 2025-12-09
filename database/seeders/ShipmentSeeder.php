<?php

namespace Database\Seeders;

use App\Models\Carriers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shipment;
use App\Models\Vehicle;
use App\Models\Hub;
use App\Models\Warehouse;

class ShipmentSeeder extends Seeder
{
    // Lagos zones with accurate coordinates
    private $lagosZones = [
        'Ikeja' => ['lat' => 6.6018, 'lng' => 3.3515],
        'Yaba' => ['lat' => 6.5074, 'lng' => 3.3719],
        'Surulere' => ['lat' => 6.4969, 'lng' => 3.3614],
        'Lekki' => ['lat' => 6.4474, 'lng' => 3.5414],
        'Ajah' => ['lat' => 6.4667, 'lng' => 3.5667],
        'Ikoyi' => ['lat' => 6.4553, 'lng' => 3.4316],
        'Victoria Island' => ['lat' => 6.4281, 'lng' => 3.4219],
        'Maryland' => ['lat' => 6.5794, 'lng' => 3.3594],
        'Oshodi' => ['lat' => 6.5451, 'lng' => 3.3364],
        'Agege' => ['lat' => 6.6158, 'lng' => 3.3181],
        'Ojota' => ['lat' => 6.5897, 'lng' => 3.3792],
        'Apapa' => ['lat' => 6.4489, 'lng' => 3.3594],
        'Festac' => ['lat' => 6.4644, 'lng' => 3.2808],
        'Mushin' => ['lat' => 6.5320, 'lng' => 3.3426],
        'Isolo' => ['lat' => 6.5370, 'lng' => 3.3352],
    ];

    public function run()
    {
        $this->command->info('Starting Lagos Shipment Seeder with Different Coordinates...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            Shipment::truncate();

            // Fetch related IDs
            $customers = User::where('role', 'customer')->pluck('id')->toArray();
            $drivers   = User::where('role', 'driver')->pluck('id')->toArray();
            $carriers  = Carriers::pluck('id')->toArray();
            $vehicles  = Schema::hasTable('vehicles') ? Vehicle::pluck('id')->toArray() : [];
            $warehouses = Schema::hasTable('warehouses') ? Warehouse::pluck('id')->toArray() : [];
            $hubs      = Schema::hasTable('hubs') ? Hub::pluck('id')->toArray() : [];

            if (empty($customers)) {
                $this->command->warn('No customers found. Creating sample customers...');
                $customers = $this->createSampleUsers('customer', 5);
            }
            if (empty($drivers)) {
                $this->command->warn('No drivers found. Creating sample drivers...');
                $drivers = $this->createSampleUsers('driver', 5);
            }
            if (empty($carriers)) {
                $this->command->warn('No carriers found. Creating sample carriers...');
                $carriers = $this->createSampleCarriers();
            }

            // Get zone names
            $zoneNames = array_keys($this->lagosZones);

            // Postal codes
            $postalCodes = [
                '100001', '100242', '100283', '100271',
                '101233', '101245', '101241', '105102'
            ];

            $statuses = ['pending', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered'];
            $priorities = ['standard', 'express', 'overnight'];
            $shipmentTypes = ['Standard Package', 'Document Envelope', 'Freight/Pallet'];
            $paymentModes = ['prepaid', 'cod'];

            $this->command->info('Creating 50 Lagos shipments with different pickup/delivery locations...');
            $success = 0;

            for ($i = 1; $i <= 50; $i++) {
                try {
                    // Select DIFFERENT zones for pickup and delivery
                    $pickupZone = $zoneNames[array_rand($zoneNames)];
                    do {
                        $deliveryZone = $zoneNames[array_rand($zoneNames)];
                    } while ($deliveryZone === $pickupZone); // Ensure different zones

                    // Get base coordinates and add random variation (±0.01 degrees ~1km)
                    $pickupLat = $this->lagosZones[$pickupZone]['lat'] + (rand(-100, 100) / 10000);
                    $pickupLng = $this->lagosZones[$pickupZone]['lng'] + (rand(-100, 100) / 10000);
                    
                    $deliveryLat = $this->lagosZones[$deliveryZone]['lat'] + (rand(-100, 100) / 10000);
                    $deliveryLng = $this->lagosZones[$deliveryZone]['lng'] + (rand(-100, 100) / 10000);

                    // Dates
                    $pickupDate = Carbon::now()->subDays(rand(1, 60));
                    $expectedDelivery = $pickupDate->copy()->addDays(rand(2, 7));
                    
                    // Status and assignment
                    $status = $statuses[array_rand($statuses)];
                    $priority = $priorities[array_rand($priorities)];
                    $paymentMode = $paymentModes[array_rand($paymentModes)];
                    
                    // Only assign driver for active statuses
                    $assignedDriver = in_array($status, ['picked_up', 'in_transit', 'out_for_delivery']) 
                        ? $drivers[array_rand($drivers)] 
                        : null;
                    
                    $actualDelivery = $status === 'delivered'
                        ? $expectedDelivery->copy()->addDays(rand(-1, 2))
                        : null;

                    // Pricing
                    $basePrice = rand(5000, 20000);
                    $weightCharge = rand(500, 3000);
                    $distanceCharge = rand(1000, 5000);
                    $priorityCharge = $priority === 'overnight' ? rand(1000, 3000) : 
                                    ($priority === 'express' ? rand(500, 1500) : 0);
                    $taxAmount = rand(200, 1000);
                    $insuranceFee = rand(0, 2000);
                    $additionalFee = rand(0, 2000);
                    $totalAmount = $basePrice + $weightCharge + $distanceCharge + $priorityCharge + 
                                 $taxAmount + $insuranceFee + $additionalFee;

                    $shipmentData = [
                        // Identifiers
                        'tracking_number'   => 'TRK' . str_pad($i, 8, '0', STR_PAD_LEFT),
                        'reference_number'  => 'REF' . str_pad($i, 6, '0', STR_PAD_LEFT),

                        // Customer & Carrier
                        'customer_id'       => $customers[array_rand($customers)],
                        'sender_id'         => $customers[array_rand($customers)],
                        'carrier_id'        => $carriers[array_rand($carriers)],

                        // Pickup (Different Lagos zone)
                        'pickup_company_name'    => 'Lagos Pickup Co ' . $i,
                        'pickup_contact_name'    => 'Sender ' . $i,
                        'pickup_contact_phone'   => '+23480' . rand(10000000, 99999999),
                        'pickup_contact_email'   => 'sender' . $i . '@example.com',
                        'pickup_address'         => rand(10, 200) . ' Street ' . $pickupZone,
                        'pickup_address_line2'   => 'Suite ' . rand(1, 50),
                        'pickup_city'            => $pickupZone,
                        'pickup_state'           => 'Lagos',
                        'pickup_country'         => 'Nigeria',
                        'pickup_postal_code'     => $postalCodes[array_rand($postalCodes)],
                        'pickup_latitude'        => $pickupLat,
                        'pickup_longitude'       => $pickupLng,

                        // Delivery (Different Lagos zone)
                        'delivery_company_name'  => 'Lagos Delivery Co ' . $i,
                        'delivery_contact_name'  => 'Receiver ' . $i,
                        'delivery_contact_phone' => '+23480' . rand(10000000, 99999999),
                        'delivery_contact_email' => 'receiver' . $i . '@example.com',
                        'delivery_address'       => rand(10, 200) . ' Avenue ' . $deliveryZone,
                        'delivery_address_line2' => 'Apt ' . rand(1, 100),
                        'delivery_city'          => $deliveryZone,
                        'delivery_state'         => 'Lagos',
                        'delivery_country'       => 'Nigeria',
                        'delivery_postal_code'   => $postalCodes[array_rand($postalCodes)],
                        'delivery_latitude'      => $deliveryLat,
                        'delivery_longitude'     => $deliveryLng,

                        // Route fields (initially null, calculated later)
                        'calculated_route'       => null,
                        'route_distance'         => null,
                        'estimated_duration'     => null,
                        'route_calculated_at'    => null,
                        'driver_started_at'      => $assignedDriver && in_array($status, ['in_transit', 'out_for_delivery', 'delivered']) 
                            ? $pickupDate->copy()->addHours(rand(1, 3)) 
                            : null,
                        'driver_arrived_at'      => $status === 'delivered' 
                            ? $actualDelivery 
                            : null,

                        // Package details
                        'shipment_type'      => $shipmentTypes[array_rand($shipmentTypes)],
                        'number_of_items'    => rand(1, 5),
                        'total_weight'       => rand(1, 50),
                        'total_value'        => rand(2000, 50000),

                        // Service details
                        'delivery_priority'  => $priority,
                        'payment_mode'       => $paymentMode,
                        'cod_amount'         => $paymentMode === 'cod' ? rand(1000, 20000) : 0,

                        // Special services
                        'insurance_required' => rand(0, 1),
                        'insurance_amount'   => rand(0, 1) ? rand(1000, 10000) : 0,
                        'signature_required' => rand(0, 1),
                        'temperature_controlled' => rand(0, 1),
                        'fragile_handling'   => rand(0, 1),
                        'service_level'      => ['ground', 'air', 'express'][rand(0, 2)],

                        // Assignment
                        'status'                 => $status,
                        'assigned_driver_id'     => $assignedDriver,
                        'assigned_vehicle_id'    => $assignedDriver && !empty($vehicles) 
                            ? $vehicles[array_rand($vehicles)] 
                            : null,
                        'current_warehouse_id'   => !empty($warehouses) 
                            ? $warehouses[array_rand($warehouses)] 
                            : null,
                        'current_hub_id'         => !empty($hubs) 
                            ? $hubs[array_rand($hubs)] 
                            : null,
                        'origin_warehouse_id'    => !empty($warehouses) 
                            ? $warehouses[array_rand($warehouses)] 
                            : null,
                        'destination_warehouse_id' => !empty($warehouses) 
                            ? $warehouses[array_rand($warehouses)] 
                            : null,

                        // Pricing
                        'base_price'             => $basePrice,
                        'weight_charge'          => $weightCharge,
                        'distance_charge'        => $distanceCharge,
                        'priority_charge'        => $priorityCharge,
                        'tax_amount'             => $taxAmount,
                        'discount_amount'        => 0,
                        'insurance_fee'          => $insuranceFee,
                        'additional_services_fee' => $additionalFee,
                        'total_amount'           => $totalAmount,

                        // Dates
                        'pickup_date'            => $pickupDate,
                        'preferred_delivery_date' => $expectedDelivery,
                        'expected_delivery_date' => $expectedDelivery,
                        'actual_delivery_date'   => $actualDelivery,
                        'pickup_scheduled_date'  => $pickupDate->copy()->subDay(),

                        // Tracking
                        'delivery_attempts'      => $status === 'delivered' ? rand(1, 2) : 0,
                        'customer_rating'        => $status === 'delivered' ? rand(3, 5) : null,
                        'customer_feedback'      => $status === 'delivered' ? 'Good service' : null,

                        // Timestamps
                        'created_at'             => $pickupDate,
                        'updated_at'             => now(),
                    ];

                    Shipment::create($shipmentData);
                    $success++;

                    if ($i % 10 === 0) {
                        $this->command->info("Created {$i} shipments...");
                    }

                } catch (\Exception $e) {
                    $this->command->error("Failed shipment {$i}: " . $e->getMessage());
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->command->info("✅ Lagos Shipment Seeder Completed!");
            $this->command->info("Successfully created: {$success} shipments");
            $this->command->info("All shipments have different pickup and delivery coordinates!");

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->error("Seeder error: " . $e->getMessage());
            throw $e;
        }
    }

    private function createSampleUsers($role, $count)
    {
        $this->command->info("Creating {$count} sample {$role} users...");
        $ids = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $user = User::create([
                'user_name' => $role . $i,
                'first_name' => ucfirst($role),
                'last_name' => 'User' . $i,
                'email' => $role . $i . '@example.com',
                'password' => bcrypt('password'),
                'role' => $role,
                'status' => 'active',
                'phone' => '+23480' . rand(10000000, 99999999),
                'is_available' => $role === 'driver',
                'is_tracking_active' => false,
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
            ]);
            $ids[] = $user->id;
        }
        
        return $ids;
    }

    private function createSampleCarriers()
    {
        $this->command->info("Creating sample carriers...");
        $names = ['GIG Logistics', 'Kobo360', 'Sendbox', 'DHL Nigeria'];
        $ids = [];

        foreach ($names as $name) {
            $carrier = Carriers::create([
                'name' => $name,
                'code' => strtoupper(substr(str_replace(' ', '', $name), 0, 3)),
                'contact_email' => 'contact@' . strtolower(str_replace(' ', '', $name)) . '.com',
                'contact_phone' => '+23480' . rand(10000000, 99999999),
                'status' => 'active',
            ]);
            $ids[] = $carrier->id;
        }

        return $ids;
    }
}