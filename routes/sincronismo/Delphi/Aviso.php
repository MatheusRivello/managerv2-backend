<?php

use App\Http\Controllers\servico\v1\ERP\AvisoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('aviso', [AvisoServicoController::class, 'storeUpdate']);
Route::post('aviso/json', [AvisoServicoController::class, 'atualizarDadosJSON']);
