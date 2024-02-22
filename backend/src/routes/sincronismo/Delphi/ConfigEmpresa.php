<?php

use App\Http\Controllers\servico\v1\ERP\ConfigIntegracaoServicoController;
use Illuminate\Support\Facades\Route;

Route::get('confintegracao/novos', [ConfigIntegracaoServicoController::class, 'getNovasConfigs']);
