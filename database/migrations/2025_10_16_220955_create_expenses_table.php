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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number', 50)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // who claimed
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('expense_type', ['fuel', 'maintenance', 'toll', 'parking', 'salary', 'rent', 'utilities', 'other'])->default('other');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('vendor_name')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('receipt_document')->nullable(); // path to receipt
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
            $table->index('expense_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
