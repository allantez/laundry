<?php
// database/migrations/2026_02_13_060820_create_order_items_table.php

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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Order Association
            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Service/Item Associations
            $table->foreignId('service_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('service_item_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Branch Association (for reporting)
            $table->foreignId('branch_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Item Details
            $table->string('name'); // Snapshot of item name at time of order
            $table->string('description')->nullable();
            $table->string('sku')->nullable(); // Stock keeping unit

            // Item Classification
            $table->string('category')->nullable(); // wash, dry, iron, etc.
            $table->string('item_type')->nullable(); // shirt, pants, dress, etc.
            $table->string('fabric_type')->nullable(); // cotton, silk, wool, etc.
            $table->string('color')->nullable();
            $table->string('size')->nullable(); // S, M, L, XL

            // Quantity & Pricing
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0); // Before tax/discount
            $table->decimal('total', 12, 2)->default(0); // After tax/discount

            // Add-ons & Modifiers
            $table->json('add_ons')->nullable(); // Extra services (stain removal, etc.)
            $table->json('modifiers')->nullable(); // Price modifiers applied
            $table->decimal('add_ons_total', 12, 2)->default(0);

            // Special Instructions
            $table->text('customer_notes')->nullable();
            $table->text('staff_notes')->nullable();
            $table->json('special_instructions')->nullable();

            // Status Tracking (per item)
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->timestamp('status_updated_at')->nullable();
            $table->foreignId('status_updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Processing Details
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Quality Control
            $table->boolean('requires_inspection')->default(false);
            $table->boolean('inspected')->default(false);
            $table->foreignId('inspected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('inspected_at')->nullable();
            $table->text('inspection_notes')->nullable();

            // Inventory Tracking
            $table->boolean('track_inventory')->default(false);
            $table->foreignId('inventory_item_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('inventory_quantity_used', 10, 2)->nullable();
            $table->boolean('inventory_deducted')->default(false);

            // Flags
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_express')->default(false);
            $table->boolean('is_insured')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->string('flag_reason')->nullable();

            // Metadata
            $table->json('metadata')->nullable(); // Flexible storage

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('order_id');
            $table->index('service_id');
            $table->index('service_item_id');
            $table->index('branch_id');
            $table->index('status');
            $table->index('assigned_to');
            $table->index('category');
            $table->index('item_type');
            $table->index(['order_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index(['branch_id', 'status']);
            $table->index('requires_inspection');
            $table->index('inspected');
            $table->index('is_flagged');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
