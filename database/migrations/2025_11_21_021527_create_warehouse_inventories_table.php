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
        Schema::create('warehouse_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            
            $table->string('storage_location')->nullable(); // e.g., "Aisle 3, Shelf B2"
            $table->string('bin_number')->nullable();
            $table->enum('package_condition', ['good', 'damaged', 'requires_attention'])->default('good');
            
            $table->timestamp('checked_in_at');
            $table->timestamp('checked_out_at')->nullable();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('checked_out_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->boolean('is_priority')->default(false);
            $table->boolean('requires_special_handling')->default(false);
            $table->text('handling_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('warehouse_id');
            $table->index('shipment_id');
            $table->index(['warehouse_id', 'checked_out_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_inventories');
    }
};
