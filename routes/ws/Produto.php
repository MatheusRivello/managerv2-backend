<?php

use App\Http\Controllers\externa\ProdutoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('produto', [ProdutoExternaController::class, 'index']);
Route::get('produto/filtro', [ProdutoExternaController::class, 'show']);
Route::delete('produto/{id}', [ProdutoExternaController::class, 'destroy']);
Route::post('produto/cadastro', [ProdutoExternaController::class, 'store']);