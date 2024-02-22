<?php

use App\Http\Controllers\api\v1\Central\ConfiguracaoPadraoController;
use Illuminate\Support\Facades\Route;

Route::get('dispositivo/config', [ConfiguracaoPadraoController::class, 'index'])->name('configDispositivo');
Route::get('dispositivo/config/{id}', [ConfiguracaoPadraoController::class, 'show']);
Route::post('dispositivo/config/', [ConfiguracaoPadraoController::class, 'store']);
Route::post('dispositivo/config/aplicarParaTodos', [ConfiguracaoPadraoController::class, 'applyToAll']);
Route::delete('dispositivo/config/{id}', [ConfiguracaoPadraoController::class, 'destroy']);
