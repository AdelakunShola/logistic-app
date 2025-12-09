<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShipmentIssue;
use App\Models\Shipment;
use App\Models\User;

class ShipmentIssueSeeder extends Seeder
{
    public function run()
    {
        $shipments = Shipment::limit(10)->get();
        $users = User::limit(5)->get();
        
        $issueTypes = ['damaged', 'delayed', 'lost', 'wrong_address', 'missing_items', 'incorrect_tracking'];
        $priorities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['pending', 'investigating', 'resolved'];
        
        foreach ($shipments as $shipment) {
            ShipmentIssue::create([
                'shipment_id' => $shipment->id,
                'reported_by' => $users->random()->id,
                'assigned_to' => rand(0, 1) ? $users->random()->id : null,
                'issue_type' => $issueTypes[array_rand($issueTypes)],
                'description' => 'Test issue description for shipment ' . $shipment->tracking_number,
                'status' => $statuses[array_rand($statuses)],
                'priority' => $priorities[array_rand($priorities)],
                'resolution' => rand(0, 1) ? 'Issue has been resolved successfully.' : null,
                'resolved_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                'reporter_ip' => '127.0.0.1',
                'reporter_user_agent' => 'Mozilla/5.0',
                'metadata' => [
                    'tracking_number' => $shipment->tracking_number,
                    'shipment_status' => $shipment->status,
                ],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}