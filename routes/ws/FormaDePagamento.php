<?php

use App\Http\Controllers\externa\FormaPgtoExternaController;
use Illuminate\Support\Facades\Route;

Route::get("formadepagamento", [FormaPgtoExternaController::class, "index"])->name("formadepagamentoWS");
Route::get('formadepagamento/filtro', [FormaPgtoExternaController::class, 'show']);
Route::delete('formadepagamento/{id}', [FormaPgtoExternaController::class, 'destroy']);
Route::post('formadepagamento/cadastro', [FormaPgtoExternaController::class, 'storeFormaPgto']);