<?php

use App\Http\Controllers\externa\MetaDetalheExternaController;
use Illuminate\Support\Facades\Route;

Route::get('metadetalhe', [MetaDetalheExternaController::class, 'index']);
Route::get('metadetalhe/filtro', [MetaDetalheExternaController::class, 'show']);
Route::post('metadetalhe/cadastro', [MetaDetalheExternaController::class, 'store']);
Route::delete('metadetalhe/{id}', [MetaDetalheExternaController::class, 'destroy']);
