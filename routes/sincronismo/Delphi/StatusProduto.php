<?php

use App\Http\Controllers\servico\v1\ERP\StatusProdutoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('statusproduto', [StatusProdutoServicoController::class, 'storeUpdate']);
Route::post('statusproduto/statusprodutojson', [StatusProdutoServicoController::class, 'atualizarDadosJSON']);
