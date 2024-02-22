<?php

use App\Http\Controllers\api\v1\Tenant\VisitaController;
use Illuminate\Support\Facades\Route;

Route::get('justificativa/vendedor', [VisitaController::class, 'indexJustificativaVendedor']);
Route::delete('justificativa/vendedor/{id}', [VisitaController::class, 'deleteJustificativaVendedor']);
Route::post('justificativa/vendedor/cadastro', [VisitaController::class, 'storeJustificativaVendedor']);
