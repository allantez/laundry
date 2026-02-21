<?php
// database/migrations/2026_02_13_061020_create_petty_cashes_table.php

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
        Schema::create('petty_cashes', function (Blueprint $table) {
            $table->id();

            // Fund Identification
            $table->string('fund_number')->unique(); // Human-readable fund number
            $table->string('name'); // e.g., "Main Office Petty Cash", "Store Petty Cash"
            $table->string('code')->unique(); // Short code (e.g., PC001, PC-MAIN)

            // Branch Association
            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            // Custodian (person responsible)
            $table->foreignId('custodian_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Financial Details
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('current_balance', 12, 2)->default(0);
            $table->decimal('minimum_balance', 12, 2)->default(0); // Alert when below this
            $table->decimal('maximum_balance', 12, 2)->nullable(); // Maximum allowed

            // Fund Status
            $table->enum('status', [
                'active',        // Normal operations
                'inactive',      // Temporarily not in use
                'closed',        // Permanently closed
                'under_review',  // Being audited
                'replenishing',  // Awaiting top-up
            ])->default('active');

            // Date Information
            $table->date('established_date');
            $table->date('last_replenished_at')->nullable();
            $table->date('last_counted_at')->nullable();
            $table->date('closed_at')->nullable();

            // Replenishment Settings
            $table->boolean('auto_replenish')->default(false);
            $table->decimal('replenishment_threshold', 12, 2)->nullable(); // Auto-replenish when below this
            $table->decimal('replenishment_amount', 12, 2)->nullable(); // Standard top-up amount
            $table->enum('replenishment_method', [
                'cash',
                'bank_transfer',
                'cheque',
                'mpesa',
                'other'
            ])->nullable();

            // Transaction Limits
            $table->decimal('max_transaction_amount', 12, 2)->nullable(); // Max per transaction
            $table->decimal('daily_withdrawal_limit', 12, 2)->nullable(); // Max per day
            $table->integer('max_transactions_per_day')->nullable(); // Max number of transactions

            // Approval Requirements
            $table->boolean('requires_approval')->default(false);
            $table->decimal('approval_threshold', 12, 2)->nullable(); // Amount requiring approval
            $table->foreignId('approver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Accounting
            $table->string('account_code')->nullable(); // Chart of accounts code
            $table->string('gl_account')->nullable(); // General ledger account

            // Location/Description
            $table->string('location')->nullable(); // Physical location of cash
            $table->text('description')->nullable();
            $table->text('purpose')->nullable(); // What this fund is used for

            // Audit Information
            $table->timestamp('last_audited_at')->nullable();
            $table->foreignId('last_audited_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Notes & Metadata
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('fund_number');
            $table->index('code');
            $table->index('branch_id');
            $table->index('custodian_id');
            $table->index('status');
            $table->index('current_balance');
            $table->index('established_date');
            $table->index(['branch_id', 'status']);
            $table->index(['custodian_id', 'status']);
            $table->index(['branch_id', 'current_balance']);
            $table->index('deleted_at');
        });

        // Transactions table for petty cash
        Schema::create('petty_cash_transactions', function (Blueprint $table) {
            $table->id();

            // Transaction Identification
            $table->string('transaction_number')->unique();
            $table->foreignId('petty_cash_id')
                ->constrained()
                ->cascadeOnDelete();

            // Transaction Type
            $table->enum('type', [
                'disbursement',   // Money going out (expense)
                'replenishment',  // Money coming in (top-up)
                'adjustment',     // Manual adjustment
                'transfer',       // Transfer to/from another fund
                'refund',         // Money returned
            ]);

            $table->enum('direction', ['in', 'out']); // Whether money is coming in or going out

            // Amount Information
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);

            // Date Information
            $table->date('transaction_date');
            $table->timestamp('recorded_at')->useCurrent();

            // Category (if disbursement)
            $table->foreignId('expense_category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Reference Information
            $table->string('reference_type')->nullable(); // expense, receipt, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('receipt_number')->nullable();

            // Payee/Recipient Information
            $table->string('payee_name')->nullable(); // Who received the money
            $table->string('payee_type')->nullable(); // staff, supplier, other

            // Description
            $table->string('description');
            $table->text('notes')->nullable();

            // Approval
            $table->boolean('requires_approval')->default(false);
            $table->enum('approval_status', [
                'pending',
                'approved',
                'rejected'
            ])->default('approved');
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Attachments
            $table->string('receipt_path')->nullable();
            $table->json('attachments')->nullable();

            // User Associations
            $table->foreignId('created_by')
                ->constrained('users');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users');

            // Metadata
            $table->json('metadata')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('transaction_number');
            $table->index('petty_cash_id');
            $table->index('type');
            $table->index('direction');
            $table->index('transaction_date');
            $table->index('expense_category_id');
            $table->index('reference_type');
            $table->index('reference_id');
            $table->index('receipt_number');
            $table->index('approval_status');
            $table->index('created_by');
            $table->index(['petty_cash_id', 'transaction_date']);
            $table->index(['petty_cash_id', 'type']);
            $table->index(['petty_cash_id', 'approval_status']);
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_transactions');
        Schema::dropIfExists('petty_cashes');
    }
};
