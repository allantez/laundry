<?php
// database/migrations/2026_02_13_060900_create_inventory_items_table.php

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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Branch Association
            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Supplier Association
            $table->uuid('supplier_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Basic Information
            $table->string('name');
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->string('barcode')->nullable()->unique();
            $table->string('category'); // detergent, softener, bleach, packaging, equipment, etc.
            $table->string('sub_category')->nullable();
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();

            // Unit of Measurement
            $table->string('unit_type'); // piece, kg, liter, ml, g, box, bottle, pack
            $table->decimal('unit_size', 10, 2)->nullable(); // e.g., 5 for 5L
            $table->string('unit_size_type')->nullable(); // L, ml, kg, g

            // Stock Tracking
            $table->decimal('current_stock', 12, 2)->default(0);
            $table->decimal('minimum_stock', 12, 2)->default(0); // Reorder level
            $table->decimal('maximum_stock', 12, 2)->nullable(); // Max capacity
            $table->decimal('reorder_point', 12, 2)->default(0); // When to reorder
            $table->decimal('reorder_quantity', 12, 2)->nullable(); // How much to order

            // Cost & Pricing
            $table->decimal('unit_cost', 12, 2)->default(0); // Purchase cost
            $table->decimal('average_cost', 12, 2)->default(0); // Weighted average cost
            $table->decimal('last_cost', 12, 2)->nullable(); // Last purchase cost
            $table->decimal('selling_price', 12, 2)->nullable(); // If sold directly
            $table->decimal('markup_percentage', 5, 2)->nullable();

            // Location Tracking
            $table->string('location')->nullable(); // Warehouse, shelf, bin location
            $table->string('aisle')->nullable();
            $table->string('rack')->nullable();
            $table->string('bin')->nullable();

            // Expiry Tracking
            $table->boolean('track_expiry')->default(false);
            $table->date('expiry_date')->nullable();
            $table->integer('shelf_life_days')->nullable(); // Days until expiry
            $table->timestamp('last_expiry_check')->nullable();

            // Batch/Lot Tracking
            $table->boolean('track_batches')->default(false);
            $table->string('batch_number')->nullable();
            $table->string('lot_number')->nullable();
            $table->date('manufacturing_date')->nullable();

            // Inventory Valuation
            $table->decimal('total_value', 15, 2)->default(0); // current_stock * average_cost

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_taxable')->default(true);
            $table->decimal('tax_rate', 5, 2)->nullable();

            // Stock Alerts
            $table->boolean('alert_on_low_stock')->default(true);
            $table->boolean('alert_on_expiry')->default(true);
            $table->integer('alert_before_days')->default(30); // Alert X days before expiry

            // Media
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->json('documents')->nullable(); // MSDS, specification sheets

            // Specifications
            $table->json('specifications')->nullable(); // Technical specs
            $table->json('ingredients')->nullable(); // For chemicals/detergents
            $table->json('safety_info')->nullable(); // Safety handling instructions

            // Usage Tracking
            $table->decimal('total_quantity_used', 15, 2)->default(0);
            $table->decimal('total_quantity_purchased', 15, 2)->default(0);
            $table->decimal('total_quantity_wasted', 15, 2)->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('last_purchased_at')->nullable();
            $table->timestamp('last_counted_at')->nullable();

            // Notes & Metadata
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->json('tags')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('branch_id');
            $table->index('supplier_id');
            $table->index('sku');
            $table->index('barcode');
            $table->index('name');
            $table->index('category');
            $table->index('is_active');
            $table->index('current_stock');
            $table->index('minimum_stock');
            $table->index('expiry_date');
            $table->index('batch_number');
            $table->index('lot_number');
            $table->index(['branch_id', 'category']);
            $table->index(['branch_id', 'is_active']);
            $table->index(['branch_id', 'current_stock']);
            $table->index(['category', 'is_active']);
            $table->index('deleted_at');

            // Composite indexes for reporting
            $table->index(['branch_id', 'category', 'is_active']);
            $table->index(['supplier_id', 'is_active']);
            $table->index(['expiry_date', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
