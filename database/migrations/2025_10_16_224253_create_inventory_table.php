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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 50)->unique();
            $table->string('item_name');
            $table->string('category')->nullable(); // packaging, supplies, spare_parts
            $table->text('description')->nullable();
            $table->string('unit_of_measurement', 20)->default('pcs'); // pcs, kg, box, roll
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->integer('quantity_in_stock')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->integer('max_stock_level')->nullable();
            $table->foreignId('hub_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->string('location_in_warehouse')->nullable(); // shelf/rack number
            $table->enum('status', ['available', 'low_stock', 'out_of_stock', 'discontinued'])->default('available');
            $table->string('supplier_name')->nullable();
            $table->string('supplier_contact')->nullable();
            $table->date('last_restock_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['hub_id', 'warehouse_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
