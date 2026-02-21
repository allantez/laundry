<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserPermissions extends Command
{
    protected $signature = 'user:check {email} {--branch=}';
    protected $description = 'Check user permissions and roles';

    public function handle()
    {
        $email = $this->argument('email');
        $branchId = $this->option('branch');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Status: " . ($user->is_active ? 'Active' : 'Inactive'));
        $this->info("Default Branch: " . ($user->branch ? $user->branch->name : 'None'));
        $this->line('');

        // Global roles
        $globalRoles = $user->rolesForBranch(null);
        $this->info('Global Roles:');
        if ($globalRoles->isEmpty()) {
            $this->warn('  None');
        } else {
            foreach ($globalRoles as $role) {
                $this->line("  - {$role->name}");
            }
        }
        $this->line('');

        // Branch-specific roles
        $branchRoles = $user->branchRoles()
            ->with(['role', 'branch'])
            ->whereNotNull('branch_id')
            ->get();

        $this->info('Branch-Specific Roles:');
        if ($branchRoles->isEmpty()) {
            $this->warn('  None');
        } else {
            foreach ($branchRoles as $assignment) {
                $expiry = $assignment->expires_at ? ' (expires: ' . $assignment->expires_at->format('Y-m-d') . ')' : '';
                $this->line("  - {$assignment->role->name} @ {$assignment->branch->name}{$expiry}");
            }
        }
        $this->line('');

        // All permissions
        $this->info('All Permissions:');
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        if (empty($permissions)) {
            $this->warn('  None');
        } else {
            foreach ($permissions as $permission) {
                $this->line("  - {$permission}");
            }
        }
        $this->line('');

        // Branch-specific permissions
        if ($branchId) {
            $branch = \App\Models\Branch::find($branchId);
            if ($branch) {
                $branchPermissions = $user->getBranchPermissions($branch);
                $this->info("Permissions for {$branch->name}:");
                if (empty($branchPermissions)) {
                    $this->warn('  None');
                } else {
                    foreach ($branchPermissions as $permission) {
                        $this->line("  - {$permission}");
                    }
                }
            }
        }

        return 0;
    }
}
