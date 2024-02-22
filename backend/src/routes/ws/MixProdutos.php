<?php

use App\Http\Controllers\externa\MixProdutoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('mix/produtos', [MixProdutoExternaController::class, 'index']);
Route::get('mix/produtos/filtro', [MixProdutoExternaController::class, 'show']);
Route::post('mix/produtos/cadastro', [MixProdutoExternaController::class, 'storeMixProduto']);
Route::delete('mix/produtos/destroy', [MixProdutoExternaController::class, 'destroyMixProduto']);
