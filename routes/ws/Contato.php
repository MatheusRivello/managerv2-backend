<?php

use App\Http\Controllers\externa\ContatoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('contato', [ContatoExternaController::class, 'index']);
Route::get('contato/filtro', [ContatoExternaController::class, 'show']);
Route::post('contato/cadastro', [ContatoExternaController::class, 'storePersonalizado']);
Route::delete('contato/destroy', [ContatoExternaController::class, 'destroyPersonalizado']);
