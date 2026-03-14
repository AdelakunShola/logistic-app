<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yard_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yard_id');
            $table->foreign('yard_id')->references('id')->on('yards')->cascadeOnDelete();

            $table->unsignedBigInteger('yard_slot_id')->nullable();
            $table->foreign('yard_slot_id')->references('id')->on('yard_slots')->nullOnDelete();

            // Driver/vehicle - can be registered or unregistered
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('users')->nullOnDelete();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->nullOnDelete();

            $table->string('driver_name');
            $table->string('vehicle_plate');
            $table->string('vehicle_type')->nullable();

            // Associated shipment
            $table->unsignedBigInteger('shipment_id')->nullable();
            $table->foreign('shipment_id')->references('id')->on('shipments')->nullOnDelete();

            $table->enum('purpose', ['pickup', 'delivery', 'staging', 'parking', 'maintenance'])->default('parking');

            // Timing
            $table->dateTime('check_in_time');
            $table->dateTime('check_out_time')->nullable();
            $table->integer('expected_duration_minutes')->default(60);
            $table->integer('actual_duration_minutes')->nullable();

            $table->enum('status', ['checked_in', 'loading', 'unloading', 'waiting', 'checked_out', 'overstay'])->default('checked_in');
            $table->text('notes')->nullable();
            $table->enum('registered_by', ['self', 'admin', 'kiosk', 'system'])->default('admin');

            $table->timestamps();

            $table->index(['yard_id', 'status']);
            $table->index('vehicle_plate');
            $table->index('check_in_time');
            $table->index(['driver_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yard_visits');
    }
};
