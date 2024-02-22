<?php

use App\Http\Controllers\api\v1\Tenant\Config\PedidoWebController;
use Illuminate\Support\Facades\Route;

Route::get('pedidoweb/tenant/config', [PedidoWebController::class, 'indexEmpresaEspecifica'])->name('pedidoweb');
Route::get('pedidoweb/tenant/config/{id}', [PedidoWebController::class, 'showEmpresaEspecifica']);
Route::post('pedidoweb/tenant/config', [PedidoWebController::class, 'storeEmpresaEspecifica']);
Route::post('pedidoweb/tenant/array/config', [PedidoWebController::class, 'storeArrayEmpresaEspecifica']);
Route::delete('pedidoweb/tenant/config/{id}', [PedidoWebController::class, 'destroyEmpresaEspecifica']);
Route::get('pedidoweb/termo', [PedidoWebController::class, 'indexTermoPW']);
Route::patch('pedidoweb/termo', [PedidoWebController::class, 'storeTermoPW']);
Route::get('pedidoweb/all/config', [PedidoWebController::class, 'indexEmpresas']);
Route::get('pedidoweb/all/config/{idEmpresa}', [PedidoWebController::class, 'showEmpresas']);
Route::post('pedidoweb/all/config', [PedidoWebController::class, 'storeEmpresas']);
Route::delete('pedidoweb/all/config', [PedidoWebController::class, 'destroyEmpresas']);
