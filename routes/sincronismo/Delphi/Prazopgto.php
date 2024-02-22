<?php

use App\Http\Controllers\servico\v1\ERP\PrazoPagamentoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('prazopgto', [PrazoPagamentoServicoController::class, 'storeUpdate']);
Route::post('prazopgto/json', [PrazoPagamentoServicoController::class, 'atualizarDadosJSON']);
