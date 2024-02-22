<?php

use App\Http\Controllers\externa\FormaPrazoDePagamentoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('formaprazodepagamento', [FormaPrazoDePagamentoExternaController::class, 'index']);
Route::get('formaprazodepagamento/filtro', [FormaPrazoDePagamentoExternaController::class, 'show']);
Route::post('formaprazodepagamento/cadastro', [FormaPrazoDePagamentoExternaController::class, 'storePersonalizado']);
Route::delete('formaprazodepagamento/destroy', [FormaPrazoDePagamentoExternaController::class, 'destroyPersonalizado']);
