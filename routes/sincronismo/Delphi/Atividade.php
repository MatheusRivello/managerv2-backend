<?php

use App\Http\Controllers\servico\v1\ERP\AtividadeServicoController;
use Illuminate\Support\Facades\Route;

Route::post('atividade', [AtividadeServicoController::class, 'storeUpdate']);
Route::post('atividade/json', [AtividadeServicoController::class, 'atualizarDadosJSON']);
