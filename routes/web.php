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
        });


    Route::middleware('role:admin,sudirmanpark')->group(function () {
        Route::get('/product', [ProductController::class, 'index'])->name('product.index');
        Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    });


    Route::resource('/banner', BannerController::class);

    Route::middleware(['auth'])->group(function () {
        Route::resource('division', App\Http\Controllers\DivisionController::class);
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/career', [CareerController::class, 'index'])->name('career.index');
    });

    Route::middleware(['auth'])->group(function () {
        Route::resource('news', NewsController::class);
    });

    Route::resource('settings-content', SettingsContentController::class);
    Route::post('settings-content/{id}/toggle', [SettingsContentController::class, 'toggleStatus'])
        ->name('settings-content.toggle');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // <— Tambahkan ini    

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
});
