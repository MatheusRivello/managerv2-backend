<?php

use App\Http\Controllers\servico\v1\ERP\RotaServicoController;
use Illuminate\Support\Facades\Route;

Route::post('rota', [RotaServicoController::class, 'storeUpdate']);
