<?php

use App\Http\Controllers\externa\CidadeExternaController;
use Illuminate\Support\Facades\Route;

Route::get('cidade', [CidadeExternaController::class, 'index'])->name('cidadeWS');
Route::get('cidade/filtro', [CidadeExternaController::class, 'show']);
Route::delete('cidade/{id}', [CidadeExternaController::class, 'destroy']);
Route::post('cidade/cadastro', [CidadeExternaController::class, 'store']);
