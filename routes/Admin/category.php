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

Route::prefix('bri')->group(function () {
    Route::post('/create', [CategoryController::class, 'createBri']);
    Route::put('/update/{briId}', [CategoryController::class, 'updateBri']);
    Route::get('/all', [CategoryController::class, 'getAllBri']);
    Route::delete('/delete/{briId}', [CategoryController::class, 'deleteBri']);
});

Route::prefix('topup')->group(function () {
    Route::post('/create', [CategoryController::class, 'createTopup']);
    Route::put('/update/{topupId}', [CategoryController::class, 'updateTopup']);
    Route::get('/all', [CategoryController::class, 'getAllTopup']);
    Route::delete('/delete/{topupId}', [CategoryController::class, 'deleteTopup']);
});

Route::prefix('chip')->group(function () {
    Route::post('/create', [CategoryController::class, 'createChip']);
    Route::put('/update/{chipId}', [CategoryController::class, 'updateChip']);
    Route::get('/all', [CategoryController::class, 'getAllChip']);
    Route::delete('/delete/{chipId}', [CategoryController::class, 'deleteChip']);
});
