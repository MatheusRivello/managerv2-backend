<?php

use App\Http\Controllers\servico\v1\ERP\ProdutoSubGrupoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('produtosubgrupo', [ProdutoSubGrupoServicoController::class, 'storeUpdate']);
Route::post('produtosubgrupo/json', [ProdutoSubGrupoServicoController::class, 'atualizarDadosJSON']);
