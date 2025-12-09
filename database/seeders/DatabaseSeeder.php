<?php

// 1. DatabaseSeeder.php - Main seeder
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            WarehouseSeeder::class,
            BranchSeeder::class,
            HubSeeder::class,
            VehicleSeeder::class,
            CustomerSeeder::class,
            PricingZoneSeeder::class,
            PricingRuleSeeder::class,
            PackageTypeSeeder::class,
            SettingSeeder::class,
            EmailTemplateSeeder::class,
            SmsTemplateSeeder::class,
            CarrierSeeder::class,
            ShipmentSeeder::class,
            MaintenanceLogSeeder::class,
            ShipmentDelaySeeder::class,
            ShipmentIssueSeeder::class,
            OrderSeeder::class,
            
        ]);
    }
}












