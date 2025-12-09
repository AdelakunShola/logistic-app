<?php



namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'template_key' => 'shipment_created',
                'template_name' => 'Shipment Created',
                'subject' => 'Your Shipment #{tracking_number} has been created',
                'body' => 'Dear {customer_name},\n\nYour shipment has been created successfully.\n\nTracking Number: {tracking_number}\nExpected Delivery: {expected_delivery_date}\n\nThank you for choosing our service.',
                'variables' => json_encode(['tracking_number', 'customer_name', 'expected_delivery_date']),
                'category' => 'shipment',
                'is_active' => true,
            ],
            [
                'template_key' => 'shipment_picked_up',
                'template_name' => 'Shipment Picked Up',
                'subject' => 'Your Shipment #{tracking_number} has been picked up',
                'body' => 'Dear {customer_name},\n\nYour shipment has been picked up and is on the way.\n\nTracking Number: {tracking_number}\n\nTrack your shipment at: {tracking_url}',
                'variables' => json_encode(['tracking_number', 'customer_name', 'tracking_url']),
                'category' => 'shipment',
                'is_active' => true,
            ],
            [
                'template_key' => 'shipment_out_for_delivery',
                'template_name' => 'Out for Delivery',
                'subject' => 'Your Shipment #{tracking_number} is out for delivery',
                'body' => 'Dear {customer_name},\n\nYour shipment is out for delivery today.\n\nDriver: {driver_name}\nDriver Phone: {driver_phone}\n\nThank you!',
                'variables' => json_encode(['tracking_number', 'customer_name', 'driver_name', 'driver_phone']),
                'category' => 'shipment',
                'is_active' => true,
            ],
            [
                'template_key' => 'shipment_delivered',
                'template_name' => 'Shipment Delivered',
                'subject' => 'Your Shipment #{tracking_number} has been delivered',
                'body' => 'Dear {customer_name},\n\nYour shipment has been delivered successfully.\n\nDelivered at: {delivery_time}\n\nThank you for choosing our service!',
                'variables' => json_encode(['tracking_number', 'customer_name', 'delivery_time']),
                'category' => 'shipment',
                'is_active' => true,
            ],
            [
                'template_key' => 'payment_received',
                'template_name' => 'Payment Received',
                'subject' => 'Payment Confirmation - {payment_number}',
                'body' => 'Dear {customer_name},\n\nWe have received your payment.\n\nAmount: {amount}\nPayment Method: {payment_method}\n\nThank you!',
                'variables' => json_encode(['customer_name', 'payment_number', 'amount', 'payment_method']),
                'category' => 'payment',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
    }
}


