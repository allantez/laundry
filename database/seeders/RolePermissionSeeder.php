<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Branch;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =========================================================================
        // CREATE PERMISSIONS
        // =========================================================================
        $permissions = [
            // Branch Management
            'view branches', 'create branches', 'edit branches', 'delete branches',

            // User Management
            'view users', 'create users', 'edit users', 'delete users',
            'assign roles', 'revoke roles',

            // Role Management
            'view roles', 'create roles', 'edit roles', 'delete roles',

            // Customer Management
            'view customers', 'create customers', 'edit customers', 'delete customers',

            // Service Management
            'view services', 'create services', 'edit services', 'delete services',

            // Order Management
            'view orders', 'create orders', 'edit orders', 'delete orders',
            'process orders', 'complete orders', 'cancel orders',
            'assign orders',

            // Payment Management
            'view payments', 'create payments', 'edit payments', 'delete payments',
            'refund payments',

            // Inventory Management
            'view inventory', 'create inventory', 'edit inventory', 'delete inventory',
            'adjust stock', 'view stock movements',

            // Expense Management
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',

            // Petty Cash Management
            'view petty cash', 'create petty cash', 'edit petty cash', 'delete petty cash',
            'disburse petty cash', 'replenish petty cash',

            // Reports
            'view reports', 'export reports', 'view analytics',

            // Dashboard
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // =========================================================================
        // CREATE ROLES
        // =========================================================================

        // Super Admin - Global access, no branch restrictions
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);
        $superAdmin->syncPermissions(Permission::all());

        // Branch Manager - Full access within their branch
        $branchManager = Role::firstOrCreate([
            'name' => 'Branch Manager',
            'guard_name' => 'web'
        ]);
        $branchManager->syncPermissions([
            'view dashboard',
            'view customers', 'create customers', 'edit customers',
            'view services', 'create services', 'edit services',
            'view orders', 'create orders', 'edit orders', 'process orders', 'complete orders', 'cancel orders', 'assign orders',
            'view payments', 'create payments', 'edit payments', 'refund payments',
            'view inventory', 'create inventory', 'edit inventory', 'adjust stock',
            'view expenses', 'create expenses', 'edit expenses',
            'view petty cash', 'create petty cash', 'edit petty cash', 'disburse petty cash', 'replenish petty cash',
            'view reports', 'export reports',
            'view users', 'edit users', 'assign roles', // Can manage branch staff
        ]);

        // Cashier - Payment and order creation
        $cashier = Role::firstOrCreate([
            'name' => 'Cashier',
            'guard_name' => 'web'
        ]);
        $cashier->syncPermissions([
            'view dashboard',
            'view customers', 'create customers',
            'view services',
            'view orders', 'create orders', 'edit orders',
            'view payments', 'create payments',
            'view inventory',
        ]);

        // Laundry Staff - Process orders
        $laundryStaff = Role::firstOrCreate([
            'name' => 'Laundry Staff',
            'guard_name' => 'web'
        ]);
        $laundryStaff->syncPermissions([
            'view dashboard',
            'view orders', 'process orders', 'complete orders',
            'view inventory', 'adjust stock',
        ]);

        // Driver - Handle deliveries
        $driver = Role::firstOrCreate([
            'name' => 'Driver',
            'guard_name' => 'web'
        ]);
        $driver->syncPermissions([
            'view dashboard',
            'view orders', 'edit orders',
        ]);

        // Accountant - Handle finances
        $accountant = Role::firstOrCreate([
            'name' => 'Accountant',
            'guard_name' => 'web'
        ]);
        $accountant->syncPermissions([
            'view dashboard',
            'view payments', 'edit payments', 'refund payments',
            'view expenses', 'create expenses', 'edit expenses',
            'view petty cash', 'create petty cash', 'edit petty cash',
            'view reports', 'export reports',
            'view inventory',
        ]);

        // =========================================================================
        // CREATE SAMPLE USERS WITH BRANCH ASSIGNMENTS
        // =========================================================================

        // Create branches first
        $branch1 = Branch::firstOrCreate([
            'name' => 'Main Branch',
            'code' => 'MAIN',
            'city' => 'Nairobi',
            'is_main_branch' => true,
        ]);

        $branch2 = Branch::firstOrCreate([
            'name' => 'Westlands Branch',
            'code' => 'WEST',
            'city' => 'Nairobi',
        ]);

        // Super Admin (global)
        $admin = User::firstOrCreate([
            'email' => 'admin@laundry.com',
        ], [
            'name' => 'Super Administrator',
            'password' => bcrypt('password'),
            'branch_id' => $branch1->id,
            'is_active' => true,
        ]);

        // Assign global role (branch_id = null)
        $admin->assignBranchRole('Super Admin', null);

        // Branch Manager for Main Branch
        $manager1 = User::firstOrCreate([
            'email' => 'manager.main@laundry.com',
        ], [
            'name' => 'Main Branch Manager',
            'password' => bcrypt('password'),
            'branch_id' => $branch1->id,
            'is_active' => true,
        ]);
        $manager1->assignBranchRole('Branch Manager', $branch1);

        // Branch Manager for Westlands
        $manager2 = User::firstOrCreate([
            'email' => 'manager.west@laundry.com',
        ], [
            'name' => 'Westlands Branch Manager',
            'password' => bcrypt('password'),
            'branch_id' => $branch2->id,
            'is_active' => true,
        ]);
        $manager2->assignBranchRole('Branch Manager', $branch2);

        // Cashier for Main Branch
        $cashier1 = User::firstOrCreate([
            'email' => 'cashier.main@laundry.com',
        ], [
            'name' => 'Main Branch Cashier',
            'password' => bcrypt('password'),
            'branch_id' => $branch1->id,
            'is_active' => true,
        ]);
        $cashier1->assignBranchRole('Cashier', $branch1);

        // Laundry Staff for both branches (user can work in multiple branches)
        $staff1 = User::firstOrCreate([
            'email' => 'staff@laundry.com',
        ], [
            'name' => 'Multi-Branch Staff',
            'password' => bcrypt('password'),
            'branch_id' => $branch1->id,
            'is_active' => true,
        ]);
        $staff1->assignBranchRole('Laundry Staff', $branch1);
        $staff1->assignBranchRole('Laundry Staff', $branch2); // Same role in both branches

        // Accountant (works globally but not Super Admin)
        $accountant1 = User::firstOrCreate([
            'email' => 'accountant@laundry.com',
        ], [
            'name' => 'Company Accountant',
            'password' => bcrypt('password'),
            'branch_id' => $branch1->id,
            'is_active' => true,
        ]);
        $accountant1->assignBranchRole('Accountant', null);
    }
}
