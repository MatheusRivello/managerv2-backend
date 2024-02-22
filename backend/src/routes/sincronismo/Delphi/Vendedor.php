<?php

use App\Http\Controllers\servico\v1\ERP\VendedorClienteServicoController;
use App\Http\Controllers\servico\v1\ERP\VendedorPrazopgtoServicoController;
use App\Http\Controllers\servico\v1\ERP\VendedorServicoController;
use App\Http\Controllers\servico\v1\ERP\VendedorTabelaPrecoServicoController;
use Illuminate\Support\Facades\Route;

//Vendedor
Route::post('vendedor/zip', [VendedorServicoController::class, 'storeUpdate']);
Route::post('vendedor/json', [VendedorServicoController::class, 'atualizarDadosJSON']);
Route::post('vendedorcliente/json', [VendedorClienteServicoController::class, 'atualizarDadosJSON']);
Route::post('vendedorprotabelapreco/json', [VendedorTabelaPrecoServicoController::class, 'atualizarDadosJSON']);
Route::post('vendedorprazo/json', [VendedorPrazopgtoServicoController::class, 'atualizarDadosJSON']);