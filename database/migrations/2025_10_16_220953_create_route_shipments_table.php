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
        Schema::create('route_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->integer('sequence_number')->default(0);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['route_id', 'sequence_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_shipments');
    }
};
