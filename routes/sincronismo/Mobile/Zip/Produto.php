<?php

use App\Http\Controllers\mobile\v1\zip\ProdutoMobileController;
use Illuminate\Support\Facades\Route;

Route::post('produto/completo', [ProdutoMobileController::class, 'completo']);
Route::post('produto/estoque', [ProdutoMobileController::class, 'estoque']);
