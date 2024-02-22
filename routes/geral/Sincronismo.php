<?php

use App\Http\Controllers\api\v1\Central\SincronismoController;
use App\Http\Controllers\api\v1\Central\SincronismoInternoController;
use Illuminate\Support\Facades\Route;

Route::get('sincronismo/configuracao', [SincronismoController::class, 'indexConfigEmpresa'])->name('conf_sinc');
// Route::get('sincronismo/configuracao', [SincronismoInternoController::class, 'index'])->name('conf_sinc');
Route::get('sincronismo/search/configuracao', [SincronismoInternoController::class, 'show']);
Route::post('sincronismo/configuracao', [SincronismoInternoController::class, 'storeConfigEmpresa']);
Route::post('sincronismo/configuracao/cadastro', [SincronismoInternoController::class, 'storeSincronismo']);
Route::get('sincronismo/periodo', [SincronismoController::class, 'indexPeriodoSinc'])->name('periodo_sinc');
Route::patch('sincronismo/periodo', [SincronismoController::class, 'updatePeriodoSinc']);
Route::patch('sincronismo/periodo', [SincronismoController::class, 'applyToAll']);
