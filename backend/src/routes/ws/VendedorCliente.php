<?php

use App\Http\Controllers\externa\VendedorxClienteExternaController;
use Illuminate\Support\Facades\Route;

Route::get('vendedor/cliente', [VendedorxClienteExternaController::class, 'index']);
Route::get('vendedor/cliente/filtro', [VendedorxClienteExternaController::class, 'show']);
Route::post('vendedor/cliente/cadastro', [VendedorxClienteExternaController::class, 'StoreVendedorCliente']);
Route::delete('vendedor/cliente/destroy', [VendedorxClienteExternaController::class, 'destroyPersonalizado']);
