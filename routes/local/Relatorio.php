<?php

use App\Http\Controllers\api\v1\Config\RelatorioController;
use Illuminate\Support\Facades\Route;

    Route::get('relatorio', [RelatorioController::class, 'index'])->name('relatorio');
    Route::get('listarelatorios', [RelatorioController::class, 'listarRelatorios']);
    Route::get('relatorio/{id}', [RelatorioController::class, 'show']);
    Route::post('relatorio', [RelatorioController::class, 'store']);
    Route::get('relatorio/view/{path}', [RelatorioController::class, 'showRelatorioPath']);
    Route::delete('relatorio/{id}', [RelatorioController::class, 'destroy']);