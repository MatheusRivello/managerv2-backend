<?php

use App\Http\Controllers\api\v1\Central\PerfilController;
use Illuminate\Support\Facades\Route;

Route::get('perfil', [PerfilController::class, 'index'])->name('perfil');
Route::post('perfil', [PerfilController::class, 'store']);
Route::get('perfil/{id}', [PerfilController::class, 'show']);
Route::get('perfil/empresa/{id}', [PerfilController::class, 'showEmpresa']);
Route::get('perfil/menus/{id}', [PerfilController::class, 'showMenus']);
Route::get('perfil/api/rotas', [PerfilController::class, 'showApis']);
Route::delete('perfil/{id}', [PerfilController::class, 'destroy']);
Route::patch('perfil/{id}', [PerfilController::class, 'update']);
Route::get('perfil/usuario/{id}', [PerfilController::class, 'showUsersProfiles']);
