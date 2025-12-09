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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');
            $table->enum('report_type', [
                'shipment_summary',
                'revenue',
                'driver_performance',
                'branch_performance',
                'warehouse_performance',
                'customer_analysis',
                'payment_summary',
                'expense_report'
            ]);
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->date('date_from');
            $table->date('date_to');
            $table->json('filters')->nullable();
            $table->string('file_path')->nullable(); // path to generated PDF/Excel
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
                $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->index(['report_type', 'generated_by']);
            $table->index('created_at');        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
