<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingRule;

class PricingRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            // Within Lagos Mainland - Standard
            [
                'name' => 'Lagos Mainland to Mainland - Standard',
                'from_zone_id' => 1,
                'to_zone_id' => 1,
                'service_type' => 'standard',
                'base_price' => 1500.00,
                'price_per_kg' => 100.00,
                'min_weight' => 0.00,
                'max_weight' => 50.00,
                'fuel_surcharge_percentage' => 5.00,
                'tax_percentage' => 7.50,
                'insurance_percentage' => 2.00,
                'estimated_delivery_days' => 1,
                'status' => 'active',
            ],
            // Lagos Mainland to Island - Express
            [
                'name' => 'Lagos Mainland to Island - Express',
                'from_zone_id' => 1,
                'to_zone_id' => 2,
                'service_type' => 'express',
                'base_price' => 3000.00,
                'price_per_kg' => 150.00,
                'min_weight' => 0.00,
                'max_weight' => 30.00,
                'fuel_surcharge_percentage' => 5.00,
                'tax_percentage' => 7.50,
                'insurance_percentage' => 2.00,
                'estimated_delivery_days' => 1,
                'status' => 'active',
            ],
            // Lagos to Abuja - Standard
            [
                'name' => 'Lagos to Abuja - Standard',
                'from_zone_id' => 1,
                'to_zone_id' => 3,
                'service_type' => 'standard',
                'base_price' => 5000.00,
                'price_per_kg' => 200.00,
                'min_weight' => 0.00,
                'max_weight' => 100.00,
                'fuel_surcharge_percentage' => 8.00,
                'tax_percentage' => 7.50,
                'insurance_percentage' => 3.00,
                'estimated_delivery_days' => 3,
                'status' => 'active',
            ],
            // Same Day Delivery
            [
                'name' => 'Lagos Mainland - Same Day',
                'from_zone_id' => 1,
                'to_zone_id' => 1,
                'service_type' => 'same_day',
                'base_price' => 2500.00,
                'price_per_kg' => 200.00,
                'min_weight' => 0.00,
                'max_weight' => 10.00,
                'fuel_surcharge_percentage' => 5.00,
                'tax_percentage' => 7.50,
                'insurance_percentage' => 2.00,
                'estimated_delivery_days' => 0,
                'status' => 'active',
            ],
        ];

        foreach ($rules as $rule) {
            PricingRule::create($rule);
        }
    }
}