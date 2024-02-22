<?php

use App\Http\Controllers\api\v1\Central\IntegracaoController;
use Illuminate\Support\Facades\Route;

Route::get('integracao', [IntegracaoController::class, 'index'])->name('integracao');
Route::get('integracao/{id}', [IntegracaoController::class, 'show']);
Route::get('integracao/filiais/{idEmpresa}', [IntegracaoController::class, 'getFilial']);
Route::post('integracao/', [IntegracaoController::class, 'store']);
Route::patch('integracao/{id}', [IntegracaoController::class, 'update']);
Route::delete('integracao/{id}', [IntegracaoController::class, 'destroy']);