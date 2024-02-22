<?php

use App\Http\Controllers\api\v1\Central\AvisoController;
use Illuminate\Support\Facades\Route;

Route::get('aviso', [AvisoController::class, 'index'])->name('aviso');
Route::get('aviso/{id}', [AvisoController::class, 'show']);
Route::post('aviso', [AvisoController::class, 'store']);
Route::patch('aviso/{id}', [AvisoController::class, 'update']);
Route::patch('aviso/{id}/views', [AvisoController::class, 'contadorView']);
Route::delete('aviso/{id}', [AvisoController::class, 'destroy']);
