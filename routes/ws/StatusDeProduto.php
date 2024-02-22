<?php

use App\Http\Controllers\externa\StatusDeProdutoExternaController;
use Illuminate\Support\Facades\Route;

Route::get('statusdeproduto', [StatusDeProdutoExternaController::class, 'index'])->name('statusDeProdutoWS');
Route::get('statusdeproduto/filtro', [StatusDeProdutoExternaController::class, 'show']);
Route::delete('statusdeproduto/{id}', [StatusDeProdutoExternaController::class, 'destroy']);
Route::post('statusdeproduto/cadastro', [StatusDeProdutoExternaController::class, 'store']);