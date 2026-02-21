<?php
// database/migrations/2026_02_13_061010_create_expenses_table.php

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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // Expense Identification
            $table->string('expense_number')->unique(); // Human-readable expense number
            $table->string('receipt_number')->nullable()->unique();
            $table->string('invoice_number')->nullable();

            // Branch Association (CRITICAL)
            $table->foreignId('branch_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Category Association
            $table->foreignId('expense_category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Supplier Association (MOVED AFTER expense_category_id as requested)
            $table->foreignId('supplier_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // User Associations
            $table->foreignId('created_by')
                  ->constrained('users');

            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users');

            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Basic Expense Details
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2); // amount + tax_amount

            // Date Information
            $table->date('expense_date');
            $table->date('due_date')->nullable(); // For bills to pay
            $table->date('paid_date')->nullable();

            // Payment Information
            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'cheque',
                'mpesa',
                'credit_card',
                'debit_card',
                'mobile_money',
                'credit_account',
                'other'
            ])->default('cash');

            $table->string('payment_reference')->nullable(); // Transaction ID, cheque number, etc.
            $table->enum('payment_status', [
                'pending',
                'paid',
                'partially_paid',
                'overdue',
                'cancelled'
            ])->default('pending');

            // Tax Information
            $table->boolean('is_taxable')->default(false);
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->string('tax_code')->nullable();

            // Recurring Expenses
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', [
                'daily',
                'weekly',
                'monthly',
                'quarterly',
                'yearly'
            ])->nullable();
            $table->date('recurring_end_date')->nullable();
            $table->integer('recurring_count')->nullable(); // Number of times to repeat

            // Budget Tracking
            $table->boolean('is_budgeted')->default(false);
            $table->decimal('budget_amount', 12, 2)->nullable();
            $table->decimal('budget_variance', 12, 2)->nullable(); // amount - budget_amount

            // Approval Workflow
            $table->boolean('requires_approval')->default(false);
            $table->enum('approval_status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Document Management
            $table->string('receipt_path')->nullable(); // Path to receipt image/PDF
            $table->string('invoice_path')->nullable();
            $table->json('attachments')->nullable(); // Additional documents

            // For Inventory Purchases
            $table->boolean('is_inventory_purchase')->default(false);
            $table->foreignId('inventory_item_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->decimal('quantity_purchased', 12, 2)->nullable();
            $table->decimal('unit_cost', 12, 2)->nullable();

            // For Utility Bills
            $table->string('utility_type')->nullable(); // electricity, water, internet, etc.
            $table->string('meter_number')->nullable();
            $table->decimal('units_consumed', 12, 2)->nullable();

            // For Staff Expenses
            $table->foreignId('staff_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->enum('expense_type', [
                'salary',
                'advance',
                'reimbursement',
                'bonus',
                'commission',
                'other'
            ])->nullable();

            // Reconciliation
            $table->boolean('is_reconciled')->default(false);
            $table->timestamp('reconciled_at')->nullable();
            $table->foreignId('reconciled_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Notes & Metadata
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->json('tags')->nullable();

            // Flags
            $table->boolean('is_flagged')->default(false);
            $table->string('flag_reason')->nullable();
            $table->foreignId('flagged_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('expense_number');
            $table->index('receipt_number');
            $table->index('invoice_number');
            $table->index('branch_id');
            $table->index('expense_category_id');
            $table->index('supplier_id');
            $table->index('created_by');
            $table->index('expense_date');
            $table->index('due_date');
            $table->index('paid_date');
            $table->index('payment_method');
            $table->index('payment_status');
            $table->index('approval_status');
            $table->index('is_recurring');
            $table->index('is_inventory_purchase');
            $table->index('is_reconciled');
            $table->index('is_flagged');
            $table->index(['branch_id', 'expense_date']);
            $table->index(['branch_id', 'expense_category_id']);
            $table->index(['branch_id', 'payment_status']);
            $table->index(['expense_category_id', 'expense_date']);
            $table->index(['supplier_id', 'expense_date']);
            $table->index(['approval_status', 'requires_approval']);
            $table->index('deleted_at');

            // Composite indexes for reporting
            $table->index(['branch_id', 'expense_category_id', 'expense_date']);
            $table->index(['branch_id', 'payment_status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
