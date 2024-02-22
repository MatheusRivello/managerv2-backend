<?php

use App\Http\Controllers\Api\v1\Config\LogController;
use Illuminate\Support\Facades\Route;

Route::get('logscontato', [LogController::class, 'indexContato'])->name('logscontato');
Route::get('logscontato/filtrar/{id}', [LogController::class, 'showLogsContato']);
Route::post('logscontato', [LogController::class, 'storeContato']);
