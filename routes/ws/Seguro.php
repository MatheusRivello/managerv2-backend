<?php

use App\Http\Controllers\externa\SeguroExternaController;
use Illuminate\Support\Facades\Route;

Route::get('seguro', [SeguroExternaController::class, 'index']);
Route::get('seguro/filtro', [SeguroExternaController::class, 'show']);
Route::post('seguro/cadastro', [SeguroExternaController::class, 'store']);
Route::delete('seguro/{id}', [SeguroExternaController::class, 'destroy']);
