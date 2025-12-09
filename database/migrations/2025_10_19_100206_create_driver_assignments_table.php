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
        Schema::create('driver_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->string('route_name')->nullable();
            $table->string('route_code')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->json('route_details')->nullable();
            $table->json('optimized_route')->nullable();
            $table->json('waypoints')->nullable();
            $table->decimal('estimated_distance', 10, 2)->nullable();
            $table->integer('estimated_duration')->nullable();
            $table->time('start_time')->nullable();
                    $table->time('end_time')->nullable();
                    $table->integer('total_deliveries')->default(0);
                    $table->integer('completed_deliveries')->default(0);
            $table->decimal('total_distance', 10, 2)->default(0);
            $table->integer('total_stops')->nullable();
            $table->timestamp('actual_start_time')->nullable();
            $table->timestamp('actual_end_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['driver_id', 'status']);
            $table->index(['vehicle_id', 'status']);
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_assignments');
    }
};
