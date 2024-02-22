<?php

use App\Http\Controllers\externa\AtividadeExternaController;
use Illuminate\Support\Facades\Route;

Route::get('atividade', [AtividadeExternaController::class, 'index']);
Route::get('atividade/filtro', [AtividadeExternaController::class, 'show']);
Route::post('atividade/cadastro', [AtividadeExternaController::class, 'store']);
Route::delete('atividade/{id}', [AtividadeExternaController::class, 'destroy']);
Route::patch('atividade/modificar', [AtividadeExternaController::class, 'modificarTabelaAtividade']);
