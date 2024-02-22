<?php

use App\Http\Controllers\servico\v1\ERP\FormaPrazoPgtoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('formaprazopgto', [FormaPrazoPgtoServicoController::class, 'storeUpdate']);
Route::post('formaprazopgto/json', [FormaPrazoPgtoServicoController::class, 'atualizarDadosJSON']);
