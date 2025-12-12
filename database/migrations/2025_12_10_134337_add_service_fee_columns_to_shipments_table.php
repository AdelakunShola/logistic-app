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
        $table->decimal('signature_fee', 10, 2)->default(0)->after('additional_services_fee');
        $table->decimal('temperature_fee', 10, 2)->default(0)->after('signature_fee');
        $table->decimal('fragile_fee', 10, 2)->default(0)->after('temperature_fee');
        $table->decimal('subtotal_amount', 10, 2)->default(0)->after('fragile_fee');
    });
}

public function down()
{
    Schema::table('shipments', function (Blueprint $table) {
        $table->dropColumn(['signature_fee', 'temperature_fee', 'fragile_fee', 'subtotal_amount']);
    });
}
};
