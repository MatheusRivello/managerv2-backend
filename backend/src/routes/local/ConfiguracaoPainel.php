<?php

use App\Http\Controllers\api\v1\Config\ConfigManagerController;
use Illuminate\Support\Facades\Route;

Route::get('tipoempresa', [ConfigManagerController::class, 'tipoEmpresa']);
Route::post('tipoempresa', [ConfigManagerController::class, 'storeTipoEmpresa']);
Route::delete('tipoempresa/{id}', [ConfigManagerController::class, 'destroyTipoEmpresa']);
Route::get('tipopermissao', [ConfigManagerController::class, 'tipoPermissao']);
Route::post('tipopermissao', [ConfigManagerController::class, 'storeTipoPermissao']);
Route::delete('tipopermissao/{id}', [ConfigManagerController::class, 'destroyTipoPermissao']);
