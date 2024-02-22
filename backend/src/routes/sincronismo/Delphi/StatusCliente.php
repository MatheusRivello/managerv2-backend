<?php

use App\Http\Controllers\servico\v1\ERP\StatusClienteServicoController;
use Illuminate\Support\Facades\Route;

Route::post('statuscliente', [StatusClienteServicoController::class, 'storeUpdate']);
Route::post('statuscliente/json', [StatusClienteServicoController::class, 'atualizarDadosJSON']);
