<?php

use App\Http\Controllers\api\v1\Tenant\PedidoController;
use Illuminate\Support\Facades\Route;

Route::get('pedido/andamento', [PedidoController::class, 'listaAndamentoPedidos'])->name('listaAndamentoPedidos');
Route::get('pedido/detalhe/{id}', [PedidoController::class, 'detalhePedidoPendente']);
Route::get('pedido/coordenada/total', [PedidoController::class, 'totalPedidosCoordenadas']);
Route::get('pedido/coordenada/vendedor/{id}', [PedidoController::class, 'listarPedidosVendedor']);
Route::get('pedido/coordenada/lista', [PedidoController::class, 'listaPedidosComCoordenadas']);
Route::get('pedido/coordenada/estado/cidade', [PedidoController::class, 'pedidosPorEstadosCidades']);
Route::get('pedido/coordenada/regioes', [PedidoController::class, 'somenteCoordenadas']);
Route::get('pedido/por-dias', [PedidoController::class, 'totaisPorDias']);