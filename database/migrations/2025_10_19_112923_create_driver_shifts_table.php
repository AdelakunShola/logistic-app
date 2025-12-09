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
        Schema::create('driver_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->date('shift_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamp('actual_start_time')->nullable();
            $table->timestamp('actual_end_time')->nullable();
            $table->enum('shift_type', ['morning', 'afternoon', 'evening', 'night', 'full_day'])->default('full_day');
            $table->enum('status', ['scheduled', 'active', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->decimal('break_duration', 5, 2)->default(0); // in hours
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['driver_id', 'shift_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_shifts');
    }
};
