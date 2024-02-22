<?php

use App\Http\Controllers\externa\ImagemExternaController;
use Illuminate\Support\Facades\Route;

Route::get('imagem/produto', [ImagemExternaController::class, 'index']);
Route::post('imagem/produto/cadastro', [ImagemExternaController::class, 'storeProdutoImagem']);
Route::delete('imagem/produto/{id}', [ImagemExternaController::class, 'destroyProdutoImagem']);