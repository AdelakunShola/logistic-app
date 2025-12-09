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
        Schema::create('warehouse_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->foreignId('from_warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('to_warehouse_id')->constrained('warehouses')->onDelete('cascade');
            
            $table->enum('transfer_type', ['inter_warehouse', 'delivery_dispatch', 'return'])->default('inter_warehouse');
            $table->enum('status', ['pending', 'in_transit', 'completed', 'cancelled'])->default('pending');
            
            $table->foreignId('initiated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->string('vehicle_number')->nullable();
            $table->text('transfer_notes')->nullable();
            $table->text('reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('shipment_id');
            $table->index(['from_warehouse_id', 'to_warehouse_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_transfers');
    }
};
