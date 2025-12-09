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
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'alert', 'maintenance'])->default('info');
            $table->enum('target_audience', ['all', 'admin', 'manager', 'dispatcher', 'driver', 'customer'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
    }
};
