<?php

use App\Http\Controllers\api\v1\Config\GrupoController;
use Illuminate\Support\Facades\Route;

Route::get('grupos', [GrupoController::class, 'index'])->name('grupos');
Route::post('grupos', [GrupoController::class, 'store']);
Route::get('grupos/{id}', [GrupoController::class, 'show']);
Route::delete('grupos/{id}', [GrupoController::class, 'destroy']);
