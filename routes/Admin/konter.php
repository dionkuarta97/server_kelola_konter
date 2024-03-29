<?php

use App\Http\Controllers\KonterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/create', [KonterController::class, 'createKonter']);
Route::get('/all', [KonterController::class, 'getAllKonter']);
Route::put('/update/{konterId}', [KonterController::class, 'updateKonter']);
