<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'customer_code' => 'CUST001',
                'customer_type' => 'business',
                'name' => 'Shoprite Nigeria',
                'contact_person' => 'John Doe',
                'email' => 'procurement@shoprite.ng',
                'phone' => '+2348012347001',
                'address' => '100 Lekki-Epe Expressway',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'postal_code' => '100001',
                'company_name' => 'Shoprite Nigeria Limited',
                'tax_id' => 'TIN-001234567',
                'status' => 'active',
                'credit_limit' => 500000.00,
                'payment_terms' => 'credit',
                'credit_days' => 30,
                'discount_percentage' => 10.00,
            ],
            [
                'customer_code' => 'CUST002',
                'customer_type' => 'business',
                'name' => 'Jumia Nigeria',
                'contact_person' => 'Jane Smith',
                'email' => 'logistics@jumia.com.ng',
                'phone' => '+2348012347002',
                'address' => '38 Glover Road, Ikoyi',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'postal_code' => '100001',
                'company_name' => 'Jumia Nigeria',
                'tax_id' => 'TIN-001234568',
                'status' => 'active',
                'credit_limit' => 1000000.00,
                'payment_terms' => 'credit',
                'credit_days' => 45,
                'discount_percentage' => 15.00,
            ],
            [
                'customer_code' => 'CUST003',
                'customer_type' => 'individual',
                'name' => 'Ibrahim Musa',
                'email' => 'ibrahim.musa@email.com',
                'phone' => '+2348012347003',
                'address' => '25 Wuse Zone 3',
                'city' => 'Abuja',
                'state' => 'FCT',
                'country' => 'Nigeria',
                'postal_code' => '900001',
                'status' => 'active',
                'payment_terms' => 'prepaid',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}