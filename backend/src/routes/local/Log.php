<?php

use App\Http\Controllers\Api\v1\Config\LogController;
use Illuminate\Support\Facades\Route;

Route::get('log', [LogController::class, 'index'])->name('log');
Route::get('log/filtrar/{id}', [LogController::class, 'showLog']);
Route::post('log', [LogController::class, 'storeLog']);
