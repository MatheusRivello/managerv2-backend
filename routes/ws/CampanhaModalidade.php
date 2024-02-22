<?php

use App\Http\Controllers\externa\CampanhaModalidadeExternaController;
use Illuminate\Support\Facades\Route;

Route::get('campanha/modalidade', [CampanhaModalidadeExternaController::class, 'index']);
Route::get('campanha/modalidade/filtro', [CampanhaModalidadeExternaController::class, 'show']);
Route::post('campanha/modalidade', [CampanhaModalidadeExternaController::class, 'storeCampanhaModalidade']);
Route::delete('campanha/modalidade/destroy', [CampanhaModalidadeExternaController::class, 'destroyCampanhaModalidade']);