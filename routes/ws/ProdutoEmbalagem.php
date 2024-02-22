<?php

use App\Http\Controllers\externa\ProdutoEmbalagemExternaController;
use Illuminate\Support\Facades\Route;

Route::get('produtoembalagem', [ProdutoEmbalagemExternaController::class, 'index']);
Route::get('produtoembalagem/filtro', [ProdutoEmbalagemExternaController::class, 'show']);
Route::post('produtoembalagem/cadastro', [ProdutoEmbalagemExternaController::class, 'store']);
Route::delete('produtoembalagem/{id}', [ProdutoEmbalagemExternaController::class, 'destroy']);
