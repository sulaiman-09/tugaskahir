<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;

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
    
    // Customer - Admin dan Sales bisa akses
    Route::get('/customer', function () {
        return view('pages.customer');
    })->middleware('role:admin,sales')->name('customer');
    
    // Sudirman Park - Admin dan sudirman park bisa akses
    Route::get('/sudirman-park', function () {
        return view('pages.sudirman-park');
    })->middleware('role:admin,sudirman park')->name('sudirman-park');
    
    // Menu lainnya hanya untuk admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/product', function () {
            return view('pages.product');
        })->name('product');
        
        Route::get('/banner', function () {
            return view('pages.banner');
        })->name('banner');
        
        Route::get('/division', function () {
            return view('pages.division');
        })->name('division');
        
        Route::get('/career', function () {
            return view('pages.career');
        })->name('career');
        
        Route::get('/news', function () {
            return view('pages.news');
        })->name('news');
        
        Route::get('/settings-content', function () {
            return view('pages.settings-content');
        })->name('settings-content');
        
        Route::get('/user-management', function () {
            return view('pages.user-management');
        })->name('user-management');
    });

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
});
