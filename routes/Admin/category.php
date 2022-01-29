<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;



Route::prefix('pulsa')->group(function () {
    Route::post('/create', [CategoryController::class, 'createPulsa']);
    Route::put('/update/{pulsaId}', [CategoryController::class, 'updatePulsa']);
    Route::get('/all', [CategoryController::class, 'getAllPulsa']);
    Route::delete('/delete/{pulsaId}', [CategoryController::class, 'deletePulsa']);
});
Route::prefix('vocher')->group(function () {
    Route::post('/create', [CategoryController::class, 'createVocher']);
    Route::put('/update/{vocherId}', [CategoryController::class, 'updateVocher']);
    Route::get('/all', [CategoryController::class, 'getAllVocher']);
    Route::delete('/delete/{vocherId}', [CategoryController::class, 'deleteVocher']);
});
