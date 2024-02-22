<?php

use App\Http\Controllers\mobile\v1\zip\ClienteMobileController;
use Illuminate\Support\Facades\Route;

Route::post('cliente/cliente', [ClienteMobileController::class, 'cliente']);
