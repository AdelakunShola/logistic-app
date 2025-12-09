<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hub;

class HubSeeder extends Seeder
{
    public function run(): void
    {
        $hubs = [
            [
                'hub_code' => 'HUB001',
                'hub_name' => 'Lagos Main Warehouse',
                'hub_type' => 'warehouse',
                'branch_id' => 1,
                'email' => 'warehouse.lagos@courier.com',
                'phone' => '+2348012346000',
                'address' => '100 Apapa-Oshodi Expressway',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'postal_code' => '100001',
                'storage_capacity' => 5000.00,
                'current_occupancy' => 1200.00,
                'status' => 'active',
                'opening_time' => '06:00',
                'closing_time' => '22:00',
            ],
            [
                'hub_code' => 'HUB002',
                'hub_name' => 'Ikeja Distribution Center',
                'hub_type' => 'distribution_center',
                'branch_id' => 2,
                'email' => 'dc.ikeja@courier.com',
                'phone' => '+2348012346001',
                'address' => '25 Mobolaji Bank Anthony Way',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'postal_code' => '100271',
                'storage_capacity' => 2000.00,
                'current_occupancy' => 500.00,
                'status' => 'active',
                'opening_time' => '07:00',
                'closing_time' => '20:00',
            ],
            [
                'hub_code' => 'HUB003',
                'hub_name' => 'Abuja Sorting Facility',
                'hub_type' => 'sorting_facility',
                'branch_id' => 3,
                'email' => 'sort.abuja@courier.com',
                'phone' => '+2348012346002',
                'address' => '150 Nnamdi Azikiwe Expressway',
                'city' => 'Abuja',
                'state' => 'FCT',
                'country' => 'Nigeria',
                'postal_code' => '900001',
                'storage_capacity' => 1500.00,
                'current_occupancy' => 300.00,
                'status' => 'active',
                'opening_time' => '06:00',
                'closing_time' => '22:00',
            ],
        ];

        foreach ($hubs as $hub) {
            Hub::create($hub);
        }
    }
}