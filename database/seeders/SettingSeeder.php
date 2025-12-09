<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'app_name', 'value' => 'CourierMax ERP', 'group' => 'general', 'type' => 'text', 'description' => 'Application name'],
            ['key' => 'app_logo', 'value' => '/images/logo.png', 'group' => 'general', 'type' => 'text', 'description' => 'Application logo path'],
            ['key' => 'currency', 'value' => 'USD', 'group' => 'general', 'type' => 'text', 'description' => 'Default currency'],
            ['key' => 'timezone', 'value' => 'America/New_York', 'group' => 'general', 'type' => 'text', 'description' => 'Application timezone'],
            ['key' => 'date_format', 'value' => 'MM/DD/YYYY', 'group' => 'general', 'type' => 'text', 'description' => 'Date display format'],
            
            // Company Information
            ['key' => 'company_name', 'value' => 'CargoMax Logistics', 'group' => 'company', 'type' => 'text', 'description' => 'Company name'],
            ['key' => 'company_tax_id', 'value' => 'TAX123456789', 'group' => 'company', 'type' => 'text', 'description' => 'Company tax ID'],
            ['key' => 'company_email', 'value' => 'admin@cargomax.com', 'group' => 'company', 'type' => 'text', 'description' => 'Company email'],
            ['key' => 'company_phone', 'value' => '+1 (555) 123-4567', 'group' => 'company', 'type' => 'text', 'description' => 'Company phone'],
            ['key' => 'company_website', 'value' => 'www.cargomax.com', 'group' => 'company', 'type' => 'text', 'description' => 'Company website'],
            ['key' => 'company_address', 'value' => '123 Logistics Ave, Transport City, TC 12345', 'group' => 'company', 'type' => 'text', 'description' => 'Company address'],
            
            // Appearance Settings
            ['key' => 'theme', 'value' => 'system', 'group' => 'appearance', 'type' => 'text', 'description' => 'Application theme (light, dark, system)'],
            
            // Localization Settings
            ['key' => 'language', 'value' => 'en', 'group' => 'localization', 'type' => 'text', 'description' => 'Default language'],
            
            // Units Settings
            ['key' => 'distance_unit', 'value' => 'miles', 'group' => 'units', 'type' => 'text', 'description' => 'Distance measurement unit'],
            ['key' => 'weight_unit', 'value' => 'lbs', 'group' => 'units', 'type' => 'text', 'description' => 'Weight measurement unit'],
            
            // Email Settings
            ['key' => 'email_from_address', 'value' => 'noreply@courier.com', 'group' => 'email', 'type' => 'text', 'description' => 'From email address'],
            ['key' => 'email_from_name', 'value' => 'CourierMax', 'group' => 'email', 'type' => 'text', 'description' => 'From email name'],
            ['key' => 'email_notifications_enabled', 'value' => 'true', 'group' => 'email', 'type' => 'boolean', 'description' => 'Enable email notifications'],
            
            // SMS Settings
            ['key' => 'sms_enabled', 'value' => 'true', 'group' => 'sms', 'type' => 'boolean', 'description' => 'Enable SMS notifications'],
            ['key' => 'sms_provider', 'value' => 'termii', 'group' => 'sms', 'type' => 'text', 'description' => 'SMS provider'],
            
            // Integration Settings
            ['key' => 'google_maps_enabled', 'value' => 'true', 'group' => 'integrations', 'type' => 'boolean', 'description' => 'Google Maps integration enabled'],
            ['key' => 'sendgrid_enabled', 'value' => 'true', 'group' => 'integrations', 'type' => 'boolean', 'description' => 'SendGrid integration enabled'],
            ['key' => 'twilio_enabled', 'value' => 'false', 'group' => 'integrations', 'type' => 'boolean', 'description' => 'Twilio integration enabled'],
            
            // Shipment Settings
            ['key' => 'auto_assign_driver', 'value' => 'false', 'group' => 'shipment', 'type' => 'boolean', 'description' => 'Auto assign drivers to shipments'],
            ['key' => 'tracking_url', 'value' => 'https://courier.com/track/', 'group' => 'shipment', 'type' => 'text', 'description' => 'Tracking page URL'],
            ['key' => 'max_cod_amount', 'value' => '500000', 'group' => 'shipment', 'type' => 'number', 'description' => 'Maximum COD amount allowed'],
            
            // Payment Settings
            ['key' => 'payment_gateway', 'value' => 'paystack', 'group' => 'payment', 'type' => 'text', 'description' => 'Payment gateway provider'],
            ['key' => 'tax_percentage', 'value' => '7.5', 'group' => 'payment', 'type' => 'number', 'description' => 'Default tax percentage'],
            
            // Driver Settings
            ['key' => 'driver_commission_percentage', 'value' => '10', 'group' => 'driver', 'type' => 'number', 'description' => 'Driver commission on deliveries'],
            ['key' => 'max_daily_deliveries', 'value' => '30', 'group' => 'driver', 'type' => 'number', 'description' => 'Maximum deliveries per driver per day'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}