<?php

use App\Http\Controllers\api\v1\Central\DispositivoController;
use Illuminate\Support\Facades\Route;

Route::get('dispositivo', [DispositivoController::class, 'index'])->name('dispositivo');
Route::get('dispositivo/{id}', [DispositivoController::class, 'show']);
Route::get('dispositivo/vendedor/{id}', [DispositivoController::class, 'showVendedor']);
Route::post('dispositivo', [DispositivoController::class, 'store']);
Route::patch('dispositivo/{id}', [DispositivoController::class, 'update']);
Route::delete('dispositivo/{id}', [DispositivoController::class, 'destroy']);
