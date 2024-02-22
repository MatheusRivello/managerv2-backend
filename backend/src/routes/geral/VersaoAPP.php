<?php

use App\Http\Controllers\api\v1\Central\VersaoAppController;
use Illuminate\Support\Facades\Route;

Route::get('aplicativo/versao', [VersaoAppController::class, 'index'])->name('versao_app');
Route::get('aplicativo/versao/{codigo_versao}', [VersaoAppController::class, 'show']);
Route::post('aplicativo/versao', [VersaoAppController::class, 'store']);
Route::patch('aplicativo/versao/{codigo_versao}', [VersaoAppController::class, 'update']);
Route::delete('aplicativo/versao/{codigo_versao}', [VersaoAppController::class, 'destroy']);
