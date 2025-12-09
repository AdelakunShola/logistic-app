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
        Schema::create('customer_feedbacks', function (Blueprint $table) {
            $table->id();
                $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
                $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
                $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('feedback_type', ['complaint', 'compliment', 'suggestion', 'issue']);
                $table->integer('rating')->nullable(); // 1-5
                $table->text('comment')->nullable();
                $table->enum('status', ['pending', 'reviewed', 'resolved', 'closed'])->default('pending');
                $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('resolved_at')->nullable();
                $table->text('resolution_notes')->nullable();
                $table->timestamps();
                
                $table->index(['driver_id', 'feedback_type']);
                $table->index(['customer_id', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_feedbacks');
    }
};
