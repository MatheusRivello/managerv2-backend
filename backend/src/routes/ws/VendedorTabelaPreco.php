<?php

use App\Http\Controllers\externa\VendedorTabelaPrecoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('vendedor/tabela/preco', [VendedorTabelaPrecoExternaController::class, 'index']);
Route::get('vendedor/tabela/preco/filtro', [VendedorTabelaPrecoExternaController::class, 'show']);
Route::post('vendedor/tabela/preco/cadastro', [VendedorTabelaPrecoExternaController::class, 'StoreVendedorCliente']);
Route::delete('vendedor/tabela/preco/destroy', [VendedorTabelaPrecoExternaController::class, 'destroyPersonalizado']);
