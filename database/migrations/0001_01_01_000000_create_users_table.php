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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
           // Basic Information
    $table->string('user_name')->nullable();
    $table->string('first_name')->nullable();
    $table->string('last_name')->nullable();
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    
    // Role & Status
    $table->enum('role', ['admin', 'driver', 'customer', 'manager', 'dispatcher'])->default('customer');
    $table->enum('status', ['active', 'inactive', 'suspended', 'on_leave'])->default('active');
    $table->boolean('is_verified')->default(false);
    
    // Contact Information
    $table->string('phone')->nullable();
    $table->string('alternate_phone', 20)->nullable();
    $table->text('address')->nullable();
    $table->string('city', 100)->nullable();
    $table->string('state', 100)->nullable();
    $table->string('country', 100)->nullable();
    $table->string('postal_code', 20)->nullable();


    
    // Personal Details
    $table->date('date_of_birth')->nullable();
    $table->enum('gender', ['male', 'female', 'other'])->nullable();
    $table->string('profile_photo')->nullable();
    
    // Driver-Specific Fields
    $table->string('license_number')->nullable();
    $table->string('driver_license')->nullable(); // document path
    $table->string('license_expiry')->nullable();
    $table->string('specializations')->nullable();
    $table->string('experience_years')->nullable();
    $table->string('medical_certificate')->nullable(); // document path
    $table->string('vehicle_type', 50)->nullable();
    $table->string('vehicle_number', 50)->nullable();
    $table->decimal('vehicle_capacity', 10, 2)->nullable();
    $table->string('emergency_contact_name', 100)->nullable();
    $table->string('emergency_contact_phone', 20)->nullable();
    
    // Employee/Work Information
    $table->string('employee_id', 50)->unique()->nullable();
    $table->string('department', 100)->nullable();
    $table->string('designation', 100)->nullable();
    $table->date('joining_date')->nullable();
    $table->decimal('salary', 10, 2)->nullable();
    $table->decimal('commission_rate', 5, 2)->nullable();
    $table->unsignedBigInteger('manager_id')->nullable();
    
    // Financial Information
    $table->string('bank_name', 100)->nullable();
    $table->string('account_number', 50)->nullable();
    $table->string('account_holder_name', 100)->nullable();
    $table->string('ifsc_code', 20)->nullable();
    $table->decimal('wallet_balance', 10, 2)->default(0);
    
    // Identity Documents
    $table->string('id_proof_type', 50)->nullable();
    $table->string('id_proof_number', 50)->nullable();
    $table->string('id_proof_document')->nullable();
    $table->string('address_proof_document')->nullable();
    
    // Driver Availability & Location
    $table->boolean('is_available')->default(true);
    $table->decimal('current_latitude', 10, 8)->nullable();
    $table->decimal('current_longitude', 11, 8)->nullable();
    $table->timestamp('last_location_update')->nullable();
    $table->boolean('is_tracking_active')->default(false);
    $table->decimal('current_speed', 8, 2)->nullable(); 
    $table->string('current_heading', 50)->nullable();
    $table->decimal('location_accuracy', 12, 2)->nullable()->comment('GPS accuracy in meters');
    
    // Operational Fields
    $table->unsignedBigInteger('assigned_branch_id')->nullable();
    $table->unsignedBigInteger('assigned_hub_id')->nullable();
    $table->unsignedBigInteger('assigned_warehouse_id')->nullable();
    $table->integer('max_daily_deliveries')->nullable();
    $table->json('preferred_delivery_areas')->nullable();
    
    // Performance Metrics
    $table->decimal('rating', 3, 2)->default(0);
    $table->integer('total_deliveries')->default(0);
    $table->integer('on_time_deliveries')->default(0);
    $table->integer('successful_deliveries')->default(0);
    $table->integer('failed_deliveries')->default(0);
    $table->integer('pending_deliveries')->default(0);
    $table->decimal('on_time_rate', 5, 2)->default(0);
    $table->string('weekly_hours')->nullable();
    $table->decimal('monthly_earnings', 10, 2)->default(0);
    
    // Notification Preferences
    $table->boolean('email_notifications')->default(true);
    $table->boolean('sms_notifications')->default(true);
    $table->boolean('push_notifications')->default(true);
    
    // Security & Authentication
    $table->timestamp('last_login_at')->nullable();
    $table->string('last_login_ip', 45)->nullable();
    $table->boolean('two_factor_enabled')->default(false);
    $table->text('two_factor_secret')->nullable();
    
    // System Fields
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();
    

    $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
   // $table->foreign('assigned_branch_id')->references('id')->on('branches')->onDelete('set null');
   // $table->foreign('assigned_hub_id')->references('id')->on('hubs')->onDelete('set null');       
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
