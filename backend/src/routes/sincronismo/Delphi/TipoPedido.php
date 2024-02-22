<?php

use App\Http\Controllers\servico\v1\ERP\TipoPedidoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('tipopedido', [TipoPedidoServicoController::class, 'storeUpdate']);
Route::post('tipopedido/json', [TipoPedidoServicoController::class, 'atualizarDadosJSON']);
