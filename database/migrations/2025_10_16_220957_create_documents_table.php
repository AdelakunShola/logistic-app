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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('document_type'); // invoice, receipt, manifest, pod, etc.
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_type', 50); // pdf, jpg, png, etc.
            $table->integer('file_size')->nullable(); // in bytes
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('shipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
