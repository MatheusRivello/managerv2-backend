<?php

use App\Http\Controllers\externa\ProdutoTabelaExternaController;
use Illuminate\Support\Facades\Route;

Route::get('produtotabelapreco', [ProdutoTabelaExternaController::class, 'index']);
Route::get('produtotabelapreco/filtro', [ProdutoTabelaExternaController::class, 'show']);
Route::post('produtotabelapreco/cadastro', [ProdutoTabelaExternaController::class, 'storeProdutoTabelaExterna']);
Route::delete('produtotabelapreco/destroy', [ProdutoTabelaExternaController::class, 'destroyPersonalizado']);
