<?php

use App\Http\Controllers\api\v1\Central\ConfigIntegradorController;
use Illuminate\Support\Facades\Route;

Route::get('config-integrador', [ConfigIntegradorController::class, 'getConfigIntegrador']);
Route::post('config-integrador/cadastro', [ConfigIntegradorController::class, 'postConfigIntegrador']);
Route::put('config-integrador/modificar', [ConfigIntegradorController::class, 'putConfigIntegrador']);