<?php

use App\Http\Controllers\api\v1\Tenant\FilialTenantController;
use Illuminate\Support\Facades\Route;

Route::get('filialtenant', [FilialTenantController::class, 'index'])->name('filialTenant');
Route::get('filialtenant/filtrar/{id}', [FilialTenantController::class, 'show']);
Route::post('filialtenant', [FilialTenantController::class, 'store']);
