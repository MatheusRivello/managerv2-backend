<?php

use App\Http\Controllers\externa\VendedorExternaController;
use Illuminate\Support\Facades\Route;

Route::get('vendedor', [VendedorExternaController::class, 'index']);
Route::get('vendedor/filtro', [VendedorExternaController::class, 'show']);
Route::post('vendedor/cadastro', [VendedorExternaController::class, 'storePedidoItem']);
Route::delete('vendedor/{id}', [VendedorExternaController::class, 'destroy']);
