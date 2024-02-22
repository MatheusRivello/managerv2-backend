<?php

use App\Http\Controllers\mobile\v1\comum\ConfigDispositivoMobileController;
use Illuminate\Support\Facades\Route;

Route::post('configuracaodispositivo/configuracaodispositivo', [ConfigDispositivoMobileController::class, 'configuracao']);