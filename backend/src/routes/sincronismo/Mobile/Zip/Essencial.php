<?php

use App\Http\Controllers\mobile\v1\zip\EssencialMobileController;
use Illuminate\Support\Facades\Route;

Route::post('essencial/essencial', [EssencialMobileController::class, 'essencial']);
