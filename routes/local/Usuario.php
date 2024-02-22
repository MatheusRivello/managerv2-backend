<?php

use App\Http\Controllers\api\v1\Central\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('usuario', [UsuarioController::class, 'index'])->name('usuario');
Route::get('usuario/sig2000', [UsuarioController::class, 'indexSIG']);
Route::post('usuario', [UsuarioController::class, 'store']);
Route::get('usuario/{id}', [UsuarioController::class, 'show']);
Route::patch('usuario/{id}', [UsuarioController::class, 'update']);
Route::delete('usuario/{id}', [UsuarioController::class, 'destroy']);
