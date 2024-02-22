<?php

use App\Http\Controllers\externa\SubGrupoExternaController;
use Illuminate\Support\Facades\Route;


Route::get('subgrupo', [SubGrupoExternaController::class, 'index']);
Route::get('subgrupo/filtro', [SubGrupoExternaController::class, 'show']);
Route::delete('subgrupo/{id}', [SubGrupoExternaController::class, 'destroy']);
Route::post('subgrupo/cadastro', [SubGrupoExternaController::class, 'storeSubGrupo']);