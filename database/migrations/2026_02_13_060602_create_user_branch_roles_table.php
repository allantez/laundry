// 2026_02_13_060700_create_user_branch_roles_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_branch_roles', function (Blueprint $table) {
            $table->id();

            // User assignment (who)
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Branch assignment (where)
            $table->foreignId('branch_id')
                ->nullable() // NULL = Global/Super Admin access
                ->constrained()
                ->cascadeOnDelete();

            // Role assignment (what)
            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnDelete();

            // Assignment metadata
            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('expires_at')->nullable(); // For temporary assignments
            $table->timestamps();
            $table->softDeletes(); // For audit trail

            // Unique constraint prevents duplicates
            $table->unique(['user_id', 'branch_id', 'role_id'], 'user_branch_role_unique');

            // Indexes for common queries
            $table->index(['user_id', 'branch_id']);
            $table->index('role_id');
            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_branch_roles');
    }
};
