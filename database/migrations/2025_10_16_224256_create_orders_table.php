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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            
            // Order Details
            $table->enum('order_type', ['pickup', 'delivery', 'return', 'exchange'])->default('delivery');
            $table->date('order_date');
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time_from')->nullable();
            $table->time('scheduled_time_to')->nullable();
            
            // Status & Priority
            $table->enum('status', ['processing', 'in_transit', 'delivered', 'delayed', 'cancelled', 'pending', 'confirmed', 'assigned', 'in_progress', 'completed'])->default('pending');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            
            // Addresses
            $table->text('pickup_address')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('street_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            
            // Order Items & Financials
            $table->json('items')->nullable(); // array of items in the order
            $table->decimal('order_value', 10, 2)->default(0);
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            
            // Payment Information
            $table->enum('payment_status', ['paid', 'pending', 'refunded', 'failed'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_terms')->nullable();
            
            // Shipping & Tracking
            $table->string('tracking_number')->nullable();
            $table->string('shipping_method')->nullable();
            $table->integer('delivery_progress')->default(0); // 0-100 percentage
            
            // Customer Information
            $table->string('customer_phone')->nullable();
            $table->string('customer_company')->nullable();
            $table->string('customer_email')->nullable();
            
            // Additional Information
            $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('special_instructions')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['order_date', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index('priority');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
