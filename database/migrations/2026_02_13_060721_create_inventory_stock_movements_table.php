<?php
// database/migrations/2026_02_13_060721_create_inventory_stock_movements_table.php

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
        Schema::create('inventory_stock_movements', function (Blueprint $table) {
            $table->id();

            // Inventory Item Association
            $table->foreignId('inventory_item_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Branch Association
            $table->foreignId('branch_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // User Associations
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Movement Details
            $table->enum('movement_type', [
                'purchase',           // Stock purchased from supplier
                'sale',               // Stock used/sold in order
                'return',             // Stock returned by customer
                'adjustment',         // Manual stock adjustment
                'transfer',           // Transfer to another branch
                'waste',              // Damaged/expired/wasted
                'return_to_supplier', // Returned to supplier
                'initial_stock',      // Initial stock setup
            ]);

            $table->enum('direction', ['in', 'out']); // Whether stock is coming in or going out

            // Quantity & Values
            $table->decimal('quantity', 12, 2);
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable(); // quantity * unit_cost

            // Stock Levels Before/After
            $table->decimal('previous_stock', 12, 2)->nullable();
            $table->decimal('new_stock', 12, 2)->nullable();
            $table->decimal('change_amount', 12, 2)->nullable(); // new_stock - previous_stock

            // Reference Information (Polymorphic)
            $table->string('reference_type')->nullable(); // order, purchase, return, adjustment
            $table->unsignedBigInteger('reference_id')->nullable();

            // For Transfers
            $table->foreignId('from_branch_id')
                  ->nullable()
                  ->constrained('branches')
                  ->nullOnDelete();

            $table->foreignId('to_branch_id')
                  ->nullable()
                  ->constrained('branches')
                  ->nullOnDelete();

            // Reason & Notes
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();

            // Status
            $table->enum('status', [
                'pending',      // Awaiting approval
                'approved',     // Approved and processed
                'cancelled',    // Cancelled
                'rejected',     // Rejected
            ])->default('approved');

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();

            // Document References
            $table->string('document_number')->nullable(); // PO number, invoice number, etc.
            $table->string('document_path')->nullable(); // Path to supporting document

            // Metadata
            $table->json('metadata')->nullable(); // Additional data

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance - WITH SHORTER NAMES
            $table->index('inventory_item_id', 'ism_item_idx');
            $table->index('branch_id', 'ism_branch_idx');
            $table->index('created_by', 'ism_created_by_idx');
            $table->index('movement_type', 'ism_type_idx');
            $table->index('direction', 'ism_dir_idx');
            $table->index('status', 'ism_status_idx');
            $table->index('reference_type', 'ism_ref_type_idx');
            $table->index('reference_id', 'ism_ref_id_idx');
            $table->index('document_number', 'ism_doc_num_idx');
            $table->index('created_at', 'ism_created_idx');

            // Composite indexes with SHORTER NAMES
            $table->index(['inventory_item_id', 'movement_type'], 'ism_item_type_idx');
            $table->index(['inventory_item_id', 'created_at'], 'ism_item_date_idx');
            $table->index(['branch_id', 'movement_type'], 'ism_branch_type_idx');
            $table->index(['reference_type', 'reference_id'], 'ism_ref_idx');
            $table->index(['from_branch_id', 'to_branch_id'], 'ism_transfer_idx');

            // 🔴 FIXED: Shortened this index name
            $table->index(['branch_id', 'movement_type', 'created_at'], 'ism_branch_type_date_idx');

            $table->index('deleted_at', 'ism_deleted_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_movements');
    }
};
