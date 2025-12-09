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
        Schema::create('shipment_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('issue_type');
            $table->text('description');
            $table->enum('status', ['pending', 'investigating', 'resolved', 'closed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->string('reporter_ip')->nullable();
            $table->text('reporter_user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->integer('response_time_minutes')->nullable();
            $table->integer('resolution_time_minutes')->nullable();
            $table->timestamps();
            
            $table->index(['shipment_id', 'status']);
            $table->index('created_at');
        $table->index('priority');
        $table->index(['assigned_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_issues');
    }
};
