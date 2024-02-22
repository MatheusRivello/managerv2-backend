<?php

use App\Http\Controllers\Integracao\MixProdutoController;
use App\Http\Controllers\Integracao\ProdutoController;
use App\Http\Controllers\Integracao\ProdutoDescontoQuantidadeController;
use App\Http\Controllers\Integracao\ProdutoEmbalagemController;
use App\Http\Controllers\Integracao\ProdutoEstoqueController;
use App\Http\Controllers\Integracao\ProdutoGrupoController;
use App\Http\Controllers\Integracao\ProdutoIPIController;
use App\Http\Controllers\Integracao\ProdutoSTController;
use App\Http\Controllers\Integracao\ProdutoSubGrupoController;
use App\Http\Controllers\Integracao\ProdutoTabelaItensController;
use App\Http\Controllers\Integracao\ProdutoTabelaPrecoController;
use App\Http\Controllers\Integracao\StatusProdutoController;
use App\Http\Controllers\Integracao\VendaPlanoProdutoController;
use Illuminate\Support\Facades\Route;

Route::prefix('produto')->group(function () {
    Route::get('/obter', [ProdutoController::class, 'request']);
    Route::get('/tabela/itens', [ProdutoTabelaItensController::class, 'request']);
    Route::get('/mix', [MixProdutoController::class, 'request']);
    Route::get('/desconto/quantidade', [ProdutoDescontoQuantidadeController::class, 'request']);
    Route::get('/embalagem', [ProdutoEmbalagemController::class, 'request']);
    Route::get('/estoque', [ProdutoEstoqueController::class, 'request']);
    Route::get('/grupo', [ProdutoGrupoController::class, 'request']);
    Route::get('/subgrupo', [ProdutoSubGrupoController::class, 'request']);
    Route::get('/ipi', [ProdutoIPIController::class, 'request']);
    Route::get('/st', [ProdutoSTController::class, 'request']);
    Route::get('/tabela/preco', [ProdutoTabelaPrecoController::class, 'request']);
    Route::get('/status', [StatusProdutoController::class, 'request']);
    Route::get('/venda/plano', [VendaPlanoProdutoController::class, 'request']);
});
