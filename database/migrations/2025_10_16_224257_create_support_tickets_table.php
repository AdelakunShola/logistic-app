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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('category', ['delivery_issue', 'payment_issue', 'tracking', 'complaint', 'inquiry', 'technical', 'other'])->default('other');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('subject');
            $table->text('description');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'escalated'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
