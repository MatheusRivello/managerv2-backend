<?php

use App\Http\Controllers\Integracao\NotaFiscalController;
use App\Http\Controllers\Integracao\NotaFiscalItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('notafiscal')->group(function () {
    Route::get('/cabecalho', [NotaFiscalController::class, 'request']); 
    Route::get('/itens', [NotaFiscalItemController::class, 'request']); 
});