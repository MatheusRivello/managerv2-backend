<?php

use App\Http\Controllers\Api\v1\Config\LogController;
use Illuminate\Support\Facades\Route;

Route::get('logssincronismo', [LogController::class, 'indexSincronismo'])->name('logssincronismo');
Route::get('logssincronismo/filtrar/{id}', [LogController::class, 'showLogsSincronismo']);
Route::post('logssincronismo', [LogController::class, 'storeLogsSincronismo']);
