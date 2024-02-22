<?php

use App\Http\Controllers\servico\v1\ERP\RastroServicoController;
use Illuminate\Support\Facades\Route;

Route::get('rastro/novos', [RastroServicoController::class, 'getNovosRastros']);
Route::post('rastro/novos', [RastroServicoController::class, 'atualizaRastros']);
