<?php

use App\Http\Controllers\api\v1\Tenant\NotaFiscalController;
use Illuminate\Support\Facades\Route;

Route::get('nota', [NotaFiscalController::class, 'getNotas'])->name('notaTenant');
Route::get('nota/{id}', [NotaFiscalController::class, 'getDetalheNota']);
Route::get('nota/margem/markup', [NotaFiscalController::class, 'getMargemMarkup']);