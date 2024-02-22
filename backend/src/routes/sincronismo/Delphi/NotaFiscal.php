<?php

use App\Http\Controllers\servico\v1\ERP\NotaFiscalServicoController;
use App\Http\Controllers\servico\v1\ERP\NotaFiscalItemServicoController;
use Illuminate\Support\Facades\Route;

Route::post('notafiscal', [NotaFiscalServicoController::class, 'storeUpdate']);
Route::post('notafiscalitem', [NotaFiscalItemServicoController::class, 'storeUpdate']);

Route::post('notafiscal/json', [NotaFiscalServicoController::class, 'atualizarDadosJSON']);
Route::post('notafiscalitem/json', [NotaFiscalItemServicoController::class, 'atualizarDadosJSON']);