<?php

use App\Http\Controllers\Integracao\ClientePrazoDePagamentoController;
use Illuminate\Support\Facades\Route;

Route::get('cliente/prazodepagamento', [ClientePrazoDePagamentoController::class, 'request']);