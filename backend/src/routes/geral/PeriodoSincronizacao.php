<?php

use App\Http\Controllers\api\v1\Central\PeriodoSincronizacaoController;
use Illuminate\Support\Facades\Route;

Route::get('periodo/sincronizacao', [PeriodoSincronizacaoController::class, 'show']);
Route::post('periodo/sincronizacao/cadastro', [PeriodoSincronizacaoController::class, 'store']);
