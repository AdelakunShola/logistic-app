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
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('rule_type', ['base', 'weight', 'distance', 'priority', 'service'])->default('base');
            $table->decimal('base_rate', 10, 2)->default(0);
            $table->decimal('per_unit_rate', 10, 2)->default(0);
            $table->decimal('min_charge', 10, 2)->default(0);
            $table->decimal('max_charge', 10, 2)->nullable();
            $table->json('conditions')->nullable();
            $table->foreignId('from_zone_id')->constrained('pricing_zones')->onDelete('cascade');
            $table->foreignId('to_zone_id')->constrained('pricing_zones')->onDelete('cascade');
            $table->enum('service_type', ['standard', 'express', 'same_day', 'next_day', 'international'])->default('standard');
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('price_per_kg', 10, 2)->default(0);
            $table->decimal('min_weight', 10, 2)->default(0);
            $table->decimal('max_weight', 10, 2)->nullable();
            $table->decimal('fuel_surcharge_percentage', 5, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('insurance_percentage', 5, 2)->default(0);
            $table->integer('estimated_delivery_days')->default(1);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['from_zone_id', 'to_zone_id', 'service_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
