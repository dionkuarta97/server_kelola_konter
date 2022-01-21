<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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


Route::post('/register', [UserController::class, 'register']);
Route::patch('/change_password/{userId}', [UserController::class, 'changePassword']);
Route::patch('/change_nama/{userId}', [UserController::class, 'changeNama']);
Route::patch('/change_status/{userId}', [UserController::class, 'changeStatus']);
Route::get('/all', [UserController::class, 'getAllKaryawan']);
