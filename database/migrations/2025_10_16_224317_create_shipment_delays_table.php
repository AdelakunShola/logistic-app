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
        Schema::create('shipment_delays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('delay_reason', [
                'traffic_congestion',
                'weather_conditions',
                'vehicle_issues',
                'address_issues',
                'customer_unavailable',
                'customs_delay',
                'port_congestion',
                'documentation_issues',
                'mechanical_failure',
                'road_closure',
                'other'
            ])->default('other');
            $table->text('delay_description')->nullable();
            $table->integer('delay_duration_minutes')->default(0);
            $table->timestamp('delayed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->dateTime('original_delivery_date')->nullable();
            $table->dateTime('new_delivery_date')->nullable();
            $table->integer('delay_hours')->nullable();
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->boolean('customer_notified')->default(false);
            $table->timestamp('customer_notified_at')->nullable();
            $table->boolean('escalated')->default(false);
            $table->timestamp('escalated_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            
            $table->index(['shipment_id', 'delay_reason']);
            $table->index('driver_id');
            $table->index(['resolved_at', 'escalated']);
            $table->index('delay_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_delays');
    }
};