<?php

use App\Http\Controllers\Integracao\ConfigFilialController;
use App\Http\Controllers\Integracao\FilialController;
use Illuminate\Support\Facades\Route;

Route::prefix('empresa')->group(function () {
    Route::get('configfilial', [ConfigFilialController::class, 'request']);
    Route::get('', [FilialController::class, 'request']);
});