<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackageType;

class PackageTypeSeeder extends Seeder
{
    public function run(): void
    {
        $packageTypes = [
            [
                'name' => 'Document',
                'code' => 'DOC',
                'description' => 'Letters, papers, and small documents',
                'max_weight' => 0.50,
                'max_length' => 30.00,
                'max_width' => 20.00,
                'max_height' => 5.00,
                'base_price' => 500.00,
                'status' => 'active',
            ],
            [
                'name' => 'Small Parcel',
                'code' => 'SPAR',
                'description' => 'Small packages and parcels',
                'max_weight' => 5.00,
                'max_length' => 40.00,
                'max_width' => 30.00,
                'max_height' => 30.00,
                'base_price' => 1500.00,
                'status' => 'active',
            ],
            [
                'name' => 'Medium Box',
                'code' => 'MBOX',
                'description' => 'Medium sized boxes',
                'max_weight' => 20.00,
                'max_length' => 60.00,
                'max_width' => 40.00,
                'max_height' => 40.00,
                'base_price' => 3000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Large Box',
                'code' => 'LBOX',
                'description' => 'Large boxes and packages',
                'max_weight' => 50.00,
                'max_length' => 100.00,
                'max_width' => 60.00,
                'max_height' => 60.00,
                'base_price' => 5000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Pallet',
                'code' => 'PALL',
                'description' => 'Pallet shipments',
                'max_weight' => 500.00,
                'max_length' => 120.00,
                'max_width' => 100.00,
                'max_height' => 100.00,
                'base_price' => 15000.00,
                'status' => 'active',
            ],
        ];

        foreach ($packageTypes as $packageType) {
            PackageType::create($packageType);
        }
    }
}