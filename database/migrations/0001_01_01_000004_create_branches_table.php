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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch_code', 50)->unique();
            $table->string('branch_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code', 20);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('branch_type', ['main', 'regional', 'local'])->default('local');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->json('working_days')->nullable(); // ["monday", "tuesday", ...]
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
