<?php

use App\Http\Controllers\servico\v1\ERP\ConfigFilialServicoController;
use Illuminate\Support\Facades\Route;

Route::post('configuracaofilial', [ConfigFilialServicoController::class, 'storeUpdate']);
Route::post('configuracaofilial/json', [ConfigFilialServicoController::class, 'atualizarDadosJSON']);
