<?php

use App\Http\Controllers\externa\GrupoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('grupo', [GrupoExternaController::class, 'index']);
Route::get('grupo/filtro', [GrupoExternaController::class, 'show']);
Route::delete('grupo/{id}', [GrupoExternaController::class, 'destroy']);
Route::post('grupo/cadastro', [GrupoExternaController::class, 'storeGrupo']);