<?php

use App\Http\Controllers\api\v1\Central\AcessosController;
use Illuminate\Support\Facades\Route;

    Route::get('acessos/api', [AcessosController::class, 'indexAPI'])->name('acesso_api');
    Route::get('acessos/api/{id}', [AcessosController::class, 'showAPI']);
    Route::post('acessos/api', [AcessosController::class, 'storeUpdateAPI']);
    Route::delete('acessos/api/{id}', [AcessosController::class, 'destroyAPI']);
    Route::get('acessos/menu', [AcessosController::class, 'indexMENU'])->name('acesso_menu');
    Route::get('acessos/menu/{id}', [AcessosController::class, 'showMENU']);
    Route::post('acessos/menu', [AcessosController::class, 'storeUpdateMENU']);
    Route::delete('acessos/menu/{id}', [AcessosController::class, 'destroyMENU']);
    Route::get('acessos/arvore/menu', [AcessosController::class, 'showArvoreMENU']);
