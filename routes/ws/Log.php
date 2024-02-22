<?php

use App\Http\Controllers\externa\LogExternaController;
use Illuminate\Support\Facades\Route;

Route::get('log', [LogExternaController::class, 'index']);
Route::get('log/filtro', [LogExternaController::class, 'show']);
Route::post('log/cadastro', [LogExternaController::class, 'store']);
Route::delete('log/{id}', [LogExternaController::class, 'destroy']);
