<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Carrier;

class DebugShipments extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'shipments:debug';

    /**
     * The console command description.
     */
    protected $description = 'Debug shipments table and related data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== SHIPMENT DEBUG INFORMATION ===');
        $this->newLine();

        // Check if shipments table exists
        if (!Schema::hasTable('shipments')) {
            $this->error('❌ Shipments table does not exist!');
            $this->info('Run: php artisan migrate');
            return 1;
        }
        $this->info('✅ Shipments table exists');

        // Get table structure
        $this->info('📋 Table Structure:');
        $columns = DB::select('DESCRIBE shipments');
        $table = [];
        foreach ($columns as $column) {
            $table[] = [
                'Field' => $column->Field,
                'Type' => $column->Type,
                'Null' => $column->Null,
                'Key' => $column->Key,
                'Default' => $column->Default ?? 'NULL',
            ];
        }
        $this->table(['Field', 'Type', 'Null', 'Key', 'Default'], $table);
        $this->newLine();

        // Check required fields
        $this->info('🔍 Checking Required Fields:');
        $requiredFields = [
            'pickup_contact_phone',
            'pickup_contact_name',
            'delivery_contact_phone',
            'delivery_contact_name',
        ];

        foreach ($requiredFields as $field) {
            $column = collect($columns)->firstWhere('Field', $field);
            if ($column) {
                $nullable = $column->Null === 'YES' ? '✅ Nullable' : '❌ Not Nullable';
                $this->line(" - {$field}: {$nullable}");
            } else {
                $this->error(" - {$field}: ❌ Missing from table");
            }
        }
        $this->newLine();

        // Count records
        $count = Shipment::count();
        $this->info("📦 Total Shipments: {$count}");

        if ($count > 0) {
            $latest = Shipment::latest()->first();
            $this->newLine();
            $this->info('🆕 Latest Shipment:');
            $this->table(
                ['ID', 'Status', 'Pickup', 'Delivery', 'Carrier', 'Created At'],
                [[
                    $latest->id,
                    $latest->status,
                    $latest->pickup_location ?? 'N/A',
                    $latest->delivery_location ?? 'N/A',
                    $latest->carrier->name ?? 'N/A',
                    $latest->created_at,
                ]]
            );
        }

        $this->newLine();
        $this->info('✅ Debug complete!');
        return 0;
    }
}
