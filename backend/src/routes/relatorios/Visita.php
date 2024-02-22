<?php

use App\Http\Controllers\api\v1\Relatorios\VendedorAnaliticoController;
use App\Http\Controllers\api\v1\Relatorios\VendedorCarteiraClienteController;
use App\Http\Controllers\api\v1\Relatorios\VisitaEfetuadasController;
use App\Http\Controllers\api\v1\Relatorios\VisitaNormalController;
use Illuminate\Support\Facades\Route;

Route::prefix('visita')->group(function () {
    Route::get('normal', [VisitaNormalController::class, 'getAll']);
    Route::get('efetuadas', [VisitaEfetuadasController::class, 'getAll']);
    Route::get('vendedor-carteira', [VendedorCarteiraClienteController::class, 'getAll']);
    Route::get('vendedor-analitico', [VendedorAnaliticoController::class, 'getAll']);
    Route::get('vendedor-analitico-mapeado', [VendedorAnaliticoController::class, 'mapObject']);
});