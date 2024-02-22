<?php

use App\Http\Controllers\externa\MotivoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('motivo', [MotivoExternaController::class, 'index']);
Route::get('motivo/filtro', [MotivoExternaController::class, 'show']);
Route::delete('motivo/{id}', [MotivoExternaController::class, 'destroy']);
Route::post('motivo/cadastro', [MotivoExternaController::class, 'store']);