<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarrierSeeder extends Seeder
{
    public function run()
    {
        $carriers = [
            ['name' => 'Express Logistics', 'code' => 'EXL', 'contact_email' => 'contact@expresslogistics.com', 'contact_phone' => '+1-555-0101', 'status' => 'active'],
            ['name' => 'Swift Carriers', 'code' => 'SWC', 'contact_email' => 'info@swiftcarriers.com', 'contact_phone' => '+1-555-0102', 'status' => 'active'],
            ['name' => 'Global Shipping', 'code' => 'GLS', 'contact_email' => 'support@globalshipping.com', 'contact_phone' => '+1-555-0103', 'status' => 'active'],
            ['name' => 'Metro Delivery', 'code' => 'MTD', 'contact_email' => 'hello@metrodelivery.com', 'contact_phone' => '+1-555-0104', 'status' => 'active'],
        ];

        foreach ($carriers as $carrier) {
            DB::table('carriers')->insert(array_merge($carrier, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}