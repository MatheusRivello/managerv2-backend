<?php

use App\Http\Controllers\mobile\v1\comum\PedidoV2MobileController;
use Illuminate\Support\Facades\Route;

Route::post('pedidov2/pedidoatualizado', [PedidoV2MobileController::class, 'pedidoatualizado']);
Route::post('pedidov2/pedidojson', [PedidoV2MobileController::class, 'pedidojson']);
