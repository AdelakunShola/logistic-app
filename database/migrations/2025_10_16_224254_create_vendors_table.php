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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_code', 50)->unique();
            $table->string('vendor_name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->enum('vendor_type', ['vehicle_parts', 'packaging', 'fuel', 'insurance', 'maintenance', 'other'])->default('other');
            $table->enum('status', ['active', 'inactive', 'blacklisted'])->default('active');
            $table->decimal('credit_limit', 10, 2)->default(0);
            $table->enum('payment_terms', ['cod', 'net_30', 'net_60', 'advance'])->default('cod');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
