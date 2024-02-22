<?php

use App\Http\Controllers\externa\ClienteTabelaPrecoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('tabela/preco/cliente', [ClienteTabelaPrecoExternaController::class, 'index']);
Route::get('tabela/preco/cliente/filtro', [ClienteTabelaPrecoExternaController::class, 'show']);
Route::post('tabela/preco/cliente/cadastro', [ClienteTabelaPrecoExternaController::class, 'storeClienteTabelaPreco']);
Route::delete('tabela/preco/cliente/destroy', [ClienteTabelaPrecoExternaController::class, 'destroyPersonalizado']);
