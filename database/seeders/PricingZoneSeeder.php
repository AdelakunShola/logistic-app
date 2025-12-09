<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingZone;

class PricingZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'zone_name' => 'Lagos Mainland',
                'zone_code' => 'ZONE-LG-MAIN',
                'cities' => json_encode(['Lagos', 'Ikeja', 'Surulere', 'Yaba', 'Ebute Metta']),
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'zone_type' => 'local',
                'status' => 'active',
            ],
            [
                'zone_name' => 'Lagos Island',
                'zone_code' => 'ZONE-LG-ISL',
                'cities' => json_encode(['Victoria Island', 'Ikoyi', 'Lekki', 'Ajah', 'Marina']),
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'zone_type' => 'local',
                'status' => 'active',
            ],
            [
                'zone_name' => 'FCT Abuja',
                'zone_code' => 'ZONE-ABJ',
                'cities' => json_encode(['Abuja', 'Wuse', 'Garki', 'Maitama', 'Asokoro']),
                'state' => 'FCT',
                'country' => 'Nigeria',
                'zone_type' => 'regional',
                'status' => 'active',
            ],
            [
                'zone_name' => 'Southwest Nigeria',
                'zone_code' => 'ZONE-SW',
                'cities' => json_encode(['Ibadan', 'Abeokuta', 'Akure', 'Ilorin', 'Ado Ekiti']),
                'state' => null,
                'country' => 'Nigeria',
                'zone_type' => 'regional',
                'status' => 'active',
            ],
        ];

        foreach ($zones as $zone) {
            PricingZone::create($zone);
        }
    }
}
