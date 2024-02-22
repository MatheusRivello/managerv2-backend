<?php

use App\Http\Controllers\externa\TituloFinanceiroExternaController;
use Illuminate\Support\Facades\Route;

Route::get('titulofinanceiro', [TituloFinanceiroExternaController::class, 'index'])->name('tituloFinanceiroWS');
Route::get('titulofinanceiro/filtro', [TituloFinanceiroExternaController::class, 'showPersonalizado']);
Route::delete('titulofinanceiro/{id}', [TituloFinanceiroExternaController::class, 'destroy']);
Route::post('titulofinanceiro/cadastro', [TituloFinanceiroExternaController::class, 'store']);
 Route::patch('titulofinanceiro/alterar/{id}', [TituloFinanceiroExternaController::class, 'updateTituloFinanceiro']);