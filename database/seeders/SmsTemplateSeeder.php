<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsTemplate;

class SmsTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'template_key' => 'shipment_created_sms',
                'template_name' => 'Shipment Created SMS',
                'message' => 'Your shipment {tracking_number} has been created. Track at: {tracking_url}',
                'variables' => json_encode(['tracking_number', 'tracking_url']),
                'category' => 'shipment',
                'is_active' => true,
            ],
            [
                'template_key' => 'shipment_out_for_delivery_sms',
                'template_name' => 'Out for Delivery SMS',
                'message' => 'Your shipment {tracking_number} is out for delivery. Driver: {driver_name}, Phone: {driver_phone}',
                'variables' => json_encode(['tracking_number', 'driver_name', 'driver_phone']),
                'category' => 'shipment',
                'is_active' => true,
            ],
            [
                'template_key' => 'shipment_delivered_sms',
                'template_name' => 'Delivered SMS',
                'message' => 'Your shipment {tracking_number} has been delivered. Thank you!',
                'variables' => json_encode(['tracking_number']),
                'category' => 'shipment',
                'is_active' => true,
            ],
            [
                'template_key' => 'otp_verification',
                'template_name' => 'OTP Verification',
                'message' => 'Your OTP is: {otp}. Valid for 10 minutes.',
                'variables' => json_encode(['otp']),
                'category' => 'otp',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            SmsTemplate::create($template);
        }
    }
}
