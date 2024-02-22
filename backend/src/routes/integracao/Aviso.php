<?php

use App\Http\Controllers\Integracao\AvisoController;
use Illuminate\Support\Facades\Route;

Route::get('aviso', [AvisoController::class, 'request']);