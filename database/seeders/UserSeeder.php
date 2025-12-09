<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'user_name' => 'superadmin',
            'email' => 'admin@courier.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'is_verified' => true,
            'phone' => '+2348012345678',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'country' => 'Nigeria',
            'is_available' => true,
            'is_tracking_active' => false,
            'current_latitude' => null,
            'current_longitude' => null,
            'last_location_update' => null,
            'current_speed' => null,
            'current_heading' => null,
        ]);

        // Manager User
        User::create([
            'first_name' => 'Branch',
            'last_name' => 'Manager',
            'user_name' => 'manager',
            'email' => 'manager@courier.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'is_verified' => true,
            'phone' => '+2348012345679',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'country' => 'Nigeria',
            'is_available' => false,
            'is_tracking_active' => false,
            'current_latitude' => null,
            'current_longitude' => null,
            'last_location_update' => null,
            'current_speed' => null,
            'current_heading' => null,
        ]);

        // Dispatcher User
        User::create([
            'first_name' => 'Dispatch',
            'last_name' => 'Officer',
            'user_name' => 'dispatcher',
            'email' => 'dispatcher@courier.com',
            'password' => Hash::make('password'),
            'role' => 'dispatcher',
            'status' => 'active',
            'is_verified' => true,
            'phone' => '+2348012345680',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'country' => 'Nigeria',
            'is_available' => false,
            'is_tracking_active' => false,
            'current_latitude' => null,
            'current_longitude' => null,
            'last_location_update' => null,
            'current_speed' => null,
            'current_heading' => null,
        ]);

        // Lagos zones for driver locations
        $lagosZones = [
            ['name' => 'Ikeja', 'lat' => 6.6018, 'lng' => 3.3515],
            ['name' => 'Yaba', 'lat' => 6.5074, 'lng' => 3.3719],
            ['name' => 'Lekki', 'lat' => 6.4474, 'lng' => 3.5414],
            ['name' => 'Victoria Island', 'lat' => 6.4281, 'lng' => 3.4219],
            ['name' => 'Surulere', 'lat' => 6.4969, 'lng' => 3.3614],
        ];

        // Driver Users
        for ($i = 1; $i <= 5; $i++) {
            $zone = $lagosZones[($i - 1) % count($lagosZones)];
            $isAvailable = $i <= 3; // First 3 drivers are available
            
            User::create([
                'first_name' => "Driver{$i}",
                'last_name' => 'User',
                'user_name' => "driver{$i}",
                'email' => "driver{$i}@courier.com",
                'password' => Hash::make('password'),
                'role' => 'driver',
                'status' => 'active',
                'is_verified' => true,
                'phone' => '+234801234' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'license_number' => 'DL' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'license_expiry' => now()->addYears(2)->format('Y-m-d'),
                'vehicle_type' => ['van', 'truck', 'bike'][$i % 3],
                'experience_years' => rand(2, 10),
                'is_available' => $isAvailable,
                'is_tracking_active' => false,
                'city' => $zone['name'],
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'rating' => rand(35, 50) / 10,
                'current_latitude' => $isAvailable ? $zone['lat'] : null,
                'current_longitude' => $isAvailable ? $zone['lng'] : null,
                'last_location_update' => $isAvailable ? now() : null,
                'current_speed' => null,
                'current_heading' => null,
            ]);
        }

        // Customer Users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'first_name' => "Customer{$i}",
                'last_name' => 'Test',
                'user_name' => "customer{$i}",
                'email' => "customer{$i}@courier.com",
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
                'is_verified' => true,
                'phone' => '+234801235' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'is_available' => false,
                'is_tracking_active' => false,
                'current_latitude' => null,
                'current_longitude' => null,
                'last_location_update' => null,
                'current_speed' => null,
                'current_heading' => null,
            ]);
        }

        $this->command->info('✅ Users seeded successfully with tracking fields!');
    }
}
