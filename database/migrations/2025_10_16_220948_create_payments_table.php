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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number', 50)->unique();
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'wallet', 'upi', 'cheque', 'online'])->default('cash');
            $table->enum('payment_type', ['shipment', 'cod_collection', 'credit_payment', 'advance'])->default('shipment');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->dateTime('payment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['customer_id', 'status']);
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
