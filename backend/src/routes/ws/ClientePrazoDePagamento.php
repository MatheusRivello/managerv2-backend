<?php

use App\Http\Controllers\externa\ClientePrazoPagamentoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('clienteprazodepagamento', [ClientePrazoPagamentoExternaController::class, 'index']);
Route::get('clienteprazodepagamento/filtro', [ClientePrazoPagamentoExternaController::class, 'show']);
Route::post('clienteprazodepagamento/cadastro', [ClientePrazoPagamentoExternaController::class, 'store']);
Route::delete('clienteprazodepagamento/destroy', [ClientePrazoPagamentoExternaController::class, 'destroyPersonalizado']);
