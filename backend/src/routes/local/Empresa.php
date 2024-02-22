<?php

use App\Http\Controllers\api\v1\Central\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::get('empresa', [EmpresaController::class, 'index'])->name('empresa');
Route::get('empresa/{id}', [EmpresaController::class, 'show']);
Route::get('empresa/{id}/dispositivos', [EmpresaController::class, 'dispositivos']);
Route::get('empresa/{id}/usuarios', [EmpresaController::class, 'usuarios']);
Route::get('conexao/empresa', [EmpresaController::class, 'dadosOpenConexao']);
Route::post('empresa', [EmpresaController::class, 'store']);
Route::patch('empresa/{id}', [EmpresaController::class, 'update']);
Route::delete('empresa/{id}', [EmpresaController::class, 'destroy']);