<?php

use App\Http\Controllers\api\v1\Tenant\LogTenantController;
use Illuminate\Support\Facades\Route;

Route::get('logstenant', [LogController::class, 'index'])->name('logsFilialTenant');
Route::get('logstenant/filtrar/{id}', [LogController::class, 'show']);
Route::post('logstenant', [LogController::class, 'store']);
