<?php

use App\Http\Controllers\Integracao\FormaPagamentoController;
use App\Http\Controllers\Integracao\PrazoPagamentoController;
use Illuminate\Support\Facades\Route;

Route::prefix('condicao')->group(function () {
    Route::get('/prazo/pagamento', [PrazoPagamentoController::class, 'request']);
    Route::get('/forma/pagamento', [FormaPagamentoController::class, 'request']);
});