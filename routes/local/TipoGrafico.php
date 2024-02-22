<?php

use App\Http\Controllers\api\v1\Config\TipoGraficoController;
use Illuminate\Support\Facades\Route;

Route::get('graficos', [TipoGraficoController::class, 'index'])->name('tipoGrafico');
Route::post('graficos', [TipoGraficoController::class, 'store']);
Route::get('graficos/{id}', [TipoGraficoController::class, 'show']);
Route::delete('graficos/{id}', [TipoGraficoController::class, 'destroy']);
