<?php

use App\Http\Controllers\externa\TabelaPrecoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('tabelapreco', [TabelaPrecoExternaController::class, 'index'])->name('tabelaPrecoWS');
Route::get('tabelapreco/filtro', [TabelaPrecoExternaController::class, 'show']);
Route::delete('tabelapreco/{id}', [TabelaPrecoExternaController::class, 'destroy']);
Route::post('tabelapreco/cadastro', [TabelaPrecoExternaController::class, 'storeTabelaPreco']);
Route::patch('tabelapreco/modificar', [TabelaPrecoExternaController::class, 'moodificarTabelaPreco']);