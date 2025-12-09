<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $customers = User::where('role', 'customer')->pluck('id')->toArray();
        $drivers = User::where('role', 'driver')->pluck('id')->toArray();

        if (empty($customers)) {
            $this->command->warn('No customers found. Please run UserSeeder first.');
            return;
        }

        $statuses = ['pending', 'processing', 'confirmed', 'assigned', 'in_transit', 'in_progress', 'delivered', 'completed', 'delayed', 'cancelled'];
        $priorities = ['low', 'medium', 'high'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'cash'];
        $orderTypes = ['delivery', 'pickup', 'return', 'exchange'];
        $shippingMethods = ['standard', 'express', 'overnight'];

        $items = [
            ['name' => 'Laptop Computer', 'price' => 1200.00],
            ['name' => 'Wireless Mouse', 'price' => 25.99],
            ['name' => 'Keyboard', 'price' => 79.99],
            ['name' => 'Monitor 27"', 'price' => 350.00],
            ['name' => 'USB-C Cable', 'price' => 15.99],
            ['name' => 'Desk Lamp', 'price' => 45.00],
            ['name' => 'Office Chair', 'price' => 299.99],
            ['name' => 'Webcam HD', 'price' => 89.99],
            ['name' => 'Headphones', 'price' => 149.99],
            ['name' => 'Smartphone', 'price' => 899.99],
            ['name' => 'Tablet', 'price' => 499.99],
            ['name' => 'Smart Watch', 'price' => 299.99],
            ['name' => 'External SSD 1TB', 'price' => 129.99],
            ['name' => 'Printer', 'price' => 199.99],
            ['name' => 'Scanner', 'price' => 149.99],
        ];

        $addresses = [
            ['street' => '123 Main Street', 'city' => 'New York', 'state' => 'NY', 'zip' => '10001', 'country' => 'United States'],
            ['street' => '456 Oak Avenue', 'city' => 'Los Angeles', 'state' => 'CA', 'zip' => '90001', 'country' => 'United States'],
            ['street' => '789 Pine Road', 'city' => 'Chicago', 'state' => 'IL', 'zip' => '60601', 'country' => 'United States'],
            ['street' => '321 Elm Street', 'city' => 'Houston', 'state' => 'TX', 'zip' => '77001', 'country' => 'United States'],
            ['street' => '654 Maple Drive', 'city' => 'Phoenix', 'state' => 'AZ', 'zip' => '85001', 'country' => 'United States'],
            ['street' => '987 Cedar Lane', 'city' => 'Philadelphia', 'state' => 'PA', 'zip' => '19101', 'country' => 'United States'],
            ['street' => '147 Birch Court', 'city' => 'San Antonio', 'state' => 'TX', 'zip' => '78201', 'country' => 'United States'],
            ['street' => '258 Willow Way', 'city' => 'San Diego', 'state' => 'CA', 'zip' => '92101', 'country' => 'United States'],
            ['street' => '369 Spruce Boulevard', 'city' => 'Dallas', 'state' => 'TX', 'zip' => '75201', 'country' => 'United States'],
            ['street' => '741 Ash Avenue', 'city' => 'San Jose', 'state' => 'CA', 'zip' => '95101', 'country' => 'United States'],
        ];

        $this->command->info('Creating 50 sample orders...');

        for ($i = 1; $i <= 50; $i++) {
            $customer = User::find($customers[array_rand($customers)]);
            $address = $addresses[array_rand($addresses)];
            $pickupAddress = $addresses[array_rand($addresses)];
            
            // Generate random items for this order
            $orderItems = [];
            $numberOfItems = rand(1, 4);
            $orderValue = 0;
            
            for ($j = 0; $j < $numberOfItems; $j++) {
                $item = $items[array_rand($items)];
                $quantity = rand(1, 3);
                $orderItems[] = [
                    'name' => $item['name'],
                    'quantity' => $quantity,
                    'price' => $item['price']
                ];
                $orderValue += ($item['price'] * $quantity);
            }

            $serviceCharge = round($orderValue * 0.03, 2); // 3% service charge
            $taxAmount = round($orderValue * 0.08, 2); // 8% tax
            $shippingCost = rand(10, 50);
            $totalAmount = $orderValue + $serviceCharge + $taxAmount + $shippingCost;

            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $orderType = $orderTypes[array_rand($orderTypes)];
            
            // Adjust delivery progress based on status
            $deliveryProgress = match($status) {
                'pending' => 0,
                'processing' => rand(10, 20),
                'confirmed' => rand(20, 30),
                'assigned' => rand(30, 40),
                'in_transit', 'in_progress' => rand(40, 80),
                'delivered', 'completed' => 100,
                'delayed' => rand(30, 70),
                'cancelled' => rand(0, 50),
                default => 0
            };

            $orderDate = Carbon::now()->subDays(rand(1, 60));
            $scheduledDate = (clone $orderDate)->addDays(rand(1, 7));

            // Generate time slots
            $timeSlots = [
                ['08:00:00', '10:00:00'],
                ['10:00:00', '12:00:00'],
                ['12:00:00', '14:00:00'],
                ['14:00:00', '16:00:00'],
                ['16:00:00', '18:00:00'],
            ];
            $timeSlot = $timeSlots[array_rand($timeSlots)];

            Order::create([
                'order_number' => 'ORD-' . date('Y') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                
                // Order Details
                'order_type' => $orderType,
                'order_date' => $orderDate,
                'scheduled_date' => $scheduledDate,
                'scheduled_time_from' => $timeSlot[0],
                'scheduled_time_to' => $timeSlot[1],
                
                // Status & Priority
                'status' => $status,
                'priority' => $priorities[array_rand($priorities)],
                
                // Addresses
                'pickup_address' => $orderType === 'pickup' ? implode(', ', $pickupAddress) : null,
                'delivery_address' => implode(', ', $address),
                'street_address' => $address['street'],
                'city' => $address['city'],
                'state' => $address['state'],
                'zip_code' => $address['zip'],
                'country' => $address['country'],
                
                // Order Items & Financials
                'items' => json_encode($orderItems),
                'order_value' => $orderValue,
                'service_charge' => $serviceCharge,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                
                // Payment Information
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_terms' => rand(0, 1) ? ['net_30', 'net_60', 'due_on_receipt'][rand(0, 2)] : null,
                
                // Shipping & Tracking
                'tracking_number' => rand(0, 1) ? 'TRK' . strtoupper(bin2hex(random_bytes(8))) : null,
                'shipping_method' => $shippingMethods[array_rand($shippingMethods)],
                'delivery_progress' => $deliveryProgress,
                
                // Customer Information
                'customer_phone' => '+1' . rand(1000000000, 9999999999),
                'customer_company' => rand(0, 1) ? 'Company ' . chr(65 + rand(0, 25)) : null,
                'customer_email' => $customer->email,
                
                // Additional Information
                'assigned_driver_id' => (!empty($drivers) && in_array($status, ['assigned', 'in_transit', 'in_progress', 'delivered', 'completed'])) 
                    ? $drivers[array_rand($drivers)] 
                    : null,
                'special_instructions' => rand(0, 1) ? 'Please handle with care. Ring doorbell upon arrival.' : null,
                'internal_notes' => rand(0, 1) ? 'Customer requested expedited shipping.' : null,
                'cancellation_reason' => $status === 'cancelled' ? 'Customer requested cancellation' : null,
                
                // Timestamps
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            if ($i % 10 == 0) {
                $this->command->info("Created {$i} orders...");
            }
        }

        $this->command->info('✓ Successfully created 50 sample orders!');
    }
}