<?php
// database/migrations/2026_02_13_060722_create_inventory_stocks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Inventory Item Association
            $table->uuid('inventory_item_id')
                ->constrained()
                ->cascadeOnDelete();

            // Branch Association
            $table->uuid('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Batch/Lot Information
            $table->string('batch_number', 50)->nullable(); // 🔴 Added length limit
            $table->string('lot_number', 50)->nullable(); // 🔴 Added length limit
            $table->string('serial_number', 100)->nullable(); // 🔴 Added length limit

            // Quantity Information
            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('reserved_quantity', 12, 2)->default(0);
            $table->decimal('available_quantity', 12, 2)->default(0);
            $table->decimal('minimum_quantity', 12, 2)->default(0);
            $table->decimal('maximum_quantity', 12, 2)->nullable();

            // Cost Information
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);

            // Date Information
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('received_date')->nullable();
            $table->date('last_counted_date')->nullable();

            // Location Information - 🔴 ADDED LENGTH LIMITS
            $table->string('warehouse', 50)->nullable();
            $table->string('aisle', 30)->nullable();
            $table->string('rack', 30)->nullable();
            $table->string('bin', 30)->nullable();
            $table->string('shelf', 30)->nullable();

            // Status
            $table->enum('status', [
                'available',
                'reserved',
                'quarantined',
                'expired',
                'damaged',
                'returned',
                'in_transit'
            ])->default('available');

            // Quality Control
            $table->boolean('needs_inspection')->default(false);
            $table->boolean('inspected')->default(false);
            $table->uuid('inspected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('inspected_at')->nullable();
            $table->string('quality_grade', 10)->nullable(); // 🔴 Added length limit
            $table->text('inspection_notes')->nullable();

            // Supplier Information
            $table->uuid('supplier_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('supplier_batch_ref', 100)->nullable(); // 🔴 Added length limit

            // Purchase Information
            $table->string('purchase_order_number', 50)->nullable(); // 🔴 Added length limit
            $table->string('invoice_number', 50)->nullable(); // 🔴 Added length limit

            // Storage Conditions
            $table->string('storage_condition', 20)->nullable(); // 🔴 Added length limit
            $table->decimal('temperature_min', 5, 2)->nullable();
            $table->decimal('temperature_max', 5, 2)->nullable();
            $table->decimal('humidity_min', 5, 2)->nullable();
            $table->decimal('humidity_max', 5, 2)->nullable();

            // Attributes
            $table->json('attributes')->nullable();
            $table->json('certifications')->nullable();

            // Notes & Metadata
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes with short names - 🔴 REMOVED THE PROBLEMATIC COMPOSITE INDEX
            $table->index('inventory_item_id', 'is_item_idx');
            $table->index('branch_id', 'is_branch_idx');
            $table->index('batch_number', 'is_batch_idx');
            $table->index('lot_number', 'is_lot_idx');
            $table->index('status', 'is_status_idx');
            $table->index('expiry_date', 'is_expiry_idx');
            $table->index('supplier_id', 'is_supplier_idx');
            $table->index('warehouse', 'is_wh_idx'); // 🔴 Individual indexes instead
            $table->index('aisle', 'is_aisle_idx');
            $table->index('rack', 'is_rack_idx');
            $table->index('bin', 'is_bin_idx');

            // Composite indexes (keeping only necessary ones)
            $table->index(['inventory_item_id', 'status'], 'is_item_status_idx');
            $table->index(['inventory_item_id', 'expiry_date'], 'is_item_expiry_idx');
            $table->index(['branch_id', 'status'], 'is_branch_status_idx');

            // 🔴 REMOVED THIS PROBLEMATIC INDEX:
            // $table->index(['warehouse', 'aisle', 'rack', 'bin'], 'is_location_idx');

            $table->index('deleted_at', 'is_deleted_idx');

            // Unique constraint (with shorter columns)
            $table->unique(['inventory_item_id', 'batch_number', 'lot_number'], 'is_batch_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stocks');
    }
};
