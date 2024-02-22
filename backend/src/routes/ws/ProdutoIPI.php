<?php

use App\Http\Controllers\externa\ProdutoIpiExternaController;
use Illuminate\Support\Facades\Route;

Route::get('produtoipi', [ProdutoIpiExternaController::class, 'index']);
Route::get('produtoipi/filtro', [ProdutoIpiExternaController::class, 'show']);
Route::post('produtoipi/cadastro', [ProdutoIpiExternaController::class, 'store']);
Route::delete('produtoipi/{id}', [ProdutoIpiExternaController::class, 'destroy']);
