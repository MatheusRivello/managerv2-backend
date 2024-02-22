<?php

use App\Http\Controllers\mobile\v1\comum\PedidoMobileController;
use Illuminate\Support\Facades\Route;

Route::post('pedido/pedidoatualizado', [PedidoMobileController::class, 'pedidoatualizado']);
Route::post('pedido/pedidojson', [PedidoMobileController::class, 'pedidojson']);
