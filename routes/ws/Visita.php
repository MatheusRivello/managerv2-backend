<?php

use App\Http\Controllers\externa\VisitaExternaController;
use Illuminate\Support\Facades\Route;

Route::get('visita', [VisitaExternaController::class, 'index']);
Route::get('visita/filtro', [VisitaExternaController::class, 'show']);
Route::post('visita/cadastro', [VisitaExternaController::class, 'store']);
Route::delete('visita/{id}', [VisitaExternaController::class, 'destroy']);;
