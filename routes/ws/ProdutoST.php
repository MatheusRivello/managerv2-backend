<?php

use App\Http\Controllers\externa\ProdutoSTExternaController;
use Illuminate\Support\Facades\Route;

Route::get('produtost', [ProdutoSTExternaController::class, 'index']);
Route::get('produtost/filtro', [ProdutoSTExternaController::class, 'show']);
Route::post('produtost/cadastro', [ProdutoSTExternaController::class, 'storeProdutoST']);
Route::delete('produtost/{id}', [ProdutoSTExternaController::class, 'destroy']);
