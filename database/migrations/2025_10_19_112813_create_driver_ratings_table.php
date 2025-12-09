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
        Schema::create('driver_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating')->unsigned(); // 1-5
            $table->text('review')->nullable();
            $table->enum('delivery_speed', ['very_slow', 'slow', 'average', 'fast', 'very_fast'])->nullable();
            $table->enum('professionalism', ['poor', 'fair', 'good', 'very_good', 'excellent'])->nullable();
            $table->enum('package_handling', ['poor', 'fair', 'good', 'very_good', 'excellent'])->nullable();
            $table->boolean('would_recommend')->default(true);
            $table->timestamps();
            
            $table->index('driver_id');
            $table->index(['driver_id', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_ratings');
    }
};
