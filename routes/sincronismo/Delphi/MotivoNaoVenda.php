<?php

use App\Http\Controllers\servico\v1\ERP\MotivoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('motivo', [MotivoServicoController::class, 'storeUpdate']);
