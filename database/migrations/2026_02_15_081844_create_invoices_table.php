<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->uuid('branch_id')->constrained()->cascadeOnDelete();
            $table->uuid('order_id')->constrained()->cascadeOnDelete();

            $table->string('invoice_number')->unique();
            $table->date('issue_date');
            $table->date('due_date')->nullable();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            $table->enum('status', ['draft', 'issued', 'partial', 'paid', 'cancelled'])
                ->default('draft')
                ->index();

            $table->uuid('created_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['branch_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
