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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'success', 'warning', 'error', 'shipment_update'])->default('info');
            $table->enum('channel', ['email', 'sms', 'push', 'system'])->default('system');
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->json('data')->nullable(); 
            $table->string('action_url')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_read']);
             $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
