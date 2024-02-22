<?php

use App\Http\Controllers\servico\v1\ERP\VisitaServicoController;
use Illuminate\Support\Facades\Route;

//Visita
Route::get('visita/novos', [VisitaServicoController::class, 'getNovos']);
Route::post('visita/novos', [VisitaServicoController::class, 'atualizaVisitas']);
