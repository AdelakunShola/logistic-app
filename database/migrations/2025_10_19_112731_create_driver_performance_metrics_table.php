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
        Schema::create('driver_performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->integer('deliveries_completed')->default(0);
            $table->integer('deliveries_failed')->default(0);
            $table->integer('deliveries_cancelled')->default(0);
            $table->decimal('on_time_percentage', 5, 2)->default(0);
            $table->decimal('distance_travelled', 10, 2)->default(0); // in km
            $table->decimal('fuel_consumed', 10, 2)->default(0); // in liters
            $table->decimal('hours_worked', 5, 2)->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('customer_complaints')->default(0);
            $table->integer('customer_compliments')->default(0);
            $table->decimal('earnings', 10, 2)->default(0);
            $table->json('additional_metrics')->nullable();
            $table->timestamps();
            
            $table->unique(['driver_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_performance_metrics');
    }
};
