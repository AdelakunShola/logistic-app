<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yard_appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yard_id');
            $table->foreign('yard_id')->references('id')->on('yards')->cascadeOnDelete();

            $table->unsignedBigInteger('yard_slot_id')->nullable();
            $table->foreign('yard_slot_id')->references('id')->on('yard_slots')->nullOnDelete();

            // Driver/vehicle
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

            $table->enum('purpose', ['pickup', 'delivery', 'staging', 'parking', 'maintenance'])->default('delivery');

            // Scheduling
            $table->dateTime('scheduled_arrival');
            $table->dateTime('scheduled_departure');
            $table->integer('estimated_duration_minutes')->default(60);

            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'completed', 'cancelled', 'no_show'])->default('pending');
            $table->string('confirmation_code', 20)->unique();

            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamps();

            $table->index(['yard_id', 'status']);
            $table->index('confirmation_code');
            $table->index('scheduled_arrival');
            $table->index(['driver_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yard_appointments');
    }
};
