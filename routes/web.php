<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    OrderController,
    CustomerController,
    PaymentController,
    ServiceController,
    ServiceItemController,
    BranchController,
    UserController,
    RoleController,
    PermissionController,
    InventoryItemController,
    InventoryStockController,
    ExpenseController,
    ExpenseCategoryController,
    ReportController,
    SettingController,
    ProfileController
};

// Authentication Routes (Laravel Breeze/Jetstream)
require __DIR__ . '/auth.php';

// Routes that require authentication
Route::middleware(['auth', 'verified'])->group(function () {

    // ============================================
    // DASHBOARD
    // ============================================
    // Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================
    // USER MANAGEMENT (Admin Only)
    // ============================================
    Route::middleware(['role:super-admin|admin'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // Assign roles to user
        Route::post('/{user}/roles', [UserController::class, 'assignRoles'])->name('assign-roles');
        Route::delete('/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('remove-role');
    });

    // ============================================
    // ROLES & PERMISSIONS (Super Admin Only)
    // ============================================
    // Route::middleware(['role:super-admin'])->prefix('roles')->name('roles.')->group(function () {
    //     Route::get('/', [RoleController::class, 'index'])->name('index');
    //     Route::get('/create', [RoleController::class, 'create'])->name('create');
    //     Route::post('/', [RoleController::class, 'store'])->name('store');
    //     Route::get('/{role}', [RoleController::class, 'show'])->name('show');
    //     Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
    //     Route::put('/{role}', [RoleController::class, 'update'])->name('update');
    //     Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');

    //     // Assign permissions to role
    //     Route::post('/{role}/permissions', [RoleController::class, 'assignPermissions'])->name('assign-permissions');
    // });

    // Route::middleware(['role:super-admin'])->prefix('permissions')->name('permissions.')->group(function () {
    //     Route::get('/', [PermissionController::class, 'index'])->name('index');
    //     Route::get('/create', [PermissionController::class, 'create'])->name('create');
    //     Route::post('/', [PermissionController::class, 'store'])->name('store');
    //     Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
    //     Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
    //     Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
    // });

    // ============================================
    // BRANCH MANAGEMENT
    // ============================================
    Route::middleware(['role:super-admin|admin'])->prefix('branches')->name('branches.')->group(function () {
        Route::get('/', [BranchController::class, 'index'])->name('index');
        Route::get('/create', [BranchController::class, 'create'])->name('create');
        Route::post('/', [BranchController::class, 'store'])->name('store');
        Route::get('/{branch}', [BranchController::class, 'show'])->name('show');
        Route::get('/{branch}/edit', [BranchController::class, 'edit'])->name('edit');
        Route::put('/{branch}', [BranchController::class, 'update'])->name('update');
        Route::delete('/{branch}', [BranchController::class, 'destroy'])->name('destroy');
    });

    // ============================================
    // CUSTOMERS
    // ============================================
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    // ============================================
    // ORDERS
    // ============================================
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');

        // Order actions
        Route::post('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{order}/assign', [OrderController::class, 'assign'])->name('assign');
        Route::get('/{order}/receipt', [OrderController::class, 'receipt'])->name('receipt');
        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
    });

    // ============================================
    // SERVICES
    // ============================================
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::get('/{service}', [ServiceController::class, 'show'])->name('show');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
    });

    // Service Items
    Route::prefix('service-items')->name('service-items.')->group(function () {
        Route::get('/', [ServiceItemController::class, 'index'])->name('index');
        Route::get('/create', [ServiceItemController::class, 'create'])->name('create');
        Route::post('/', [ServiceItemController::class, 'store'])->name('store');
        Route::get('/{serviceItem}', [ServiceItemController::class, 'show'])->name('show');
        Route::get('/{serviceItem}/edit', [ServiceItemController::class, 'edit'])->name('edit');
        Route::put('/{serviceItem}', [ServiceItemController::class, 'update'])->name('update');
        Route::delete('/{serviceItem}', [ServiceItemController::class, 'destroy'])->name('destroy');
    });

    // ============================================
    // PAYMENTS
    // ============================================
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
    });

    // ============================================
    // INVENTORY
    // ============================================
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryItemController::class, 'index'])->name('index');
        Route::get('/create', [InventoryItemController::class, 'create'])->name('create');
        Route::post('/', [InventoryItemController::class, 'store'])->name('store');
        Route::get('/{item}', [InventoryItemController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [InventoryItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [InventoryItemController::class, 'update'])->name('update');
        Route::delete('/{item}', [InventoryItemController::class, 'destroy'])->name('destroy');

        // Stock management
        Route::post('/{item}/adjust-stock', [InventoryStockController::class, 'adjust'])->name('adjust-stock');
        Route::get('/{item}/movements', [InventoryStockController::class, 'movements'])->name('movements');
    });

    // ============================================
    // EXPENSES
    // ============================================
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::get('/create', [ExpenseController::class, 'create'])->name('create');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show');
        Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
        Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
        Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
    });

    // Expense Categories
    Route::prefix('expense-categories')->name('expense-categories.')->group(function () {
        Route::get('/', [ExpenseCategoryController::class, 'index'])->name('index');
        Route::post('/', [ExpenseCategoryController::class, 'store'])->name('store');
        Route::put('/{category}', [ExpenseCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [ExpenseCategoryController::class, 'destroy'])->name('destroy');
    });

    // ============================================
    // REPORTS
    // ============================================
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    //     Route::get('/orders', [ReportController::class, 'orders'])->name('orders');
    //     Route::get('/payments', [ReportController::class, 'payments'])->name('payments');
    //     Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
    //     Route::get('/expenses', [ReportController::class, 'expenses'])->name('expenses');
    // });

    // ============================================
    // SETTINGS
    // ============================================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });

    // ============================================
    // USER PROFILE
    // ============================================
    // Route::prefix('profile')->name('profile.')->group(function () {
    //     Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    //     Route::patch('/', [ProfileController::class, 'update'])->name('update');
    //     Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    // });
});
