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
        Schema::create('hubs', function (Blueprint $table) {
            $table->id();
            $table->string('hub_code', 50)->unique();
            $table->string('hub_name');
            $table->enum('hub_type', ['warehouse', 'distribution_center', 'sorting_facility', 'pickup_point'])->default('warehouse');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code', 20);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('storage_capacity', 10, 2)->nullable(); // in cubic meters
            $table->decimal('current_occupancy', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hubs');
    }
};
