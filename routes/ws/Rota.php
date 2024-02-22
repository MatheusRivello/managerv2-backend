<?php

use App\Http\Controllers\externa\RotaExternaController;
use Illuminate\Support\Facades\Route;

Route::get('rota', [RotaExternaController::class, 'index']);
Route::get('rota/filtro', [RotaExternaController::class, 'show']);
Route::post('rota/cadastro', [RotaExternaController::class, 'Store']);
Route::delete('rota/{id}', [RotaExternaController::class, 'destroy']);
