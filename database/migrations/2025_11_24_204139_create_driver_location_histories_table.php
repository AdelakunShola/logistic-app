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
        Schema::create('driver_location_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('speed', 5, 2)->nullable();
            $table->string('heading')->nullable();
            $table->decimal('accuracy', 12, 2)->nullable(); // meters
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['driver_id', 'recorded_at']);
            $table->index('shipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_location_histories');
    }
};
