<?php

use App\Http\Controllers\servico\v1\ERP\ProdutoTabelaPrecoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('produtotabelapreco', [ProdutoTabelaPrecoServicoController::class, 'storeUpdate']);
Route::post('produtotabelapreco/json', [ProdutoTabelaPrecoServicoController::class, 'atualizarDadosJSON']);
