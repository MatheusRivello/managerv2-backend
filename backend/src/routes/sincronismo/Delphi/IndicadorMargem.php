<?php

use App\Http\Controllers\servico\v1\ERP\IndicadorMargemServicoController;
use Illuminate\Support\Facades\Route;

Route::post('indicadormargem', [IndicadorMargemServicoController::class, 'storeUpdate']);
Route::post('indicadormargem/json', [IndicadorMargemServicoController::class, 'atualizarDadosJSON']);
