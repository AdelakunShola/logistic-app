<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pricing Configuration Settings
        $pricingSettings = [
            // Currency Settings
            [
                'key' => 'pricing_currency',
                'value' => 'USD',
                'group' => 'pricing',
                'type' => 'text',
                'description' => 'Currency code for pricing display',
            ],
            [
                'key' => 'pricing_currency_symbol',
                'value' => '$',
                'group' => 'pricing',
                'type' => 'text',
                'description' => 'Currency symbol for pricing display',
            ],
            
            // Standard Package Pricing
            [
                'key' => 'pricing_standard_package_standard',
                'value' => '15.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Standard Package - Standard Delivery (5-7 days)',
            ],
            [
                'key' => 'pricing_standard_package_express',
                'value' => '29.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Standard Package - Express Delivery (2-3 days)',
            ],
            [
                'key' => 'pricing_standard_package_overnight',
                'value' => '49.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Standard Package - Overnight Delivery',
            ],
            
            // Document Envelope Pricing
            [
                'key' => 'pricing_document_envelope_standard',
                'value' => '9.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Document Envelope - Standard Delivery (5-7 days)',
            ],
            [
                'key' => 'pricing_document_envelope_express',
                'value' => '19.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Document Envelope - Express Delivery (2-3 days)',
            ],
            [
                'key' => 'pricing_document_envelope_overnight',
                'value' => '34.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Document Envelope - Overnight Delivery',
            ],
            
            // Freight/Pallet Pricing
            [
                'key' => 'pricing_freight_pallet_standard',
                'value' => '99.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Freight/Pallet - Standard Delivery (5-7 days)',
            ],
            [
                'key' => 'pricing_freight_pallet_express',
                'value' => '149.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Freight/Pallet - Express Delivery (2-3 days)',
            ],
            [
                'key' => 'pricing_freight_pallet_overnight',
                'value' => '249.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Freight/Pallet - Overnight Delivery',
            ],
            
            // Bulk Cargo Pricing
            [
                'key' => 'pricing_bulk_cargo_standard',
                'value' => '199.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Bulk Cargo - Standard Delivery (5-7 days)',
            ],
            [
                'key' => 'pricing_bulk_cargo_express',
                'value' => '299.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Bulk Cargo - Express Delivery (2-3 days)',
            ],
            [
                'key' => 'pricing_bulk_cargo_overnight',
                'value' => '449.99',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Bulk Cargo - Overnight Delivery',
            ],
            
            // Weight Charges
            [
                'key' => 'pricing_weight_threshold',
                'value' => '10',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Weight threshold in lbs (charges apply above this)',
            ],
            [
                'key' => 'pricing_weight_rate_per_lb',
                'value' => '0.50',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Rate per lb over threshold',
            ],
            
            // Distance Charges
            [
                'key' => 'pricing_distance_rate_per_mile',
                'value' => '0.75',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Rate per mile when distance is calculable',
            ],
            
            // Zone-Based Flat Rates
            [
                'key' => 'pricing_zone_local',
                'value' => '5.00',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Flat rate for local delivery (within same city)',
            ],
            [
                'key' => 'pricing_zone_regional',
                'value' => '15.00',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Flat rate for regional delivery (within same state)',
            ],
            [
                'key' => 'pricing_zone_national',
                'value' => '35.00',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Flat rate for national delivery (different states, same country)',
            ],
            [
                'key' => 'pricing_zone_international',
                'value' => '100.00',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Flat rate for international delivery (different countries)',
            ],
            
            // Additional Services
            [
                'key' => 'pricing_insurance_rate',
                'value' => '2',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Insurance fee as percentage of insured amount',
            ],
            [
                'key' => 'pricing_signature_fee',
                'value' => '5.00',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Signature required service fee',
            ],
            [
                'key' => 'pricing_temperature_controlled_fee',
                'value' => '25.00',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Temperature controlled service fee',
            ],
            [
                'key' => 'pricing_fragile_handling_fee',
                'value' => '10.00',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Fragile handling service fee',
            ],
            
            // Tax
            [
                'key' => 'pricing_tax_percentage',
                'value' => '10',
                'group' => 'pricing',
                'type' => 'number',
                'description' => 'Tax percentage applied to shipments',
            ],
        ];

        foreach ($pricingSettings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all pricing settings
        DB::table('settings')->where('group', 'pricing')->delete();
    }
};
