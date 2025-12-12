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
        Schema::create('shipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->string('description')->nullable();
            $table->string('category')->default('general_merchandise');
            $table->integer('quantity')->default(1);
            $table->decimal('weight', 10, 2)->default(0); // in lbs
            $table->decimal('length', 10, 2)->nullable(); // in inches
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('value', 10, 2)->default(0);
            $table->boolean('is_hazardous')->default(false);
            $table->timestamps();
            
            $table->index('shipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_items');
    }
};
