<?php

use App\Http\Controllers\mobile\v1\zip\FinanceiroMobileController;
use Illuminate\Support\Facades\Route;

Route::post('infofinanceira/infofinanceira', [FinanceiroMobileController::class, 'infofinanceira']);