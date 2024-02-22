<?php

use App\Http\Controllers\api\v1\Tenant\IntegracaoController;
use Illuminate\Support\Facades\Route;

Route::get('elementos/{idEmpresa}', [IntegracaoController::class, 'index'])->name('integracaoTenant');
Route::get('elementos/{idEmpresa}/{filtro}/{idElemento}', [IntegracaoController::class, 'show']);
Route::post('elementos/{idEmpresa}', [IntegracaoController::class, 'storeUpdate']);
Route::delete('elementos/{idEmpresa}/{idElemento}', [IntegracaoController::class, 'destroy']);
