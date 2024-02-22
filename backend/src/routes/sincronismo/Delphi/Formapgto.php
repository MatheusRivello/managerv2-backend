<?php

use App\Http\Controllers\servico\v1\ERP\FormaPagamentoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('formapgto', [FormaPagamentoServicoController::class, 'storeUpdate']);
Route::post('formapgto/json', [FormaPagamentoServicoController::class, 'atualizarDadosJSON']);
