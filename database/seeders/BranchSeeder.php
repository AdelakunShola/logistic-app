<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'branch_code' => 'BR001',
                'branch_name' => 'Lagos Main Branch',
                'email' => 'lagos@courier.com',
                'phone' => '+2348012345000',
                'address' => '123 Adeola Odeku Street, Victoria Island',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'postal_code' => '100001',
                'branch_type' => 'main',
                'status' => 'active',
                'manager_id' => 2, // Manager user
                'opening_time' => '08:00',
                'closing_time' => '18:00',
                'working_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            ],
            [
                'branch_code' => 'BR002',
                'branch_name' => 'Ikeja Branch',
                'email' => 'ikeja@courier.com',
                'phone' => '+2348012345001',
                'address' => '45 Allen Avenue, Ikeja',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'postal_code' => '100271',
                'branch_type' => 'regional',
                'status' => 'active',
                'opening_time' => '08:00',
                'closing_time' => '18:00',
                'working_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            ],
            [
                'branch_code' => 'BR003',
                'branch_name' => 'Abuja Branch',
                'email' => 'abuja@courier.com',
                'phone' => '+2348012345002',
                'address' => '78 Ahmadu Bello Way, Central Area',
                'city' => 'Abuja',
                'state' => 'FCT',
                'country' => 'Nigeria',
                'postal_code' => '900001',
                'branch_type' => 'regional',
                'status' => 'active',
                'opening_time' => '08:00',
                'closing_time' => '18:00',
                'working_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}