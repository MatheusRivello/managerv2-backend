<?php

use App\Http\Controllers\externa\PedidoItemExternaController;
use Illuminate\Support\Facades\Route;

Route::get('itens/pedido', [PedidoItemExternaController::class, 'index']);
Route::get('itens/pedido/filtro', [PedidoItemExternaController::class, 'show']);
Route::post('itens/pedido/cadastro', [PedidoItemExternaController::class, 'storePedidoItem']);
Route::delete('itens/pedido/destroy', [PedidoItemExternaController::class, 'destroyPedidoItem']);
