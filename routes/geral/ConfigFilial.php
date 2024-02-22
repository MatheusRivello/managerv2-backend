<?php

use App\Http\Controllers\api\v1\Tenant\Config\ConfigFilialController;
use Illuminate\Support\Facades\Route;

Route::get('filial/tenant/config', [ConfigFilialController::class, 'indexEmpresaEspecifica'])->name('configFilial');
Route::get('filial/tenant/config/{id}', [ConfigFilialController::class, 'showEmpresaEspecifica']);
Route::post('filial/tenant/config', [ConfigFilialController::class, 'storeEmpresaEspecifica']);
Route::delete('filial/tenant/config/{id}', [ConfigFilialController::class, 'destroyEmpresaEspecifica']);
Route::get('filial/all/config', [ConfigFilialController::class, 'indexEmpresas']);
Route::get('filial/all/config/{idEmpresa}', [ConfigFilialController::class, 'showEmpresas']);
Route::delete('filial/all/config', [ConfigFilialController::class, 'destroyEmpresas']);
