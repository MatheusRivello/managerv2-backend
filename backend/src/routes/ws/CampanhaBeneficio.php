<?php

use App\Http\Controllers\externa\CampanhaBeneficioExternaController;
use Illuminate\Support\Facades\Route;

Route::get('campanha/beneficio', [CampanhaBeneficioExternaController::class, 'index']);
Route::get('campanha/beneficio/filtro', [CampanhaBeneficioExternaController::class, 'show']);
Route::post('campanha/beneficio/cadastro', [CampanhaBeneficioExternaController::class, 'storeCampanhaBeneficio']);
Route::delete('campanha/beneficio/destroy', [CampanhaBeneficioExternaController::class, 'destroyPersonalizado']);