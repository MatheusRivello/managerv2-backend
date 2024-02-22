<?php

use App\Http\Controllers\servico\v1\ERP\EmpresaServicoController;
use Illuminate\Support\Facades\Route;

Route::get('empresa/existeatualizacao', [EmpresaServicoController::class, 'verificaAtualizacao']);
Route::post('empresa/existeatualizacao', [EmpresaServicoController::class, 'atualizarEmpresa']);

