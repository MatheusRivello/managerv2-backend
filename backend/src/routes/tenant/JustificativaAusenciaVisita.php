<?php

use App\Http\Controllers\api\v1\Tenant\VisitaController;
use Illuminate\Support\Facades\Route;

Route::get('justificativa/visita', [VisitaController::class, 'indexJustificativaVisita']);
Route::delete('justificativa/visita/{id}', [VisitaController::class, 'deleteJustificativaVisita']);
Route::post('justificativa/visita/cadastro', [VisitaController::class, 'storeJustificativaVisita']);
