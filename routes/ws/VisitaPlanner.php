<?php

use App\Http\Controllers\externa\ClienteVisitaPlannerExternaController;
use Illuminate\Support\Facades\Route;

Route::get('cliente/visita/planner', [ClienteVisitaPlannerExternaController::class, 'index']);
Route::get('cliente/visita/planner/filtro', [ClienteVisitaPlannerExternaController::class, 'show']);
Route::post('cliente/visita/planner/cadastro', [ClienteVisitaPlannerExternaController::class, 'store']);
Route::delete('cliente/visita/planner/{id}', [ClienteVisitaPlannerExternaController::class, 'destroy']);
