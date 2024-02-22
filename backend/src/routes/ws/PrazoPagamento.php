<?php

use App\Http\Controllers\externa\PrazoDePagamentoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('prazodepagamento', [PrazoDePagamentoExternaController::class, 'index']);
Route::get('prazodepagamento/filtro', [PrazoDePagamentoExternaController::class, 'show']);
Route::delete('prazodepagamento/{id}', [PrazoDePagamentoExternaController::class, 'destroy']);
Route::post('prazodepagamento/cadastro', [PrazoDePagamentoExternaController::class, 'storePrazoDePagamentoExterna']);