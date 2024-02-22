<?php

use App\Http\Controllers\Integracao\CampanhaBeneficioController;
use App\Http\Controllers\Integracao\CampanhaController;
use App\Http\Controllers\Integracao\CampanhaParticipanteController;
use App\Http\Controllers\Integracao\CampanhaRequisitoController;
use Illuminate\Support\Facades\Route;

Route::prefix('campanha')->group(function () {
    Route::get('', [CampanhaController::class, 'request']);
    Route::get('beneficio', [CampanhaBeneficioController::class, 'request']);
    Route::get('modalidade', [CampanhaBeneficioController::class, 'request']);
    Route::get('participante', [CampanhaParticipanteController::class, 'request']);
    Route::get('requisito', [CampanhaRequisitoController::class, 'request']);
});