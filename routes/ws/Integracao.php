<?php

use App\Http\Controllers\externa\IntegracaoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('integracao', [IntegracaoExternaController::class, 'index']);
Route::get('integracao/filtro', [IntegracaoExternaController::class, 'show']);
Route::post('integracao/cadastro', [IntegracaoExternaController::class, 'storeIntegracao']);
Route::delete('integracao/{id}', [IntegracaoExternaController::class, 'destroy']);
