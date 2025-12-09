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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 50)->unique();
            $table->enum('customer_type', ['individual', 'business'])->default('individual');
            $table->string('name'); // full name or business name
            $table->string('contact_person')->nullable(); // for business
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('company_name')->nullable(); // for business
            $table->string('tax_id')->nullable(); // VAT/GST number
            $table->enum('status', ['active', 'inactive', 'blacklisted'])->default('active');
            $table->decimal('credit_limit', 10, 2)->default(0);
            $table->decimal('outstanding_balance', 10, 2)->default(0);
            $table->enum('payment_terms', ['prepaid', 'postpaid', 'credit'])->default('prepaid');
            $table->integer('credit_days')->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
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
        Schema::dropIfExists('customers');
    }
};
