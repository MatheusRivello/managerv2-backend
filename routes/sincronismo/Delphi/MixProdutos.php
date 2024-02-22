<?php

use App\Http\Controllers\servico\v1\ERP\MixProdutoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('mixproduto', [MixProdutoServicoController::class, 'storeUpdate']);
