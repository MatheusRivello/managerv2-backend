<?php

use App\Http\Controllers\externa\IndicadorDeMargemExternaController;
use Illuminate\Support\Facades\Route;

Route::get('indicadordemargem', [IndicadorDeMargemExternaController::class, 'index']);
Route::get('indicadordemargem/filtro', [IndicadorDeMargemExternaController::class, 'show']);
Route::post('indicadordemargem/cadastro', [IndicadorDeMargemExternaController::class, 'store']);
Route::delete('indicadordemargem/{id}', [IndicadorDeMargemExternaController::class, 'destroy']);
