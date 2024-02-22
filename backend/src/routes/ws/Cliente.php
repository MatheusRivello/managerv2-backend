<?php

use App\Http\Controllers\externa\ClienteExternaController;
use Illuminate\Support\Facades\Route;

Route::get('cliente', [ClienteExternaController::class, 'index']);
Route::get('cliente/filtro', [ClienteExternaController::class, 'show']);
Route::delete('cliente/{id}', [ClienteExternaController::class, 'destroy']);
Route::post('cliente/cadastro', [ClienteExternaController::class, 'storeCliente']);
