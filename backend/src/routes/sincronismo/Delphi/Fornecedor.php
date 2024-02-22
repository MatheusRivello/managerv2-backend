<?php

use App\Http\Controllers\servico\v1\ERP\FornecedorServicoController;
use Illuminate\Support\Facades\Route;

Route::post('fornecedor', [FornecedorServicoController::class, 'storeUpdate']);
Route::post('fornecedor/fornecedorjson', [FornecedorServicoController::class, 'atualizarDadosJSON']);
