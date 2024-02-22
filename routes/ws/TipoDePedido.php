<?php

use App\Http\Controllers\externa\TipoDePedidoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('tipopedido', [TipoDePedidoExternaController::class, 'index']);
Route::get('tipopedido/filtro', [TipoDePedidoExternaController::class, 'show']);
Route::post('tipopedido/cadastro', [TipoDePedidoExternaController::class, 'store']);
Route::delete('tipopedido/{id}', [TipoDePedidoExternaController::class, 'destroy']);
