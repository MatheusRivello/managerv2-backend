<?php

use App\Http\Controllers\externa\VendaPlanoxProdutoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('venda/plano/produto', [VendaPlanoxProdutoExternaController::class, 'index']);
Route::get('venda/plano/produto/filtro', [VendaPlanoxProdutoExternaController::class, 'show']);
Route::post('venda/plano/produto/cadastro', [VendaPlanoxProdutoExternaController::class, 'storeVendaPlanoXProduto']);
Route::delete('venda/plano/produto/{id}', [VendaPlanoxProdutoExternaController::class, 'destroy']);
