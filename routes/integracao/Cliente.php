<?php

use App\Http\Controllers\Integracao\ClienteController;
use App\Http\Controllers\Integracao\ClienteFormaPgtoController;
use App\Http\Controllers\Integracao\ClienteTabelaGrupoController;
use App\Http\Controllers\Integracao\ContatoController;
use App\Http\Controllers\Integracao\EnderecoController;
use App\Http\Controllers\Integracao\StatusClienteController;
use App\Http\Controllers\Integracao\TituloFinanceiroController;
use Illuminate\Support\Facades\Route;

Route::prefix('cliente')->group(function () {
    Route::get('', [ClienteController::class, 'request']);
    Route::get('/formapgto', [ClienteFormaPgtoController::class, 'request']);
    Route::get('/tabelagrupo', [ClienteTabelaGrupoController::class, 'request']);
    Route::get('/endereco', [EnderecoController::class, 'request']);
    Route::get('/contato', [ContatoController::class, 'request']);
    Route::get('/status', [StatusClienteController::class, 'request']);
    Route::get('/titulo', [TituloFinanceiroController::class, 'request']);
});
