<?php

use Illuminate\Support\Facades\Route;



Route::prefix('konter')->group(function () {
    require(__DIR__ . '/konter.php');
});

Route::prefix('user')->group(function () {
    require(__DIR__ . '/user.php');
});
