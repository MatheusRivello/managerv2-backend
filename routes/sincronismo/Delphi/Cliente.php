<?php

use App\Http\Controllers\servico\v1\ERP\ClienteContatoServicoController;
use App\Http\Controllers\servico\v1\ERP\ClienteFormaPgtoServicoController;
use App\Http\Controllers\servico\v1\ERP\ClientePrazoPgtoServicoController;
use App\Http\Controllers\servico\v1\ERP\ClienteServicoController;
use App\Http\Controllers\servico\v1\ERP\ClienteTabelaGrupoServicoController;
use App\Http\Controllers\servico\v1\ERP\ClienteTabelaPrecoServicoController;
use Illuminate\Support\Facades\Route;

Route::get('cliente/novos', [ClienteServicoController::class, 'novos']);
Route::post('cliente/novos', [ClienteServicoController::class, 'atualizaClientes']);
Route::post('cliente/zip', [ClienteServicoController::class, 'storeUpdate']);
Route::post('clienteformapgto', [ClienteFormaPgtoServicoController::class, 'storeUpdate']);
Route::post('clienteprazopgto', [ClientePrazoPgtoServicoController::class, 'storeUpdate']);
Route::post('clientetabelagrupo', [ClienteTabelaGrupoServicoController::class, 'storeUpdate']);
Route::post('clientetabelapreco', [ClienteTabelaPrecoServicoController::class, 'storeUpdate']);
Route::post('clientecontato', [ClienteContatoServicoController::class, 'storeUpdate']);

Route::post('cliente/cilentejson', [ClienteServicoController::class, 'atualizarDadosJSON']);

Route::post('contato/json', [ClienteContatoServicoController::class, 'atualizarDadosJSON']);
Route::post('clienteformapgto/json', [ClienteFormaPgtoServicoController::class, 'atualizarDadosJSON']);
Route::post('clienteprazopgto/json', [ClientePrazoPgtoServicoController::class, 'atualizarDadosJSON']);
Route::post('clientetabelapreco/json', [ClienteTabelaPrecoServicoController::class, 'atualizarDadosJSON']);
Route::post('clientetabelapreco/json', [ClienteTabelaPrecoServicoController::class, 'atualizarDadosJSON']);
//olhar essa rota depois
Route::post('cliente/json', [ClienteServicoController::class, 'atualizarDadosJSON']);
