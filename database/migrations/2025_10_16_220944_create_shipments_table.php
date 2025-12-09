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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 50)->unique();
            $table->string('reference_number', 50)->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('carrier_id')->nullable()->constrained('carriers')->onDelete('set null');
            
            // Pickup Information
            $table->string('pickup_company_name')->nullable();
            $table->string('pickup_contact_name')->nullable();
            $table->string('pickup_contact_phone')->nullable();
            $table->string('pickup_contact_email')->nullable();
            $table->text('pickup_address')->nullable();
            $table->string('pickup_address_line2')->nullable();
            $table->string('pickup_city')->nullable();
            $table->string('pickup_state')->nullable();
            $table->string('pickup_country')->nullable();
            $table->string('pickup_postal_code', 20)->nullable();
            $table->decimal('pickup_latitude', 10, 8)->nullable();
            $table->decimal('pickup_longitude', 11, 8)->nullable();
            
            // Delivery Information
            $table->string('delivery_company_name')->nullable();
            $table->string('delivery_contact_name')->nullable();
            $table->string('delivery_contact_phone')->nullable();
            $table->string('delivery_contact_email')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_address_line2')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_state')->nullable();
            $table->string('delivery_country')->nullable();
            $table->string('delivery_postal_code', 20)->nullable();
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->json('calculated_route')->nullable();
            $table->decimal('route_distance', 10, 2)->nullable(); // km
            $table->integer('estimated_duration')->nullable(); // minutes
            $table->timestamp('route_calculated_at')->nullable();
            $table->timestamp('driver_started_at')->nullable();
            $table->timestamp('driver_arrived_at')->nullable();
            
            // Package Information
            $table->enum('shipment_type', ['Standard Package', 'Document Envelope', 'Freight/Pallet', 'Bulk Cargo'])->default('Standard Package');
            $table->integer('number_of_items')->default(1);
            $table->decimal('total_weight', 10, 2)->default(0); // in lbs
            $table->decimal('total_value', 10, 2)->default(0);
            $table->json('items')->nullable(); // Store item details as JSON
            
            // Service Information
            $table->enum('delivery_priority', ['standard', 'express', 'overnight'])->default('standard');
            $table->enum('payment_mode', ['prepaid', 'cod', 'credit'])->default('prepaid');
            $table->decimal('cod_amount', 10, 2)->default(0);
            
            // Special Services
            $table->boolean('insurance_required')->default(false);
            $table->decimal('insurance_amount', 10, 2)->default(0);
            $table->boolean('signature_required')->default(false);
            $table->boolean('temperature_controlled')->default(false);
            $table->boolean('fragile_handling')->default(false);
            $table->string('preferred_carrier')->nullable();
            $table->string('service_level')->nullable();
            
            // Status & Assignment
            $table->enum('status', [
                'draft',
                'pending',
                'picked_up',
                'in_transit',
                'out_for_delivery',
                'delivered',
                'failed',
                'returned',
                'cancelled'
            ])->default('draft');
            $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->foreignId('current_branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->foreignId('current_hub_id')->nullable()->constrained('hubs')->onDelete('set null');
            
            // Pricing
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('weight_charge', 10, 2)->default(0);
            $table->decimal('distance_charge', 10, 2)->default(0);
            $table->decimal('priority_charge', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('insurance_fee', 10, 2)->default(0);
            $table->decimal('additional_services_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            
            // Dates
            $table->dateTime('pickup_date')->nullable();
            $table->dateTime('preferred_delivery_date')->nullable();
            $table->dateTime('expected_delivery_date')->nullable();
            $table->dateTime('actual_delivery_date')->nullable();
            $table->dateTime('pickup_scheduled_date')->nullable();



            //warehouse
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('origin_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('current_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('destination_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('delivered_from_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');

            $table->timestamp('arrived_at_origin_warehouse')->nullable();
            $table->timestamp('departed_origin_warehouse')->nullable();
            $table->timestamp('arrived_at_destination_warehouse')->nullable();
            $table->timestamp('departed_for_delivery')->nullable();


            // Additional Info
            $table->text('special_instructions')->nullable();
            $table->string('delivery_signature')->nullable();
            $table->string('delivery_photo')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->integer('delivery_attempts')->default(0);
            $table->decimal('customer_rating', 2, 1)->nullable();
            $table->text('customer_feedback')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tracking_number', 'status']);
            $table->index('customer_id');
            $table->index('assigned_driver_id');
            $table->index('status');
            $table->index('created_at');

         
            $table->index('origin_warehouse_id');
            $table->index('current_warehouse_id');
            $table->index('destination_warehouse_id');
            $table->index('delivered_from_warehouse_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
  