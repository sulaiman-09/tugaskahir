<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\CareerController as ApiCareerController;
use App\Http\Controllers\Api\CustomerLeadController;
use App\Http\Controllers\Api\BannerController as ApiBannerController;
use App\Http\Controllers\Api\ProductRegisterController as ApiProductRegisterController;
use App\Http\Controllers\Api\ProductBenefitController as ApiProductBenefitController;
use App\Http\Controllers\Api\AboutUsController as ApiAboutUsController;
use App\Http\Controllers\Api\ProductBannerController as ApiProductBannerController;
use App\Http\Controllers\Api\CompanyDescriptionController as ApiCompanyDescriptionController;
use App\Http\Controllers\Api\LocationController as ApiLocationController;
use App\Http\Controllers\Api\SudirmanParkApiController as ApiSudirmanParkApiController;
use App\Http\Controllers\Api\SudirmanCustomerController as ApiSudirmanCustomerController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Contoh route API untuk expose data hasil sync serta trigger sync manual.
*/

Route::get('/products', [ProductController::class, 'synced']);

Route::post('/hospitality/sync', function () {
    Artisan::call('hospitality:sync');

    return response()->json([
        'status' => 'ok',
        'message' => 'Sync triggered',
    ]);
});

Route::prefix('careers')->group(function () {
    Route::get('/', [ApiCareerController::class, 'index'])->name('api.careers.index');
    Route::post('/', [ApiCareerController::class, 'store'])->name('api.careers.store');
    Route::get('/{career}', [ApiCareerController::class, 'show'])->name('api.careers.show');
    Route::put('/{career}', [ApiCareerController::class, 'update'])->name('api.careers.update');
    Route::patch('/{career}', [ApiCareerController::class, 'update']);
    Route::delete('/{career}', [ApiCareerController::class, 'destroy'])->name('api.careers.destroy');
});

Route::prefix('v1/web')->group(function () {
    Route::post('/customer-leads', [CustomerLeadController::class, 'store'])
        ->name('api.customer-leads.store');
    Route::get('/banners', [ApiBannerController::class, 'index'])
        ->name('api.banners.index');
    Route::get('/products-register', [ApiProductRegisterController::class, 'index'])
        ->name('api.products-register.index');
    Route::get('/product-benefits', [ApiProductBenefitController::class, 'index'])
        ->name('api.product-benefits.index');
    Route::get('/about-us', [ApiAboutUsController::class, 'index'])
        ->name('api.about-us.index');
    Route::get('/product-banners', [ApiProductBannerController::class, 'index'])
        ->name('api.product-banners.index');
    Route::get('/company-description', [ApiCompanyDescriptionController::class, 'index'])
        ->name('api.company-description.index');
    Route::get('/provinces', [ApiLocationController::class, 'provinces'])
        ->name('api.provinces.index');
    Route::get('/provinces/{province}/city-districts', [ApiLocationController::class, 'cityDistricts'])
        ->name('api.provinces.city-districts');
    Route::get('/city-districts/{cityDistrict}/subdistricts', [ApiLocationController::class, 'subdistricts'])
        ->name('api.city-districts.subdistricts');
    Route::get('/subdistricts/{subdistrict}/villages', [ApiLocationController::class, 'villages'])
        ->name('api.subdistricts.villages');
    Route::get('/tower', [ApiSudirmanParkApiController::class, 'towers'])
        ->name('api.sudirman.tower');
    Route::get('/tower/floor', [ApiSudirmanParkApiController::class, 'floors'])
        ->name('api.sudirman.floor');
    Route::get('/tower/unit', [ApiSudirmanParkApiController::class, 'units'])
        ->name('api.sudirman.unit');
    Route::post('/sudirman-customer', [ApiSudirmanCustomerController::class, 'store'])
        ->name('api.sudirman-customer.store');
});
