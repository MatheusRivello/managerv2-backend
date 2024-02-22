<?php

use App\Http\Controllers\Api\v1\Config\LogController;
use Illuminate\Support\Facades\Route;

    Route::get('logsmobile', [LogController::class, 'indexMobile'])->name('logsmobile');
    Route::get('logsmobile/filtrar/{id}', [LogController::class, 'showLogsMobile']);
    Route::post('logsmobile', [LogController::class, 'storeMobile']);