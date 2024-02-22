<?php

use App\Http\Controllers\servico\v1\ERP\PedidoServicoController;
use Illuminate\Support\Facades\Route;

//Pedido
Route::get('pedido/novos', [PedidoServicoController::class, 'getPedidos']);
Route::post('pedido/novos', [PedidoServicoController::class, 'atualizaPedidos']);
