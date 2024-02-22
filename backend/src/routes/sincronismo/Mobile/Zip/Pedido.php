<?php

use App\Http\Controllers\mobile\v1\zip\PedidoMobileController;
use Illuminate\Support\Facades\Route;

Route::post('pedido/completo', [PedidoMobileController::class, 'completo']);
