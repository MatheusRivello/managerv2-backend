<?php

use App\Http\Controllers\Integracao\MotivoController;
use App\Http\Controllers\Integracao\VendaPlanoController;
use Illuminate\Support\Facades\Route;

Route::prefix('venda')->group(function () {
    Route::get('/motivo', [MotivoController::class, 'request']);
    Route::get('/plano', [VendaPlanoController::class, 'request']);
});
