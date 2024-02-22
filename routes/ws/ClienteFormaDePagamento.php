<?php

use App\Http\Controllers\externa\ClienteFormaDePagamentoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('clienteformapagamento', [ClienteFormaDePagamentoExternaController::class, 'index']);
Route::get('clienteformapagamento/filtro', [ClienteFormaDePagamentoExternaController::class, 'show']);
Route::post('clienteformapagamento/cadastro', [ClienteFormaDePagamentoExternaController::class, 'store']);
Route::delete('clienteformapagamento', [ClienteFormaDePagamentoExternaController::class, 'destroyPersonalizado']);;
