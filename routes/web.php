<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SudirmanParkController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SettingsContentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StatsController;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Protected routes
Route::middleware('auth')->group(function () {


    // PRODUCT
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::patch('/product/{id}/toggle-price', [ProductController::class, 'togglePrice'])->name('product.togglePrice');
    // CATEGORY
    Route::get('/product/category/create', [ProductController::class, 'createCategory'])->name('product.category.create');
    Route::post('/product/category/store', [ProductController::class, 'storeCategory'])->name('product.category.store');
    Route::get('/product/category/{id}/edit', [ProductController::class, 'editCategory'])->name('product.category.edit');
    Route::put('/product/category/{id}', [ProductController::class, 'updateCategory'])->name('product.category.update');
    Route::delete('/product/category/{id}', [ProductController::class, 'destroyCategory'])->name('product.category.destroy');
    // Product Categories
    Route::get('/product/category/export', [App\Http\Controllers\ProductController::class, 'exportCategory'])->name('product.category.export');
    // Product List
    Route::get('/product/export', [App\Http\Controllers\ProductController::class, 'export'])->name('product.export');

    // Dashboard - Admin dan Report bisa akses
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,report')
        ->name('dashboard');

    // ================================
    // Customer Management (Controller)
    // ================================
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::resource('customer', CustomerController::class);
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/export/excel', [CustomerController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [CustomerController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');

        // Bulk delete customer (perbaiki)
        Route::post('/bulk-delete', [CustomerController::class, 'bulkDelete'])
            ->name('bulkDelete'); // tidak perlu customer. lagi
    });

    Route::prefix('sudirmanpark')
        ->name('sudirmanpark.')
        ->group(function () {
            // 🏢 Main CRUD Sudirman Park
            Route::get('/', [SudirmanParkController::class, 'index'])->name('index');
            Route::get('/create', [SudirmanParkController::class, 'create'])->name('create');
            Route::post('/store', [SudirmanParkController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [SudirmanParkController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SudirmanParkController::class, 'update'])->name('update');
            Route::delete('/{id}', [SudirmanParkController::class, 'destroy'])->name('destroy');

            // 🗑️ Bulk Delete (Customer)
            Route::post('/bulk-delete', [SudirmanParkController::class, 'bulkDelete'])->name('bulkDelete');

            // 🗑️ Bulk Delete Homepass (Alamat Tower)
            Route::delete('/homepass/bulk-delete', [SudirmanParkController::class, 'bulkDeleteHomepass'])->name('bulkDeleteHomepass');

            // ⚙️ Update Status Customer
            Route::patch('/{id}/status', [SudirmanParkController::class, 'updateStatus'])->name('updateStatus');

            // 🧾 KTP Operations
            Route::delete('/{id}/ktp', [SudirmanParkController::class, 'removeKtp'])->name('removeKtp');
            Route::get('/{id}/ktp/download', [SudirmanParkController::class, 'downloadKtp'])->name('downloadKtp');
            Route::get('/{id}/ktp/preview', [SudirmanParkController::class, 'previewKtp'])->name('previewKtp');

            // 🏠 Homepass (Alamat Tower)
            Route::get('/alamat', [SudirmanParkController::class, 'alamat'])->name('alamat');
            Route::get('/homepass/create', [SudirmanParkController::class, 'createHomepass'])->name('createHomepass');
            Route::post('/homepass', [SudirmanParkController::class, 'storeHomepass'])->name('storeHomepass');
            Route::get('/homepass/{id}/edit', [SudirmanParkController::class, 'editHomepass'])->name('editHomepass');
            Route::put('/homepass/{id}', [SudirmanParkController::class, 'updateHomepass'])->name('updateHomepass');
            Route::delete('/homepass/{id}', [SudirmanParkController::class, 'destroyHomepass'])->name('destroyHomepass');

            // 📤 Export Homepass (Excel & PDF)
            Route::get('/homepass/export/excel', [SudirmanParkController::class, 'exportHomepassExcel'])
                ->name('exportHomepassExcel');
            Route::get('/homepass/export/pdf', [SudirmanParkController::class, 'exportHomepassPdf'])
                ->name('exportHomepassPdf');

            // 📤 Export Customer (optional)
            Route::get('/export/pdf', [SudirmanParkController::class, 'exportPdf'])->name('exportPdf');
            Route::get('/export/excel', [SudirmanParkController::class, 'exportExcel'])->name('exportExcel');
        });

    Route::middleware('role:admin,sudirmanpark')->group(function () {
        // ===== Product Routes =====
        Route::get('/product', [ProductController::class, 'index'])->name('product.index');
        Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

        // Bulk Delete Product
        Route::post('/product/bulk-delete', [ProductController::class, 'bulkDelete'])
            ->name('product.bulkDelete');

        // ===== Product Category Routes =====
        Route::get('/product/category/create', [ProductController::class, 'createCategory'])->name('product.category.create');
        Route::post('/product/category', [ProductController::class, 'storeCategory'])->name('product.category.store');
        Route::get('/product/category/{id}/edit', [ProductController::class, 'editCategory'])->name('product.category.edit');
        Route::put('/product/category/{id}', [ProductController::class, 'updateCategory'])->name('product.category.update');
        Route::delete('/product/category/{id}', [ProductController::class, 'destroyCategory'])->name('product.category.destroy');

        // Bulk Delete Category
        Route::post('/product/category/bulk-delete', [ProductController::class, 'bulkDeleteCategory'])
            ->name('product.category.bulkDelete');

        // ========== EXPORT PRODUCT ==========
        Route::get('/product/export/excel', [ProductController::class, 'exportExcel'])->name('product.export.excel');
        Route::get('/product/export/pdf', [ProductController::class, 'exportPdf'])->name('product.export.pdf');

        // ========== EXPORT CATEGORY ==========
        Route::get('/product/category/export/excel', [ProductController::class, 'exportCategoryExcel'])->name('product.category.export.excel');
        Route::get('/product/category/export/pdf', [ProductController::class, 'exportCategoryPdf'])->name('product.category.export.pdf');

        // Toggle Show Price Category
        Route::put('/product/category/{id}/toggle-price', [ProductController::class, 'toggleCategoryPrice'])
            ->name('product.category.togglePrice');
    });


    Route::middleware(['auth'])->group(function () {
        Route::resource('banner', BannerController::class)->except(['show']);
        Route::get('/banner/export', [BannerController::class, 'export'])->name('banner.export');
        Route::get('/banner/export/excel', [BannerController::class, 'exportExcel'])->name('banner.export.excel');
        Route::get('/banner/export/pdf', [BannerController::class, 'exportPdf'])->name('banner.export.pdf');
        Route::patch('/banner/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banner.toggle-status');
        Route::post('/banner/bulk-delete', [BannerController::class, 'bulkDelete'])->name('banner.bulkDelete');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/division/export', [App\Http\Controllers\DivisionController::class, 'export'])->name('division.export');
        Route::get('/division/export/excel', [App\Http\Controllers\DivisionController::class, 'exportExcel'])->name('division.export.excel');
        Route::get('/division/export/pdf', [App\Http\Controllers\DivisionController::class, 'exportPdf'])->name('division.export.pdf');
        Route::patch('/division/{id}/status', [App\Http\Controllers\DivisionController::class, 'updateStatus'])->name('division.updateStatus');
        Route::resource('division', App\Http\Controllers\DivisionController::class);
        Route::post('/division/bulk-delete', [App\Http\Controllers\DivisionController::class, 'bulkDelete'])->name('division.bulkDelete');
    });


    Route::middleware(['auth'])->group(function () {
        Route::get('/career', [CareerController::class, 'index'])->name('career.index');
        Route::get('/career/{id}/edit', [CareerController::class, 'edit'])->name('career.edit');
        Route::put('/career/{id}', [CareerController::class, 'update'])->name('career.update');
        Route::delete('/career/{id}', [CareerController::class, 'destroy'])->name('career.destroy');
        Route::get('/career/export/excel', [CareerController::class, 'exportExcel'])->name('career.export.excel');
        Route::get('/career/export/pdf', [CareerController::class, 'exportPdf'])->name('career.export.pdf');
        Route::get('/career/create', [CareerController::class, 'create'])->name('career.create');
        Route::post('/career', [CareerController::class, 'store'])->name('career.store');
        Route::post('/career/bulk-delete', [CareerController::class, 'bulkDelete'])->name('career.bulkDelete');
    });

    Route::middleware(['auth'])->group(function () {
        Route::resource('news', NewsController::class);
        Route::get('news/export/pdf', [NewsController::class, 'exportPdf'])->name('news.export.pdf');
        Route::get('news/export/xlsx', [NewsController::class, 'exportXlsx'])->name('news.export.xlsx');
        Route::post('/news/bulk-delete', [App\Http\Controllers\NewsController::class, 'bulkDelete'])->name('news.bulkDelete');
    });

    Route::prefix('settings-content')->group(function () {
        Route::get('/', [SettingsContentController::class, 'index'])->name('settings-content.index');
        Route::get('/create', [SettingsContentController::class, 'create'])->name('settings-content.create');
        Route::post('/', [SettingsContentController::class, 'store'])->name('settings-content.store');
        Route::get('/{id}/edit', [SettingsContentController::class, 'edit'])->name('settings-content.edit');
        Route::put('/{id}', [SettingsContentController::class, 'update'])->name('settings-content.update');
        Route::delete('/{id}', [SettingsContentController::class, 'destroy'])->name('settings-content.destroy');

        // ✅ perbaikan: jangan ulang prefix
        Route::get('/export/excel', [SettingsContentController::class, 'exportExcel'])->name('settings-content.export.excel');
        Route::get('/export/pdf', [SettingsContentController::class, 'exportPdf'])->name('settings-content.export.pdf');

        Route::post('/bulk-delete', [SettingsContentController::class, 'bulkDelete'])->name('settings-content.bulkDelete');
    });

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // <— Tambahkan ini    
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulkDelete');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('roles/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulkDelete');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::post('/permissions/bulk-delete', [App\Http\Controllers\PermissionController::class, 'bulkDelete'])
        ->name('permissions.bulkDelete');

    // ===== Statistics Routes =====
    Route::get('/stats/customer-growth', [StatsController::class, 'customerGrowth'])->name('stats.customer-growth');
    Route::get('/stats/customer-growth-view', function() {
        return view('stats.customer-growth');
    })->name('stats.customer-growth-view');
});
