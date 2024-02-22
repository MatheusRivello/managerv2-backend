<?php

use App\Http\Controllers\servico\v1\ERP\FilialServicoController;
use Illuminate\Support\Facades\Route;
//Filial
Route::post('filial', [FilialServicoController::class, 'storeUpdate']);
Route::post('filial/json', [FilialServicoController::class, 'atualizarDadosJSON']);
