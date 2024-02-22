<?php

use App\Http\Controllers\externa\ClienteReferenciaExternaController;
use Illuminate\Support\Facades\Route;

Route::get('cliente/referencia', [ClienteReferenciaExternaController::class, 'index']);
Route::get('cliente/referencia/filtro', [ClienteReferenciaExternaController::class, 'show']);
Route::post('cliente/referencia/cadastro', [ClienteReferenciaExternaController::class, 'storeClienteReferencia']);
Route::delete('cliente/referencia/destroy', [ClienteReferenciaExternaController::class, 'destroyPersonalizado']);
