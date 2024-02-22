<?php

use App\Http\Controllers\Integracao\MetaController;
use App\Http\Controllers\Integracao\MetaDetalheController;
use App\Http\Controllers\Integracao\VendedorClienteController;
use App\Http\Controllers\Integracao\VendedorController;
use App\Http\Controllers\Integracao\VendedorProdutoController;
use App\Http\Controllers\Integracao\VendedorProtabelaPrecoController;
use Illuminate\Support\Facades\Route;

Route::prefix('vendedor')->group(function () {
    Route::get('', [VendedorController::class, 'request']);
    Route::get('/meta', [MetaController::class, 'request']);
    Route::get('/meta/detalhe', [MetaDetalheController::class, 'request']);
    Route::get('/produto', [VendedorProdutoController::class, 'request']);
    Route::get('/tabela/preco', [VendedorProtabelaPrecoController::class, 'request']);
    Route::get('/cliente', [VendedorClienteController::class, 'request']);
});