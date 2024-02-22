<?php

use App\Http\Controllers\mobile\v1\comum\DispositivoMobileController;
use Illuminate\Support\Facades\Route;

Route::post('dispositivo/verificar', [DispositivoMobileController::class, 'verificar']);
