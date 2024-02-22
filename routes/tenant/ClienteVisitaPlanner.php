<?php

use App\Http\Controllers\api\v1\Tenant\VisitaController;
use Illuminate\Support\Facades\Route;

Route::get('planner', [VisitaController::class, 'indexVisitaPlanner']);
Route::delete('planner/{id}', [VisitaController::class, 'deleteClienteVisitaPlanner']);
Route::post('planner/cadastro', [VisitaController::class, 'storeClienteVisitaPlanner']);
