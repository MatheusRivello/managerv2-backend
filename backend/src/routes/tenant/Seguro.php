<?php

use App\Http\Controllers\api\v1\Tenant\SeguroController;
use Illuminate\Support\Facades\Route;

Route::get('seguros', [SeguroController::class, 'index'])->name('seguroTenant');
Route::get('seguros/{id}', [SeguroController::class, 'show']);
Route::patch('seguros/modificar', [SeguroController::class, 'update']);
Route::post('seguros', [SeguroController::class, 'store']);
Route::delete('seguros/{id}', [SeguroController::class, 'destroy']);
