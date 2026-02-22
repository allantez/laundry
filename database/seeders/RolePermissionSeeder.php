<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Branch;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS
        |--------------------------------------------------------------------------
        */

        $permissions = [
            'view branches','create branches','edit branches','delete branches',
            'view users','create users','edit users','delete users',
            'assign roles','revoke roles',
            'view roles','create roles','edit roles','delete roles',
            'view customers','create customers','edit customers','delete customers',
            'view services','create services','edit services','delete services',
            'view orders','create orders','edit orders','delete orders',
            'process orders','complete orders','cancel orders','assign orders',
            'view payments','create payments','edit payments','delete payments','refund payments',
            'view inventory','create inventory','edit inventory','delete inventory',
            'adjust stock','view stock movements',
            'view expenses','create expenses','edit expenses','delete expenses',
            'view petty cash','create petty cash','edit petty cash','delete petty cash',
            'disburse petty cash','replenish petty cash',
            'view reports','export reports','view analytics',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        $branchManager = Role::firstOrCreate(['name' => 'Branch Manager']);
        $cashier = Role::firstOrCreate(['name' => 'Cashier']);
        $laundryStaff = Role::firstOrCreate(['name' => 'Laundry Staff']);
        $driver = Role::firstOrCreate(['name' => 'Driver']);
        $accountant = Role::firstOrCreate(['name' => 'Accountant']);

        /*
        |--------------------------------------------------------------------------
        | BRANCHES
        |--------------------------------------------------------------------------
        */

        $main = $this->createBranch('Main Branch', 'MAIN', 'Nairobi', true);
        $west = $this->createBranch('Westlands Branch', 'WEST', 'Nairobi');
        $karen = $this->createBranch('Karen Branch', 'KAREN', 'Nairobi');
        $mombasa = $this->createBranch('Mombasa Branch', 'MSA', 'Mombasa');

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        // Super Admin
        $admin = $this->createUser(
            'Super Administrator',
            'admin@laundry.com',
            $main->id,
            'Chief Executive Officer',
            true
        );
        $admin->assignBranchRole('Super Admin', null);

        // Branch Managers
        $managerMain = $this->createUser(
            'Main Branch Manager',
            'manager.main@laundry.com',
            $main->id,
            'Branch Manager'
        );
        $managerMain->assignBranchRole('Branch Manager', $main);

        $managerWest = $this->createUser(
            'Westlands Manager',
            'manager.west@laundry.com',
            $west->id,
            'Branch Manager'
        );
        $managerWest->assignBranchRole('Branch Manager', $west);

        // Cashiers
        for ($i = 1; $i <= 2; $i++) {
            $cashierUser = $this->createUser(
                "Main Cashier $i",
                "cashier{$i}@laundry.com",
                $main->id,
                'Cashier'
            );
            $cashierUser->assignBranchRole('Cashier', $main);
        }

        // Laundry Staff
        for ($i = 1; $i <= 3; $i++) {
            $staff = $this->createUser(
                "Laundry Staff $i",
                "staff{$i}@laundry.com",
                $main->id,
                'Laundry Technician'
            );
            $staff->assignBranchRole('Laundry Staff', $main);
        }

        // Driver
        $driverUser = $this->createUser(
            'Delivery Driver',
            'driver@laundry.com',
            $west->id,
            'Delivery Driver'
        );
        $driverUser->assignBranchRole('Driver', $west);

        // Accountant
        $accountantUser = $this->createUser(
            'Company Accountant',
            'accountant@laundry.com',
            $main->id,
            'Accountant'
        );
        $accountantUser->assignBranchRole('Accountant', null);

        // Inactive user example
        $inactiveUser = $this->createUser(
            'Former Employee',
            'inactive@laundry.com',
            $main->id,
            'Former Staff',
            false
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    private function createBranch($name, $code, $city, $isMain = false)
    {
        return Branch::firstOrCreate(
            ['code' => $code],
            [
                'uuid' => Str::uuid(),
                'name' => $name,
                'city' => $city,
                'is_main_branch' => $isMain,
            ]
        );
    }

    private function createUser($name, $email, $branchId, $jobTitle, $active = true)
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'uuid' => Str::uuid(),
                'name' => $name,
                'password' => Hash::make('password'),
                'branch_id' => $branchId,
                'is_active' => $active,
                'phone' => '07' . rand(10000000, 99999999),
                'profile_photo' => null,
                'job_title' => $jobTitle,
                'bio' => "$jobTitle at LaundryPro System.",
                'hired_at' => now()->subMonths(rand(1, 36)),
                'last_login_at' => now()->subDays(rand(0, 30)),
                'last_login_ip' => '192.168.1.' . rand(2, 200),
                'login_count' => rand(5, 250),
                'preferences' => [
                    'theme' => rand(0,1) ? 'dark' : 'light',
                    'notifications' => true,
                    'dashboard_layout' => 'default',
                ],
            ]
        );
    }
}
