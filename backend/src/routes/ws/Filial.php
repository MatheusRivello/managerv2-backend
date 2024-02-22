<?php

use App\Http\Controllers\externa\FilialExternaController;
use Illuminate\Support\Facades\Route;

Route::get('filial', [FilialExternaController::class, 'index']);
Route::get('filial/filtro', [FilialExternaController::class, 'show']);
Route::post('filial/cadastro', [FilialExternaController::class, 'store']);
Route::delete('filial/{id}', [FilialExternaController::class, 'destroy']);
