<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yard_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yard_id');
            $table->foreign('yard_id')->references('id')->on('yards')->cascadeOnDelete();

            $table->string('name');
            $table->enum('type', ['parking', 'loading_dock', 'staging', 'waiting', 'maintenance'])->default('parking');
            $table->integer('capacity')->default(0);

            // Konva.js position data
            $table->json('position_data')->nullable();
            $table->string('color', 7)->default('#3B82F6');
            $table->integer('priority')->default(0);

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['yard_id', 'type']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yard_zones');
    }
};
