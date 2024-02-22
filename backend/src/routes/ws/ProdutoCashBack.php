<?php

use App\Http\Controllers\externa\ProdutoCashBackExternaController;
use Illuminate\Support\Facades\Route;

Route::get('produto/cashback', [ProdutoCashBackExternaController::class, 'index']);
Route::get('produto/cashback/filtro', [ProdutoCashBackExternaController::class, 'show']);
Route::post('produto/cashback/cadastro', [ProdutoCashBackExternaController::class, 'storeProdutoCashback']);
Route::delete('produto/cashback/{id}', [ProdutoCashBackExternaController::class, 'destroy']);
