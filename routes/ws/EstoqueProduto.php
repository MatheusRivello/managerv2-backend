<?php

use App\Http\Controllers\externa\ProdutoEstoqueExternaController;
use Illuminate\Support\Facades\Route;

Route::get('estoqueproduto', [ProdutoEstoqueExternaController::class, 'index']);
Route::get('estoqueproduto/filtro', [ProdutoEstoqueExternaController::class, 'show']);
Route::post('estoqueproduto/cadastro', [ProdutoEstoqueExternaController::class, 'store']);
Route::delete('estoqueproduto/{id}', [ProdutoEstoqueExternaController::class, 'destroy']);