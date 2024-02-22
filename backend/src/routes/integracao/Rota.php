<?php

use App\Http\Controllers\Integracao\RotaIntegracaoController;
use Illuminate\Support\Facades\Route;

Route::get('rota', [RotaIntegracaoController::class, 'request']);