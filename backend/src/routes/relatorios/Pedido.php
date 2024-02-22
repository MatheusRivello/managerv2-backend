<?php

use App\Http\Controllers\api\v1\Relatorios\PedidoController ;
use Illuminate\Support\Facades\Route;

Route::prefix('pedido')->group(function () {
    Route::get('', [PedidoController::class, 'getAll']);
});