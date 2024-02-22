<?php

use App\Http\Controllers\Api\v1\Config\LogController;
use Illuminate\Support\Facades\Route;

Route::get('logsdispositivo', [LogController::class, 'indexDispositivo'])->name('logsdispositivo');
Route::get('logsdispositivo/filtrar/{id}', [LogController::class, 'showLogsDispositivo']);
Route::post('logsdispositivo', [LogController::class, 'storeDispositivo']);
