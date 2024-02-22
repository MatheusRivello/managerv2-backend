<?php

use App\Http\Controllers\api\v1\Tenant\TipoPedidoController;
use Illuminate\Support\Facades\Route;

Route::get('tipopedido', [TipoPedidoController::class, 'getTipoPedidos'])->name('tipoPedido');
