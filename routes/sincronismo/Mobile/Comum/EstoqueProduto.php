<?php

use App\Http\Controllers\mobile\v1\comum\ProdutoEstoqueMobileController;
use Illuminate\Support\Facades\Route;

Route::post('produtoestoque/produtoestoque', [ProdutoEstoqueMobileController::class, 'setProdutoEstoque']);
Route::post('produto/{idProduto}', [ProdutoEstoqueMobileController::class, 'produto']);
