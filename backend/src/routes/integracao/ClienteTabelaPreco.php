<?php

use App\Http\Controllers\Integracao\ClienteTabelaPrecoController;
use Illuminate\Support\Facades\Route;

Route::get('cliente/tabela/preco', [ClienteTabelaPrecoController::class, 'request']);