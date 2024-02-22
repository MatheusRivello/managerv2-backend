<?php

use App\Http\Controllers\externa\CampanhaExternaController;
use Illuminate\Support\Facades\Route;

Route::get('campanha', [CampanhaExternaController::class, 'index']);
Route::get('campanha/filtro', [CampanhaExternaController::class, 'show']);
Route::post('campanha', [CampanhaExternaController::class, 'store']);
Route::delete('campanha/{id}', [CampanhaExternaController::class, 'destroy']);


