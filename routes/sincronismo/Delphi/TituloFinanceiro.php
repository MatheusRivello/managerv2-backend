<?php

use App\Http\Controllers\servico\v1\ERP\TituloFinanceiroServicoController;
use Illuminate\Support\Facades\Route;

Route::post('titulofinanceiro', [TituloFinanceiroServicoController::class, 'storeUpdate']);
Route::post('titulofinanceiro/json', [TituloFinanceiroServicoController::class, 'atualizarDadosJSON']);

