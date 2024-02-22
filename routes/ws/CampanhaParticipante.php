<?php

use App\Http\Controllers\externa\CampanhaParticipanteExternaController;
use Illuminate\Support\Facades\Route;

Route::get('campanha/participante', [CampanhaParticipanteExternaController::class, 'index']);
Route::get('campanha/participante/filtro', [CampanhaParticipanteExternaController::class, 'show']);
Route::post('campanha/participante', [CampanhaParticipanteExternaController::class, 'storeCampanha']);
Route::delete('campanha/participante/destroy', [CampanhaParticipanteExternaController::class, 'destroyCampanha']);