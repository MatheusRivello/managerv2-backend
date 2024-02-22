<?php

use App\Http\Controllers\externa\EnderecoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('endereco', [EnderecoExternaController::class, 'index']);
Route::get('endereco/filtro', [EnderecoExternaController::class, 'show']);
Route::post('endereco/cadastro', [EnderecoExternaController::class, 'storePersonalizado']);
Route::delete('endereco/destroy', [EnderecoExternaController::class, 'destroyPersonalizado']);
