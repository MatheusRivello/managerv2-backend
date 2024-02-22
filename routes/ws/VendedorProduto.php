<?php

use App\Http\Controllers\externa\VendedorxProdutoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('vendedor/produto', [VendedorxProdutoExternaController::class, 'index']);
Route::get('vendedor/produto/filtro', [VendedorxProdutoExternaController::class, 'show']);
Route::post('vendedor/produto/cadastro', [VendedorxProdutoExternaController::class, 'storeVendedorProduto']);
Route::delete('vendedor/produto/destroy', [VendedorxProdutoExternaController::class, 'destroyVendedorxProduto']);
