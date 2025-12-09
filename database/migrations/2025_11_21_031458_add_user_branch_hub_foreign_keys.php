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
    Schema::table('users', function (Blueprint $table) {
        $table->foreign('assigned_branch_id')->references('id')->on('branches')->onDelete('set null');
        $table->foreign('assigned_hub_id')->references('id')->on('hubs')->onDelete('set null');
        $table->foreign('assigned_warehouse_id')->references('id')->on('warehouses')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['assigned_branch_id']);
        $table->dropForeign(['assigned_hub_id']);
        $table->dropForeign(['assigned_warehouse_id']);
    });
}

};
