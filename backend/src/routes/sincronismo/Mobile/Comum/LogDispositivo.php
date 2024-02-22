<?php

use App\Http\Controllers\mobile\v1\comum\LogDispositivoMobileController;
use Illuminate\Support\Facades\Route;

Route::post('logdispositivo/logdispositivojson', [LogDispositivoMobileController::class, 'logdispositivojson']);
