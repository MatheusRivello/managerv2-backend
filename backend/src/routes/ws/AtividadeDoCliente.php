<?php

use App\Http\Controllers\externa\ClienteAtividadeExternaController;
use Illuminate\Support\Facades\Route;

Route::get('atividadecliente', [ClienteAtividadeExternaController::class, 'index']);
Route::get('atividadecliente/filtro', [ClienteAtividadeExternaController::class, 'show']);
Route::post('atividadecliente/cadastro', [ClienteAtividadeExternaController::class, 'store']);
Route::delete('atividadecliente/{id}', [ClienteAtividadeExternaController::class, 'destroy']);
