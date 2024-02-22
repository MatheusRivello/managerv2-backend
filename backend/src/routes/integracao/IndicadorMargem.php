<?php

use App\Http\Controllers\Integracao\IndicadorMargemController;
use Illuminate\Support\Facades\Route;

Route::get('indicadormargem', [IndicadorMargemController::class, 'request']);