<?php

use App\Http\Controllers\externa\MetaExternaController;
use Illuminate\Support\Facades\Route;

Route::get('meta', [MetaExternaController::class, 'index']);
Route::get('meta/filtro', [MetaExternaController::class, 'show']);
Route::post('meta/cadastro', [MetaExternaController::class, 'store']);
Route::delete('meta/{id}', [MetaExternaController::class, 'destroy']);
