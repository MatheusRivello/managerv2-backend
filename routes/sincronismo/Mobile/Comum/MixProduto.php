<?php

use App\Http\Controllers\mobile\v1\comum\MixProdutoMobileController;
use Illuminate\Support\Facades\Route;

Route::post('mixproduto/retornamixproduto', [MixProdutoMobileController::class, 'retornamixproduto']);
