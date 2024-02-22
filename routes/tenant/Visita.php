<?php

use App\Http\Controllers\api\v1\Tenant\VisitaController;
use Illuminate\Support\Facades\Route;

Route::prefix('visita')->group(function () {
    Route::get('simplificada', [VisitaController::class, 'getCabecalhoAgenda'])->name('visitaTenant');
    Route::get('detalhadas', [VisitaController::class, 'getDadosAgenda']);
    Route::get('vendedores', [VisitaController::class, 'listaVendedores']);
    Route::get('agenda/simples', [VisitaController::class, 'getVisitaSimples']);
    Route::get('info/{idVisita}', [VisitaController::class, 'getPedidoDetalhado']);
    Route::post('cadastro', [VisitaController::class, 'cadastrarVisita']);
});
