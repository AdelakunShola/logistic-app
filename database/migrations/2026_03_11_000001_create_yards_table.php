<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yards', function (Blueprint $table) {
            $table->id();
            $table->string('yard_code', 50)->unique();
            $table->string('name');

            // Linked entities
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
            $table->unsignedBigInteger('hub_id')->nullable();
            $table->foreign('hub_id')->references('id')->on('hubs')->nullOnDelete();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();

            // Address
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code', 20)->nullable();
            $table->string('country')->default('USA');

            // Geolocation
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Capacity
            $table->integer('total_capacity')->default(0);

            // Yard layout (Konva.js JSON)
            $table->json('yard_layout')->nullable();

            // Operating hours
            $table->time('operating_hours_start')->nullable();
            $table->time('operating_hours_end')->nullable();

            // Yard settings
            $table->integer('max_stay_hours')->default(24);
            $table->integer('overstay_alert_minutes')->default(30);
            $table->boolean('auto_assign_enabled')->default(true);
            $table->boolean('allow_self_registration')->default(true);
            $table->boolean('require_appointment')->default(false);

            // Manager
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('manager_id')->references('id')->on('users')->nullOnDelete();

            // Contact
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Status
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('yard_code');
            $table->index('status');
            $table->index(['city', 'state']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yards');
    }
};
