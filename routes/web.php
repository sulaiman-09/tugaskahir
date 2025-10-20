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

    // Dashboard - Admin dan Report bisa akses
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,report')
        ->name('dashboard');

    // ================================
    // Customer Management (Controller)
    // ================================
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/export', [CustomerController::class, 'export'])->name('export');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('sudirmanpark')
        ->name('sudirmanpark.')
        ->middleware('role:admin,sudirman park')
        ->group(function () {
            Route::get('/', [SudirmanParkController::class, 'index'])->name('index');
            Route::get('/create', [SudirmanParkController::class, 'create'])->name('create');
            Route::post('/store', [SudirmanParkController::class, 'store'])->name('store');
            Route::get('/alamat', [SudirmanParkController::class, 'alamat'])->name('alamat');
            Route::get('/export', [SudirmanParkController::class, 'export'])->name('export');
            // Tambahan untuk edit/update/delete
            Route::get('/{id}/edit', [SudirmanParkController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SudirmanParkController::class, 'update'])->name('update');
            Route::delete('/{id}', [SudirmanParkController::class, 'destroy'])->name('destroy');
            // toggle/update status (AJAX)
            Route::patch('/{id}/status', [SudirmanParkController::class, 'updateStatus'])->name('updateStatus');
            Route::patch('/sudirmanpark/{id}/status', [SudirmanParkController::class, 'updateStatus']);
        });

    Route::middleware('role:admin,sudirmanpark')->group(function () {
        Route::get('/product', [ProductController::class, 'index'])->name('product.index');
        Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product/export', [ProductController::class, 'export'])->name('product.export');
        Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    });

    Route::resource('/banner', BannerController::class);
    // banner export
    Route::get('/banner/export', [BannerController::class, 'export'])->name('banner.export');

    Route::middleware(['auth'])->group(function () {
        Route::resource('division', App\Http\Controllers\DivisionController::class);

        // Route khusus update status
        Route::patch('/division/{id}/status', [App\Http\Controllers\DivisionController::class, 'updateStatus'])->name('division.updateStatus');
        // export divisions
        Route::get('/division/export', [App\Http\Controllers\DivisionController::class, 'export'])->name('division.export');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/career', [CareerController::class, 'index'])->name('career.index');
        Route::get('/career/{id}/edit', [CareerController::class, 'edit'])->name('career.edit');
        Route::put('/career/{id}', [CareerController::class, 'update'])->name('career.update');
        Route::delete('/career/{id}', [CareerController::class, 'destroy'])->name('career.destroy');
    });

    Route::middleware(['auth'])->group(function () {
        Route::resource('news', NewsController::class);
    });

    Route::prefix('settings-content')->group(function () {
        Route::get('/', [SettingsContentController::class, 'index'])->name('settings-content.index');
        Route::get('/{id}/edit', [SettingsContentController::class, 'edit'])->name('settings-content.edit');
        Route::put('/{id}', [SettingsContentController::class, 'update'])->name('settings-content.update');
    });

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // <— Tambahkan ini    
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});
