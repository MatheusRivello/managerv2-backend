<?php

use App\Http\Controllers\externa\NotaFiscalItemExternaController;
use Illuminate\Support\Facades\Route;

Route::get('notafiscal/item', [NotaFiscalItemExternaController::class, 'index']);
Route::get('notafiscal/item/filtro', [NotaFiscalItemExternaController::class, 'show']);
Route::post('notafiscal/item/cadastro', [NotaFiscalItemExternaController::class, 'store']);
Route::delete('notafiscal/item/destroy', [NotaFiscalItemExternaController::class, 'destroyPersonalizado']);
