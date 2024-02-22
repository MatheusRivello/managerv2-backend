<?php

use App\Http\Controllers\externa\AvisoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('aviso', [AvisoExternaController::class, 'index']);
Route::get('aviso/filtro', [AvisoExternaController::class, 'show']);
Route::post('aviso/cadastro', [AvisoExternaController::class, 'store']);
Route::delete('aviso/{id}', [AvisoExternaController::class, 'destroy']);;
