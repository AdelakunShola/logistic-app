<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yard_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yard_zone_id');
            $table->foreign('yard_zone_id')->references('id')->on('yard_zones')->cascadeOnDelete();

            $table->string('slot_number', 20);
            $table->enum('type', ['truck_parking', 'dock', 'staging', 'waiting'])->default('truck_parking');
            $table->enum('size', ['small', 'medium', 'large', 'oversized'])->default('medium');
            $table->enum('status', ['available', 'occupied', 'reserved', 'maintenance', 'blocked'])->default('available');

            // Konva.js position data
            $table->json('position_data')->nullable();

            // Current occupant
            $table->unsignedBigInteger('current_vehicle_id')->nullable();
            $table->foreign('current_vehicle_id')->references('id')->on('vehicles')->nullOnDelete();
            $table->unsignedBigInteger('current_driver_id')->nullable();
            $table->foreign('current_driver_id')->references('id')->on('users')->nullOnDelete();

            // Features
            $table->json('features')->nullable();

            $table->timestamps();

            $table->index(['yard_zone_id', 'status']);
            $table->index('slot_number');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yard_slots');
    }
};
