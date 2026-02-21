<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            /**
             * Identification
             */
            $table->string('payment_number')->unique();
            $table->string('receipt_number')->nullable()->unique();

            /**
             * Relationships
             */
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * Audit
             */
            $table->foreignId('created_by')
                ->constrained('users');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('confirmed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /**
             * Financial Amounts
             */
            $table->string('currency', 3)->default('KES');

            $table->decimal('amount', 14, 2);            // Base amount paid
            $table->decimal('tip_amount', 14, 2)->default(0);
            $table->decimal('change_amount', 14, 2)->default(0);
            $table->decimal('refunded_amount', 14, 2)->default(0);

            /**
             * Payment Method (use PHP Enums instead of DB enums)
             */
            $table->string('payment_method');     // App\Enums\PaymentMethod
            $table->string('payment_channel')->nullable(); // Visa, Mpesa, Airtel

            /**
             * Status (use PHP Enum)
             */
            $table->string('status')->default('pending'); // App\Enums\PaymentStatus

            /**
             * External Transaction Data
             */
            $table->string('transaction_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('authorization_code')->nullable();

            /**
             * Cheque Details
             */
            $table->string('cheque_number')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('cheque_bank')->nullable();
            $table->timestamp('cheque_cleared_at')->nullable();

            /**
             * Card Details
             */
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_holder_name')->nullable();

            /**
             * Mobile Money (Mpesa etc)
             */
            $table->string('mobile_number')->nullable();
            $table->string('mobile_name')->nullable();
            $table->timestamp('mobile_transaction_time')->nullable();

            /**
             * Split Payments
             */
            $table->unsignedInteger('split_sequence')->nullable();

            /**
             * Refund Handling
             * (Self-referencing instead of refund fields everywhere)
             */
            $table->foreignId('parent_payment_id')
                ->nullable()
                ->constrained('payments')
                ->nullOnDelete();

            /**
             * Reconciliation
             */
            $table->boolean('is_reconciled')->default(false);
            $table->timestamp('reconciled_at')->nullable();
            $table->foreignId('reconciled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /**
             * Metadata
             */
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            $table->text('staff_notes')->nullable();

            /**
             * System Flags
             */
            $table->boolean('is_flagged')->default(false);
            $table->string('flag_reason')->nullable();
            $table->foreignId('flagged_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /**
             * Dates
             */
            $table->timestamp('payment_date')->useCurrent();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /**
             * Indexes
             */
            $table->index(['branch_id', 'payment_date']);
            $table->index(['branch_id', 'status']);
            $table->index(['payment_method', 'status']);
            $table->index(['order_id', 'status']);
            $table->index(['parent_payment_id']);
            $table->index(['deleted_at']);

            /**
             * Prevent duplicate split sequence per order
             */
            $table->unique(['order_id', 'split_sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
