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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_name');
            $table->string('route_code', 50)->unique();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->date('route_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->decimal('total_distance', 10, 2)->nullable(); // in km
            $table->decimal('estimated_duration', 10, 2)->nullable(); // in hours
            $table->integer('total_shipments')->default(0);
            $table->integer('completed_shipments')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['route_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
