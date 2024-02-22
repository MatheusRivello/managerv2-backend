<?php

use App\Http\Controllers\externa\RastroExternaController;
use Illuminate\Support\Facades\Route;

Route::get('rastro', [RastroExternaController::class, 'index']);
Route::get('rastro/filtro', [RastroExternaController::class, 'show']);
Route::post('rastro/cadastro', [RastroExternaController::class, 'store']);
Route::delete('rastro/{id}', [RastroExternaController::class, 'destroy']);
