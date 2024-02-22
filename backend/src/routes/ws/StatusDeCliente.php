<?php

use App\Http\Controllers\externa\StatusDeClienteExternaController;
use Illuminate\Support\Facades\Route;

Route::get('statuscliente', [StatusDeClienteExternaController::class, 'index']);
Route::get('statuscliente/filtro', [StatusDeClienteExternaController::class, 'show']);
Route::post('statuscliente/cadastro', [StatusDeClienteExternaController::class, 'store']);
Route::delete('statuscliente/{id}', [StatusDeClienteExternaController::class, 'destroy']);