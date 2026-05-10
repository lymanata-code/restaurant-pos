<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Branches\BranchSwitchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LocaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
| Mounted at "/admin" with the "admin." name prefix and the "web" middleware
| group (see bootstrap/app.php).
|
| Each module's routes live in routes/admin/<module>.php and are required
| from this file inside the auth-protected group below.
*/

// --- Public auth routes ---
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
});

// --- Authenticated admin area ---
Route::middleware(['admin.auth'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('locale', [LocaleController::class, 'update'])->name('locale.update');
    Route::post('branches/switch', BranchSwitchController::class)->name('branches.switch');
    Route::get('/', DashboardController::class)->name('home');
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Per-module routes — keep one file per feature for separation.
    foreach ([
        'pos',
        'branches',
        'tax-rates',
        'payment-methods',
        'printers',
        'code-sequences',
        'roles',
        'permissions',
        'users',
        'staff',
        'login-histories',
        'menu-categories',
        'menu-items',
        'modifier-groups',
        'modifiers',
        'zones',
        'dining-tables',
        'customers',
        'kitchen-stations',
        'kitchen-tickets',
        'orders',
        'promotions',
        'coupons',
        'inventory-categories',
        'units',
        'stock-items',
        'stock-movements',
        'stock-adjustments',
        'suppliers',
        'purchase-orders',
        'goods-receives',
        'accounts',
        'expenses',
        'journal-entries',
        'audit-logs',
        'notifications',
    ] as $module) {
        $file = __DIR__ . "/admin/{$module}.php";
        if (file_exists($file)) {
            require $file;
        }
    }
});
