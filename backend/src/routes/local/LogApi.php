<?php

use App\Http\Controllers\Api\v1\Config\LogController;
use Illuminate\Support\Facades\Route;

Route::get('logsapi', [LogController::class, 'indexApi'])->name('logsapi');
Route::get('logsapi/filtrar/{id}', [LogController::class, 'showLogsApi']);
Route::post('logsapi', [LogController::class, 'storeLogsApi']);
