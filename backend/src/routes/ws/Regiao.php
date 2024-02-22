<?php

use App\Http\Controllers\externa\RegiaoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('regiao', [RegiaoExternaController::class, 'index']);
Route::get('regiao/filtro', [RegiaoExternaController::class, 'show']);
Route::post('regiao/cadastro', [RegiaoExternaController::class, 'store']);
Route::delete('regiao/{id}', [RegiaoExternaController::class, 'destroy']);
