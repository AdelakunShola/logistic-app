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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('warehouse_code')->unique(); // e.g., WH-LAG-001
            $table->string('name'); // e.g., Lagos Main Warehouse
            $table->enum('type', ['main', 'regional', 'distribution', 'sortation'])->default('main');
            
            // Address Information
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('Nigeria');
            $table->string('postal_code')->nullable();
            
            // Contact Information
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('manager_phone')->nullable();
            
            // Geolocation (for maps & routing)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Capacity & Operations
            $table->integer('storage_capacity')->default(0); // Max packages
            $table->integer('current_occupancy')->default(0); // Current packages
            $table->decimal('area_sqm', 10, 2)->nullable(); // Area in square meters
            $table->integer('loading_docks')->default(0);
            $table->integer('staff_count')->default(0);
            
            // Operating Hours
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->json('operating_days')->nullable(); // ["Monday", "Tuesday", etc.]
            
            // Status & Features
            $table->enum('status', ['active', 'inactive', 'maintenance', 'closed'])->default('active');
            $table->boolean('is_pickup_point')->default(true);
            $table->boolean('is_delivery_point')->default(true);
            $table->boolean('accepts_cod')->default(true);
            $table->boolean('has_cold_storage')->default(false);
            $table->boolean('has_24h_security')->default(false);
            
            // Financial
            $table->decimal('monthly_rent', 12, 2)->nullable();
            $table->decimal('utility_cost', 10, 2)->nullable();
            
            // Performance Metrics
            $table->integer('total_shipments_processed')->default(0);
            $table->integer('total_deliveries_completed')->default(0);
            $table->decimal('average_processing_time', 8, 2)->default(0); // in hours
            $table->decimal('utilization_percentage', 5, 2)->default(0);
            
            // Compliance & Safety
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->date('last_inspection_date')->nullable();
            $table->enum('safety_rating', ['A', 'B', 'C', 'D', 'F'])->nullable();
            
            // Notes
            $table->text('description')->nullable();
            $table->text('special_instructions')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('warehouse_code');
            $table->index('status');
            $table->index(['city', 'state']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
