<?php

use App\Http\Controllers\Admin\AdminWebController;
use App\Http\Controllers\Admin\AdminWarehouseController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.admin:admin_web,super_admin'])->group(function () {
    // Admin Web Dashboard
    Route::get('/admin/web', [AdminWebController::class, 'dashboard'])->name('admin.web.dashboard');

    // Products Management
    Route::get('/admin/web/products', [AdminWebController::class, 'products'])->name('admin.web.products');
    Route::get('/admin/web/products/create', [AdminWebController::class, 'createProduct'])->name('admin.web.products.create');
    Route::post('/admin/web/products', [AdminWebController::class, 'storeProduct'])->name('admin.web.products.store');
    Route::get('/admin/web/products/{product}/edit', [AdminWebController::class, 'editProduct'])->name('admin.web.products.edit');
    Route::patch('/admin/web/products/{product}', [AdminWebController::class, 'updateProduct'])->name('admin.web.products.update');

    // Users Management
    Route::get('/admin/web/users', [AdminWebController::class, 'users'])->name('admin.web.users');

    // Transactions Management
    Route::get('/admin/web/transactions', [AdminWebController::class, 'transactions'])->name('admin.web.transactions');
    Route::get('/admin/web/transactions/{transaction}', [AdminWebController::class, 'showTransaction'])->name('admin.web.transactions.show');
    Route::patch('/admin/web/transactions/{transaction}/status', [AdminWebController::class, 'updateTransactionStatus'])->name('admin.web.transactions.update-status');

    // Chat Management
    Route::get('/admin/web/chat', [AdminChatController::class, 'index'])->name('admin.web.chat.index');
    Route::get('/admin/web/chat/{user}', [AdminChatController::class, 'show'])->name('admin.web.chat.show');
    Route::post('/admin/web/chat/{user}', [AdminChatController::class, 'store'])->name('admin.web.chat.store');
});

Route::middleware(['auth', 'check.admin:admin_warehouse,super_admin'])->group(function () {
    // Admin Warehouse Dashboard
    Route::get('/admin/warehouse', [AdminWarehouseController::class, 'dashboard'])->name('admin.warehouse.dashboard');

    // Stock Management
    Route::get('/admin/warehouse/stocks', [AdminWarehouseController::class, 'stocks'])->name('admin.warehouse.stocks');
    Route::get('/admin/warehouse/stocks/{product}/logs', [AdminWarehouseController::class, 'productStockLogs'])->name('admin.warehouse.stocks.logs');
    Route::patch('/admin/warehouse/stocks/{product}', [AdminWarehouseController::class, 'updateStock'])->name('admin.warehouse.stocks.update');

    // Stock Logs
    Route::get('/admin/warehouse/logs', [AdminWarehouseController::class, 'allStockLogs'])->name('admin.warehouse.logs');
});

Route::middleware(['auth', 'check.admin:super_admin'])->group(function () {
    Route::get('/admin/super/dashboard', [SuperAdminController::class, 'dashboard'])->name('admin.super.dashboard');
    Route::get('/admin/super/reports', [SuperAdminController::class, 'reports'])->name('admin.super.reports');
});
