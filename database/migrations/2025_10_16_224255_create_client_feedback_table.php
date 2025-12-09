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
        Schema::create('client_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('overall_rating')->default(5); // 1-5
            $table->integer('delivery_speed_rating')->nullable();
            $table->integer('driver_behavior_rating')->nullable();
            $table->integer('package_condition_rating')->nullable();
            $table->text('comments')->nullable();
            $table->enum('feedback_type', ['positive', 'negative', 'neutral', 'complaint'])->default('neutral');
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_response')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['customer_id', 'feedback_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_feedback');
    }
};
