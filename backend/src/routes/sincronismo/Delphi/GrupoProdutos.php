<?php

use App\Http\Controllers\servico\v1\ERP\ProdutoGrupoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('produtogrupo', [ProdutoGrupoServicoController::class, 'storeUpdate']);
Route::post('produtogrupo/json', [ProdutoGrupoServicoController::class, 'atualizarDadosJSON']);
