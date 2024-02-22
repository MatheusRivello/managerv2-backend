<?php

use App\Http\Controllers\Integracao\StatusPedidoController;
use App\Http\Controllers\Integracao\TipoPedidoController;
use Illuminate\Support\Facades\Route;

Route::prefix('pedido')->group(function () {
    Route::get('/tipo', [TipoPedidoController::class, 'request']);
    Route::get('/status', [StatusPedidoController::class, 'request']);
    
});
