<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yard_slot_history', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('yard_slot_id');
            $table->foreign('yard_slot_id')->references('id')->on('yard_slots')->cascadeOnDelete();

            $table->unsignedBigInteger('yard_visit_id')->nullable();
            $table->foreign('yard_visit_id')->references('id')->on('yard_visits')->nullOnDelete();

            $table->enum('action', ['assigned', 'released', 'reserved', 'blocked', 'resequenced']);
            $table->string('previous_status')->nullable();
            $table->string('new_status');

            $table->unsignedBigInteger('performed_by')->nullable();
            $table->foreign('performed_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['yard_slot_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yard_slot_history');
    }
};
