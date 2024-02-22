<?php

use App\Http\Controllers\externa\ClienteTabelaGrupoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('clientetabelagrupo', [ClienteTabelaGrupoExternaController::class, 'index']);
Route::get('clientetabelagrupo/filtro', [ClienteTabelaGrupoExternaController::class, 'show']);
Route::post('clientetabelagrupo/cadastro', [ClienteTabelaGrupoExternaController::class, 'storePersonalizado']);
Route::delete('clientetabelagrupo/{id}', [ClienteTabelaGrupoExternaController::class, 'destroy']);
