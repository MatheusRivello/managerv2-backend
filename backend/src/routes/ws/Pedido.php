<?php

use App\Http\Controllers\externa\PedidoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('pedido', [PedidoExternaController::class, 'index']);
Route::get('pedido/filtro', [PedidoExternaController::class, 'show']);
Route::get('pedido/item', [PedidoExternaController::class, 'getPedidoItem']);
Route::post('pedido/cadastro', [PedidoExternaController::class, 'storePedidoExterna']);
Route::delete('pedido/destroy', [PedidoExternaController::class, 'destroyPedidoExterna']);
