<?php
// database/migrations/2026_02_13_061000_create_expense_categories_table.php

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
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Branch Association (if categories are branch-specific)
            $table->uuid('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique(); // Category code (e.g., UTIL001, SUPPLY001)
            $table->text('description')->nullable();

            // Category Hierarchy
            $table->uuid('parent_id')
                ->nullable()
                ->constrained('expense_categories')
                ->nullOnDelete();

            $table->string('path')->nullable(); // Materialized path for hierarchy

            // Category Type
            $table->enum('type', [
                'operational',      // Day-to-day operations
                'administrative',   // Admin costs
                'utility',          // Electricity, water, etc.
                'supply',           // Supplies and materials
                'equipment',        // Equipment purchase/maintenance
                'rent',             // Rent/Lease
                'salary',           // Staff salaries
                'marketing',        // Advertising, promotions
                'transport',        // Delivery, fuel
                'maintenance',      // Repairs and maintenance
                'insurance',        // Insurance premiums
                'tax',              // Taxes and licenses
                'miscellaneous',    // Other expenses
            ])->default('operational');

            // Tax Settings
            $table->boolean('is_taxable')->default(false);
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->string('tax_code')->nullable(); // VAT code, etc.

            // Budgeting
            $table->decimal('monthly_budget', 12, 2)->nullable();
            $table->decimal('yearly_budget', 12, 2)->nullable();
            $table->boolean('track_budget')->default(false);
            $table->boolean('alert_on_exceed')->default(false);
            $table->decimal('budget_alert_threshold', 5, 2)->default(80); // Alert at 80% of budget

            // Accounting
            $table->string('account_code')->nullable(); // Chart of accounts code
            $table->string('account_type')->nullable(); // Asset, liability, equity, revenue, expense

            // Approval Workflow
            $table->boolean('requires_approval')->default(false);
            $table->decimal('approval_threshold', 12, 2)->nullable(); // Amount requiring approval
            $table->uuid('approver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Display Options
            $table->string('color')->nullable(); // For UI display
            $table->string('icon')->nullable(); // FontAwesome or custom icon
            $table->integer('sort_order')->default(0);

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false); // System categories cannot be deleted

            // Metadata
            $table->json('metadata')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('branch_id');
            $table->index('parent_id');
            $table->index('type');
            $table->index('is_active');
            $table->index('code');
            $table->index('slug');
            $table->index('path');
            $table->index(['branch_id', 'type']);
            $table->index(['branch_id', 'is_active']);
            $table->index(['parent_id', 'is_active']);
            $table->index('deleted_at');

            // Composite indexes for reporting
            $table->index(['type', 'is_active']);
            $table->index(['account_code', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_categories');
    }
};
