<?php

use App\Http\Controllers\externa\FornecedorExternaController;
use Illuminate\Support\Facades\Route;

Route::get('fornecedor', [FornecedorExternaController::class, 'index'])->name('fornecedorWS');
Route::get('fornecedor/filtro', [FornecedorExternaController::class, 'show']);
Route::delete('fornecedor/{id}', [FornecedorExternaController::class, 'destroy']);
Route::post('fornecedor/cadastro', [FornecedorExternaController::class, 'storeFornecedor']);