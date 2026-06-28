<?php

use App\Http\Controllers\Admin\AdminWebController;
use App\Http\Controllers\Admin\AdminWarehouseController;
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

    // FAQs Management
    Route::get('/admin/web/faqs', [\App\Http\Controllers\FaqController::class, 'index'])->name('admin.web.faqs.index');
    Route::post('/admin/web/faqs', [\App\Http\Controllers\FaqController::class, 'store'])->name('admin.web.faqs.store');
    Route::patch('/admin/web/faqs/{faq}', [\App\Http\Controllers\FaqController::class, 'update'])->name('admin.web.faqs.update');
    Route::delete('/admin/web/faqs/{faq}', [\App\Http\Controllers\FaqController::class, 'destroy'])->name('admin.web.faqs.destroy');

    // Admin Custom Orders
    Route::get('/admin/web/custom-orders/{custom_order}/download', [\App\Http\Controllers\Admin\AdminCustomOrderController::class, 'downloadDesign'])->name('admin.web.custom-orders.download');
    Route::resource('/admin/web/custom-orders', \App\Http\Controllers\Admin\AdminCustomOrderController::class)
        ->names('admin.web.custom-orders')
        ->only(['index', 'show', 'update']);
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

    // Warehouse Custom Orders
    Route::resource('/admin/warehouse/custom-orders', \App\Http\Controllers\Admin\WarehouseCustomOrderController::class)
        ->names('admin.warehouse.custom-orders')
        ->only(['index', 'show']);
});
