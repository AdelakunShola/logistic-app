<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_delays', function (Blueprint $table) {
            $table->id();
                $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
                $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('delay_reason', [
                    'weather_conditions',
                    'traffic_congestion',
                    'customer_unavailable',
                    'vehicle_issues',
                    'address_issues',
                    'documentation_issues',
                    'customs_delay',
                    'other'
                ]);
                $table->text('delay_description')->nullable();
                $table->integer('delay_duration_minutes')->default(0);
                $table->timestamp('delayed_at');
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
                
                $table->index(['shipment_id', 'delay_reason']);
                $table->index('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_delays');
    }
};
