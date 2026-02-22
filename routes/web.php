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

// Authentication Routes
require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    Route::get('/', function () {
        return redirect()->route('login');
    });

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    // Route::get('/dashboard', [DashboardController::class, 'index'])
    //     ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT (Super Admin & Admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')
        ->name('users.')
        ->middleware(['role:super-admin|admin'])
        ->group(function () {

            Route::get('/', [UserController::class, 'index'])
                ->middleware('permission:view users')
                ->name('index');

            Route::get('/create', [UserController::class, 'create'])
                ->middleware('permission:create users')
                ->name('create');

            Route::post('/', [UserController::class, 'store'])
                ->middleware('permission:create users')
                ->name('store');

            Route::get('/{user}', [UserController::class, 'show'])
                ->middleware('permission:view users')
                ->name('show');

            Route::get('/{user}/edit', [UserController::class, 'edit'])
                ->middleware('permission:edit users')
                ->name('edit');

            Route::put('/{user}', [UserController::class, 'update'])
                ->middleware('permission:edit users')
                ->name('update');

            Route::delete('/{user}', [UserController::class, 'destroy'])
                ->middleware('permission:delete users')
                ->name('destroy');

            Route::post('/{user}/roles', [UserController::class, 'assignRoles'])
                ->middleware('permission:assign roles')
                ->name('assign-roles');

            Route::delete('/{user}/roles/{role}', [UserController::class, 'removeRole'])
                ->middleware('permission:assign roles')
                ->name('remove-role');
        });

    /*
    |--------------------------------------------------------------------------
    | BRANCH MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::prefix('branches')
        ->name('branches.')
        ->middleware(['auth'])
        ->group(function () {

            Route::get('/', [BranchController::class, 'index'])
                ->middleware('permission:view branches')
                ->name('index');

            Route::get('/create', [BranchController::class, 'create'])
                ->middleware('permission:create branches')
                ->name('create');

            Route::post('/', [BranchController::class, 'store'])
                ->middleware('permission:create branches')
                ->name('store');

            Route::get('/{branch}', [BranchController::class, 'show'])
                ->middleware('permission:view branches')
                ->name('show');

            Route::get('/{branch}/edit', [BranchController::class, 'edit'])
                ->middleware('permission:edit branches')
                ->name('edit');

            Route::put('/{branch}', [BranchController::class, 'update'])
                ->middleware('permission:edit branches')
                ->name('update');

            Route::delete('/{branch}', [BranchController::class, 'destroy'])
                ->middleware('permission:delete branches')
                ->name('destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | CUSTOMERS
    |--------------------------------------------------------------------------
    */
    Route::resource('customers', CustomerController::class)
        ->middleware('permission:view customers');

    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */
    Route::prefix('orders')
        ->name('orders.')
        ->middleware('permission:view orders')
        ->group(function () {

            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/create', [OrderController::class, 'create'])
                ->middleware('permission:create orders')
                ->name('create');

            Route::post('/', [OrderController::class, 'store'])
                ->middleware('permission:create orders')
                ->name('store');

            Route::get('/{order}', [OrderController::class, 'show'])->name('show');

            Route::get('/{order}/edit', [OrderController::class, 'edit'])
                ->middleware('permission:edit orders')
                ->name('edit');

            Route::put('/{order}', [OrderController::class, 'update'])
                ->middleware('permission:edit orders')
                ->name('update');

            Route::delete('/{order}', [OrderController::class, 'destroy'])
                ->middleware('permission:delete orders')
                ->name('destroy');

            Route::post('/{order}/status', [OrderController::class, 'updateStatus'])
                ->middleware('permission:update order status')
                ->name('update-status');

            Route::post('/{order}/assign', [OrderController::class, 'assign'])
                ->middleware('permission:assign orders')
                ->name('assign');

            Route::get('/{order}/receipt', [OrderController::class, 'receipt'])
                ->name('receipt');

            Route::get('/{order}/invoice', [OrderController::class, 'invoice'])
                ->name('invoice');
        });

    /*
    |--------------------------------------------------------------------------
    | SERVICES
    |--------------------------------------------------------------------------
    */
    Route::resource('services', ServiceController::class);

    Route::resource('service-items', ServiceItemController::class);

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS
    |--------------------------------------------------------------------------
    */
    Route::resource('payments', PaymentController::class)
        ->only(['index', 'create', 'store', 'show']);

    /*
    |--------------------------------------------------------------------------
    | INVENTORY
    |--------------------------------------------------------------------------
    */
    Route::prefix('inventory')
        ->name('inventory.')
        ->group(function () {

            Route::resource('/', InventoryItemController::class);

            Route::post('/{item}/adjust-stock', [InventoryStockController::class, 'adjust'])
                ->middleware('permission:adjust inventory')
                ->name('adjust-stock');

            Route::get('/{item}/movements', [InventoryStockController::class, 'movements'])
                ->name('movements');
        });

    /*
    |--------------------------------------------------------------------------
    | EXPENSES
    |--------------------------------------------------------------------------
    */
    Route::resource('expenses', ExpenseController::class);

    Route::resource('expense-categories', ExpenseCategoryController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | SETTINGS
    |--------------------------------------------------------------------------
    */
    Route::prefix('settings')
        ->name('settings.')
        ->middleware('role:super-admin|admin')
        ->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
        });
});
