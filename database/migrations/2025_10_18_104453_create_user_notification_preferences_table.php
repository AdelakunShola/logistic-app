<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Alert Types
            $table->boolean('delivery_alerts')->default(true);
            $table->boolean('maintenance_alerts')->default(true);
            $table->boolean('low_inventory_alerts')->default(false);
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};