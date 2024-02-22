<?php

use App\Http\Controllers\servico\v1\ERP\StatusPedidoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('statuspedido', [StatusPedidoServicoController::class, 'storeUpdate']);
