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
        Schema::create('pricing_zones', function (Blueprint $table) {
            $table->id();
            $table->string('zone_name');
            $table->string('zone_code', 50)->unique();
            $table->json('cities')->nullable(); // array of cities
            $table->json('postal_codes')->nullable(); // array of postal codes
            $table->string('state')->nullable();
            $table->string('country');
            $table->enum('zone_type', ['local', 'regional', 'national', 'international'])->default('local');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_zones');
    }
};
