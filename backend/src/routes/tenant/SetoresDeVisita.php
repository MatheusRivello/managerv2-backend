<?php

use App\Http\Controllers\api\v1\Tenant\VisitaController;
use Illuminate\Support\Facades\Route;

Route::get('setor/visita', [VisitaController::class, 'indexVisitaSetores']);
Route::delete('setor/visita/{id}', [VisitaController::class, 'deleteVisitaSetores']);
Route::post('setor/visita/cadastro', [VisitaController::class, 'storeVisitaSetores']);
