<?php

use App\Http\Controllers\externa\VisitaSetoresExternaController;
use Illuminate\Support\Facades\Route;

Route::get('cliente/visita/setores', [VisitaSetoresExternaControllertroller::class, 'index']);
Route::get('cliente/visita/setores/filtro', [VisitaSetoresExternaController::class, 'show']);
Route::post('cliente/visita/setores/cadastro', [VisitaSetoresExternaController::class, 'store']);
Route::delete('cliente/visita/setores/{id}', [VisitaSetoresExternaController::class, 'destroy']);
