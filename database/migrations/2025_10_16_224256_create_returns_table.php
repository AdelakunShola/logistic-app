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
        Schema::create('returns', function (Blueprint $table) {
    $table->id();
    $table->string('return_number', 50)->unique(); // RET-001
    $table->string('order_number', 50)->nullable(); // ORD-2024-001
    $table->string('pickup_contact_name', 50)->nullable();
    $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('cascade');
    $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('cascade'); // Changed to nullable
    
    // Return Details
    $table->enum('return_reason', [
        'defective_product',
        'wrong_item_sent',
        'changed_mind',
        'damaged_in_transit',
        'not_as_described',
        'quality_issue',
        'size_issue',
        'other'
    ])->nullable(); // Changed to nullable
    $table->text('description')->nullable();
    $table->text('customer_notes')->nullable();
    $table->date('return_date')->nullable(); // Changed to nullable
    $table->date('request_date')->nullable(); // Changed to nullable
    
    // Status
    $table->enum('status', [
        'pending_review',
        'approved',
        'processing',
        'completed',
        'rejected',
        'cancelled'
    ])->default('pending_review');
    
    // Warehouse & Tracking
    $table->string('warehouse')->nullable();
    $table->string('tracking_number')->nullable();
    
    // Assignment
    $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->onDelete('set null');
    $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
    
    // Pickup Information
    $table->text('pickup_address')->nullable();
    $table->date('scheduled_pickup_date')->nullable();
    $table->date('actual_pickup_date')->nullable();
    
    // Financial
    $table->decimal('total_amount', 10, 2)->default(0)->nullable();
    $table->decimal('return_value', 10, 2)->default(0)->nullable(); // Added nullable
    $table->decimal('refund_amount', 10, 2)->default(0)->nullable(); // Added nullable
    $table->enum('refund_status', ['pending', 'processing', 'completed', 'rejected'])->default('pending')->nullable();
    $table->date('refund_date')->nullable();
    $table->string('refund_method')->nullable();
    
    // Items JSON (stores array of return items)
    $table->json('items')->nullable();
    
    // Notes & Images
    $table->text('admin_notes')->nullable();
    $table->text('rejection_reason')->nullable();
    $table->text('internal_notes')->nullable();
    $table->json('attached_images')->nullable();
    
    // Customer History
    $table->integer('customer_order_count')->default(0)->nullable(); // Added nullable
    $table->integer('customer_return_count')->default(0)->nullable(); // Added nullable
    $table->date('customer_since')->nullable();
    
    // Processing
    $table->timestamp('reviewed_at')->nullable();
    $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamp('approved_at')->nullable();
    $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamp('completed_at')->nullable();
    
    $table->timestamps();
    $table->softDeletes();
    
    // Indexes
    $table->index(['return_number']);
    $table->index(['order_number']);
    $table->index(['shipment_id', 'status']);
    $table->index(['customer_id', 'status']);
    $table->index(['status', 'request_date']);
    $table->index(['created_at']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
