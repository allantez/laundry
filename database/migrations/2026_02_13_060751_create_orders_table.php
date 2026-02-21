<?php
// database/migrations/2026_02_13_060800_create_orders_table.php

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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('uuid')->unique();

            // Order Identification
            $table->string('order_number')->unique(); // Human-readable order number (e.g., ORD-2026-0001)
            $table->string('invoice_number')->unique()->nullable(); // Invoice number

            // Branch Association (CRITICAL)
            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            // Customer Association
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Staff Associations
            $table->foreignId('created_by')
                ->constrained('users');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users');

            $table->foreignId('assigned_to') // Staff assigned to process this order
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Order Status
            $table->string('status')->default('pending'); // pending, processing, ready, delivered, cancelled, completed
            $table->timestamp('status_updated_at')->nullable();
            $table->foreignId('status_updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Order Details
            $table->enum('order_type', ['pickup', 'delivery', 'walk_in'])->default('walk_in');
            $table->enum('service_type', ['regular', 'express'])->default('regular');
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'refunded'])->default('pending');

            // Dates & Times
            $table->datetime('order_date')->useCurrent();
            $table->datetime('requested_pickup_date')->nullable();
            $table->datetime('requested_delivery_date')->nullable();
            $table->datetime('actual_pickup_date')->nullable();
            $table->datetime('actual_delivery_date')->nullable();
            $table->datetime('promised_completion_date')->nullable();
            $table->datetime('completed_at')->nullable();

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

            // Delivery/Pickup Address (if different from customer address)
            $table->text('delivery_address')->nullable();
            $table->string('delivery_contact_name')->nullable();
            $table->string('delivery_contact_phone')->nullable();
            $table->text('delivery_instructions')->nullable();

            $table->text('pickup_address')->nullable();
            $table->string('pickup_contact_name')->nullable();
            $table->string('pickup_contact_phone')->nullable();
            $table->text('pickup_instructions')->nullable();

            // Special Instructions
            $table->text('customer_notes')->nullable();
            $table->text('staff_notes')->nullable();
            $table->json('special_instructions')->nullable(); // JSON for structured instructions

            // Order Metadata
            $table->json('metadata')->nullable(); // Flexible storage for extra data
            $table->json('tags')->nullable(); // For categorization/filtering

            // Flags
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_insured')->default(false);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->boolean('is_flagged')->default(false);
            $table->string('flag_reason')->nullable();
            $table->foreignId('flagged_by')->nullable()->constrained('users')->nullOnDelete();

            // Audit Trail
            $table->json('status_history')->nullable(); // Track status changes
            $table->json('payment_history')->nullable(); // Track payment history

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('order_number');
            $table->index('invoice_number');
            $table->index('branch_id');
            $table->index('customer_id');
            $table->index('created_by');
            $table->index('assigned_to');
            $table->index('status');
            $table->index('payment_status');
            $table->index('order_type');
            $table->index('service_type');
            $table->index('order_date');
            $table->index('requested_delivery_date');
            $table->index('actual_delivery_date');
            $table->index('completed_at');
            $table->index(['branch_id', 'status']);
            $table->index(['branch_id', 'order_date']);
            $table->index(['customer_id', 'status']);
            $table->index(['status', 'payment_status']);
            $table->index('is_urgent');
            $table->index('is_flagged');
            $table->index('deleted_at');

            // Composite indexes for reporting
            $table->index(['branch_id', 'status', 'order_date']);
            $table->index(['branch_id', 'payment_status', 'order_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
