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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_number', 50)->unique();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->enum('maintenance_type', ['scheduled', 'breakdown', 'inspection', 'repair', 'service'])->default('scheduled');
            $table->date('maintenance_date');
            $table->date('next_maintenance_date')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('vendor_name')->nullable();
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('category')->nullable();
            $table->string('technician_name')->nullable();
            $table->text('parts_replaced')->nullable();
            $table->integer('mileage_at_maintenance')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('invoice_document')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
             
            $table->index(['vehicle_id', 'maintenance_date']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
