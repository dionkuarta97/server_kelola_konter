<?php

use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;


Route::prefix('pulsa')->group(function () {
    Route::post('/create', [ProdukController::class, 'createProdukPulsa']);
    Route::put('/update/{produkPulsaId}', [ProdukController::class, 'updateProdukPulsa']);
    Route::get('/all', [ProdukController::class, 'getAllProdukPulsa']);
    Route::delete('/delete/{produkPulsaId}', [ProdukController::class, 'deleteProdukPulsa']);
});


Route::prefix('vocher')->group(function () {
    Route::post('/create', [ProdukController::class, 'createProdukVocher']);
    Route::put('/update/{produkVocherId}', [ProdukController::class, 'updateProdukVocher']);
    Route::get('/all', [ProdukController::class, 'getAllProdukVocher']);
    Route::delete('/delete/{produkVocherId}', [ProdukController::class, 'deleteProdukVocher']);
});
