<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            // Primary Keys
            $table->id(); // Fast joins
            $table->uuid('uuid')->unique(); // Public reference

            // Branch (INT foreign key)
            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            // Order Numbering
            $table->string('order_number')->unique();
            $table->string('invoice_number')->nullable()->unique();

            // Sequence System (for formatted numbers)
            $table->unsignedBigInteger('sequence');
            $table->year('year');

            // Customer (foreign key)
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->nullOnDelete();

            // Staff Associations
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            // 🔴 FIXED: Changed from "unisgnedBigInteger" to "unsignedBigInteger"
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->foreign('assigned_to')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->unsignedBigInteger('status_updated_by')->nullable();
            $table->foreign('status_updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Order Status
            $table->string('status')->default('pending');
            $table->timestamp('status_updated_at')->nullable();

            // Order Types
            $table->enum('order_type', ['pickup', 'delivery', 'walk_in'])
                ->default('walk_in');

            $table->enum('service_type', ['regular', 'express'])
                ->default('regular');

            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'refunded'])
                ->default('pending');

            // Dates
            $table->dateTime('order_date')->useCurrent();
            $table->dateTime('requested_pickup_date')->nullable();
            $table->dateTime('requested_delivery_date')->nullable();
            $table->dateTime('actual_pickup_date')->nullable();
            $table->dateTime('actual_delivery_date')->nullable();
            $table->dateTime('promised_completion_date')->nullable();
            $table->dateTime('completed_at')->nullable();

            // Financial Summary
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('pickup_fee', 12, 2)->default(0);
            $table->decimal('extra_charges', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);

            // Discount Details
            $table->string('discount_code')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 12, 2)->nullable();

            // Tax Details
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->string('tax_description')->nullable();

            // Delivery / Pickup Details
            $table->text('delivery_address')->nullable();
            $table->string('delivery_contact_name')->nullable();
            $table->string('delivery_contact_phone')->nullable();
            $table->text('delivery_instructions')->nullable();

            $table->text('pickup_address')->nullable();
            $table->string('pickup_contact_name')->nullable();
            $table->string('pickup_contact_phone')->nullable();
            $table->text('pickup_instructions')->nullable();

            // Notes
            $table->text('customer_notes')->nullable();
            $table->text('staff_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->json('tags')->nullable();

            // Flags & Approval
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_insured')->default(false);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_approved')->default(false);

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->boolean('is_flagged')->default(false);
            $table->string('flag_reason')->nullable();

            $table->unsignedBigInteger('flagged_by')->nullable();
            $table->foreign('flagged_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | PERFORMANCE INDEXES
            |--------------------------------------------------------------------------
            */

            // Composite unique sequence per branch per year
            $table->unique(['branch_id', 'year', 'sequence']);

            // Reporting & filtering
            $table->index(['branch_id', 'status']);
            $table->index(['branch_id', 'order_date']);
            $table->index(['branch_id', 'payment_status']);
            $table->index(['customer_id', 'status']);
            $table->index(['status', 'payment_status']);
            $table->index('completed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
