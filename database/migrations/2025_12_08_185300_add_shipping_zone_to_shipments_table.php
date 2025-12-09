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
        Schema::table('shipments', function (Blueprint $table) {
            $table->enum('shipping_zone', ['local', 'regional', 'national', 'international'])
                  ->after('delivery_priority')
                  ->comment('Shipping zone: local (same city), regional (same state), national (different states), international (different countries)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('shipping_zone');
        });
    }
};