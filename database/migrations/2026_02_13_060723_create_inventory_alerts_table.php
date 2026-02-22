<?php
// database/migrations/2026_02_13_060930_create_inventory_alerts_table.php

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
        Schema::create('inventory_alerts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Inventory Item Association
            $table->uuid('inventory_item_id')
                ->constrained()
                ->cascadeOnDelete();

            // Branch Association
            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Alert Type
            $table->enum('type', [
                'low_stock',
                'out_of_stock',
                'expiry',
                'expired',
                'overstock',
                'reorder_needed',
                'inspection_needed',
                'quality_issue',
                'temperature_alert',
                'damaged',
                'theft_suspected',
                'discrepancy',
            ]);

            // Severity Level
            $table->enum('severity', ['info', 'warning', 'critical', 'emergency'])
                ->default('warning');

            // Alert Details
            $table->string('title');
            $table->text('message');
            $table->json('details')->nullable(); // Additional structured data

            // Threshold Values
            $table->decimal('threshold_value', 12, 2)->nullable();
            $table->decimal('current_value', 12, 2)->nullable();
            $table->decimal('difference', 12, 2)->nullable(); // threshold - current

            // For expiry alerts
            $table->date('expiry_date')->nullable();
            $table->integer('days_until_expiry')->nullable();

            // For stock alerts
            $table->decimal('current_stock', 12, 2)->nullable();
            $table->decimal('minimum_stock', 12, 2)->nullable();
            $table->decimal('reorder_point', 12, 2)->nullable();

            // For temperature alerts
            $table->decimal('recorded_temperature', 8, 2)->nullable();
            $table->decimal('min_temperature', 8, 2)->nullable();
            $table->decimal('max_temperature', 8, 2)->nullable();

            // Status
            $table->enum('status', [
                'active',      // Alert is active and unresolved
                'acknowledged', // Staff has seen it
                'resolved',    // Issue has been fixed
                'dismissed',   // Alert was false/invalid
            ])->default('active');

            // Acknowledgment
            $table->timestamp('acknowledged_at')->nullable();
            $table->uuid('acknowledged_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Resolution
            $table->timestamp('resolved_at')->nullable();
            $table->uuid('resolved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('resolution_notes')->nullable();
            $table->enum('resolution_action', [
                'reordered',
                'adjusted',
                'inspected',
                'discarded',
                'transferred',
                'other'
            ])->nullable();

            // Dismissal
            $table->timestamp('dismissed_at')->nullable();
            $table->uuid('dismissed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('dismissal_reason')->nullable();

            // Notification Tracking
            $table->json('notifications_sent')->nullable(); // Track who was notified
            $table->timestamp('last_notified_at')->nullable();
            $table->integer('notification_count')->default(0);

            // Assignment
            $table->uuid('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('assigned_at')->nullable();

            // Escalation
            $table->boolean('is_escalated')->default(false);
            $table->timestamp('escalated_at')->nullable();
            $table->uuid('escalated_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Comments/Notes
            $table->text('comments')->nullable();
            $table->json('comment_history')->nullable(); // Track comments

            // Metadata
            $table->json('metadata')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('inventory_item_id');
            $table->index('branch_id');
            $table->index('type');
            $table->index('severity');
            $table->index('status');
            $table->index('assigned_to');
            $table->index('acknowledged_at');
            $table->index('resolved_at');
            $table->index('expiry_date');
            $table->index('created_at');
            $table->index(['branch_id', 'status']);
            $table->index(['type', 'status']);
            $table->index(['severity', 'status']);
            $table->index(['inventory_item_id', 'type']);
            $table->index(['assigned_to', 'status']);
            $table->index('is_escalated');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_alerts');
    }
};
