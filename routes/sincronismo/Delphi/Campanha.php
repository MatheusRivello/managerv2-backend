<?php

use App\Http\Controllers\servico\v1\ERP\CampanhaBeneficioServicoController;
use App\Http\Controllers\servico\v1\ERP\CampanhaModalidadeServicoController;
use App\Http\Controllers\servico\v1\ERP\CampanhaParticipanteServicoController;
use App\Http\Controllers\servico\v1\ERP\CampanhaRequisitoServicoController;
use App\Http\Controllers\servico\v1\ERP\CampanhaServicoController;
use Illuminate\Support\Facades\Route;

Route::post('campanha', [CampanhaServicoController::class, 'storeUpdate']);
Route::post('campanhabeneficio', [CampanhaBeneficioServicoController::class, 'storeUpdate']);
Route::post('campanhamodalidade', [CampanhaModalidadeServicoController::class, 'storeUpdate']);
Route::post('campanhaparticipante', [CampanhaParticipanteServicoController::class, 'storeUpdate']);
Route::post('campanharequisito', [CampanhaRequisitoServicoController::class, 'storeUpdate']);

Route::post('campanha/json', [CampanhaServicoController::class, 'atualizarDadosJSON']);
Route::post('campanhabeneficio/json', [CampanhaBeneficioServicoController::class, 'atualizarDadosJSON']);
Route::post('campanhamodalidade/json', [CampanhaModalidadeServicoController::class, 'atualizarDadosJSON']);
Route::post('campanhaparticipante/json', [CampanhaParticipanteServicoController::class, 'atualizarDadosJSON']);
Route::post('campanharequisito/json', [CampanhaRequisitoServicoController::class, 'atualizarDadosJSON']);
