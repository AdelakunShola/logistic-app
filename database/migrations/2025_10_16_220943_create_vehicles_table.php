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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number', 50)->unique();
            $table->string('vehicle_name')->nullable();
            $table->enum('vehicle_type', ['truck', 'van', 'bike', 'car', 'bicycle'])->default('van');
            $table->string('make')->nullable(); // Toyota, Ford, etc.
            $table->string('model')->nullable();
            $table->year('year')->nullable();
            $table->string('color')->nullable();
            $table->decimal('capacity_weight', 10, 2)->nullable(); // in kg
            $table->string('vin', 17)->unique()->nullable(); // VIN is exactly 17 chars
            $table->string('license_plate')->unique()->nullable();
            $table->decimal('current_fuel_level', 5, 2)->nullable(); // Current fuel %
            $table->integer('alert_count')->default(0); // Active alerts
            $table->decimal('utilization_percentage', 5, 2)->default(0);
            $table->string('current_location')->nullable(); // Text location
            $table->decimal('current_load', 10, 2)->default(0);
            $table->decimal('capacity_volume', 10, 2)->nullable(); // in cubic meters
            $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('hub_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'maintenance', 'repair'])->default('active');
            $table->date('registration_date')->nullable();
            $table->date('registration_expiry')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('insurance_company')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable();
            $table->decimal('mileage', 10, 2)->default(0); // in km
            $table->decimal('fuel_capacity', 10, 2)->nullable();
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('last_location_update')->nullable();
            $table->decimal('speed', 5, 2)->nullable(); // km/h
            $table->decimal('heading', 5, 2)->nullable(); // degrees
            
            // Fuel tracking
            $table->decimal('total_fuel_consumed', 10, 2)->default(0);
            $table->decimal('fuel_efficiency_mpg', 5, 2)->nullable();
            $table->date('last_fuel_date')->nullable();
            
            // Performance metrics
            $table->integer('total_trips')->default(0);
            $table->decimal('total_distance', 10, 2)->default(0); // km
            $table->decimal('avg_speed', 5, 2)->nullable();
            
            // Alerts
            $table->enum('alert_type', ['none', 'fuel_low', 'maintenance_due', 'insurance_expiry', 'registration_expiry', 'breakdown'])->default('none');
            $table->text('alert_message')->nullable();
            $table->timestamp('alert_created_at')->nullable();
            $table->string('fuel_type', 50)->nullable(); // petrol, diesel, electric
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
