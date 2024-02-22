<?php

use App\Http\Controllers\externa\NotaFiscalExternaController;
use Illuminate\Support\Facades\Route;

Route::get('notafiscal', [NotaFiscalExternaController::class, 'index']);
Route::get('notafiscal/filtro', [NotaFiscalExternaController::class, 'show']);
Route::delete('notafiscal/{id}', [NotaFiscalExternaController::class, 'destroy']);
Route::post('notafiscal/cadastro', [NotaFiscalExternaController::class, 'store']);
Route::get('notafiscal/notafiscalcomitem', [NotaFiscalExternaController::class, 'getNotaFiscalItem']);
Route::post('notafiscal/cadastrodeItem', [NotaFiscalExternaController::class, 'storeNotaFiscalItem']);
