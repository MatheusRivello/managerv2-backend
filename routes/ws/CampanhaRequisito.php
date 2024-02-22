<?php

use App\Http\Controllers\externa\CampanhaRequisitoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('campanha/requisito', [CampanhaRequisitoExternaController::class, 'index']);
Route::get('campanha/requisito/filtro', [CampanhaRequisitoExternaController::class, 'show']);
Route::post('campanha/requisito', [CampanhaRequisitoExternaController::class, 'storeCampanha']);
Route::delete('campanha/requisito/destroy', [CampanhaRequisitoExternaController::class, 'destroyCampanha']);