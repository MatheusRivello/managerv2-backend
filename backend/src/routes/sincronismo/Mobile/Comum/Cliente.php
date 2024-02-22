<?php

use App\Http\Controllers\mobile\v1\comum\ClienteMobileController;
use Illuminate\Support\Facades\Route;

Route::post('cliente/clientejson', [ClienteMobileController::class, 'clientejson']);
