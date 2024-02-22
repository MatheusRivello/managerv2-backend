<?php

use App\Http\Controllers\externa\ClienteEnderecoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('clienteendereco', [ClienteEnderecoExternaController::class, 'index']);
Route::get('clienteendereco/filtro', [ClienteEnderecoExternaController::class, 'show']);
Route::delete('clienteendereco/delete', [ClienteEnderecoExternaController::class, 'destroyIdRetaguarda']);
Route::post('clienteendereco/cadastro', [ClienteEnderecoExternaController::class, 'store']);