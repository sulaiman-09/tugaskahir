<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\CareerController as ApiCareerController;
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
