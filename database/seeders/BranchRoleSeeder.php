<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class BranchRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create permissions (standard Spatie)
        $permissions = [
            'view orders', 'create orders', 'update orders', 'delete orders',
            'view inventory', 'create inventory', 'update inventory', 'delete inventory',
            'view customers', 'create customers', 'update customers', 'delete customers',
            'view reports', 'export reports',
            'manage branches', 'manage users', 'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Create roles (standard Spatie)
        $superAdmin = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $branchManager = Role::create(['name' => 'Branch Manager', 'guard_name' => 'web']);
        $cashier = Role::create(['name' => 'Cashier', 'guard_name' => 'web']);
        $laundryStaff = Role::create(['name' => 'Laundry Staff', 'guard_name' => 'web']);
        $driver = Role::create(['name' => 'Driver', 'guard_name' => 'web']);

        // 3. Assign permissions to roles (standard Spatie)
        $branchManager->givePermissionTo([
            'view orders', 'create orders', 'update orders',
            'view inventory', 'create inventory', 'update inventory',
            'view customers', 'create customers', 'update customers',
            'view reports', 'export reports',
        ]);

        $cashier->givePermissionTo([
            'view orders', 'create orders', 'update orders',
            'view customers', 'create customers',
        ]);

        $laundryStaff->givePermissionTo([
            'view orders', 'update orders', // only status updates
            'view inventory',
        ]);

        $driver->givePermissionTo([
            'view orders', // only for delivery
        ]);

        $superAdmin->givePermissionTo(Permission::all());

        // 4. Create branches
        $branch1 = Branch::create(['name' => 'Downtown', 'code' => 'DT001', 'is_active' => true]);
        $branch2 = Branch::create(['name' => 'Uptown', 'code' => 'UT001', 'is_active' => true]);

        // 5. Create users and assign branch roles (YOUR CUSTOM LOGIC)
        $admin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@laundry.com',
            'password' => bcrypt('password'),
        ]);

        // Super Admin gets NULL branch_id (GLOBAL ACCESS)
        $admin->assignBranchRole('Super Admin', null);

        $manager1 = User::create([
            'name' => 'Downtown Manager',
            'email' => 'manager.dt@laundry.com',
            'password' => bcrypt('password'),
        ]);

        // Assign branch-specific role
        $manager1->assignBranchRole('Branch Manager', $branch1);

        $cashier1 = User::create([
            'name' => 'Downtown Cashier',
            'email' => 'cashier.dt@laundry.com',
            'password' => bcrypt('password'),
        ]);

        $cashier1->assignBranchRole('Cashier', $branch1);

        // User with multiple branch assignments
        $floatingStaff = User::create([
            'name' => 'Floating Staff',
            'email' => 'floating@laundry.com',
            'password' => bcrypt('password'),
        ]);

        $floatingStaff->assignBranchRole('Laundry Staff', $branch1);
        $floatingStaff->assignBranchRole('Laundry Staff', $branch2);
    }
}
