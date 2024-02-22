<?php

use App\Http\Controllers\servico\v1\ERP\MetaServicoController;
use App\Http\Controllers\servico\v1\ERP\MetaDetalheServicoController;
use Illuminate\Support\Facades\Route;

Route::post('meta', [MetaServicoController::class, 'storeUpdate']);
Route::post('metadetalhe', [MetaDetalheServicoController::class, 'storeUpdate']);
