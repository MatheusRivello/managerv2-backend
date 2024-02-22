<?php

use App\Http\Controllers\api\v1\Tenant\TabelaPrecoController;
use Illuminate\Support\Facades\Route;

Route::get('tabela-preco', [TabelaPrecoController::class, 'getAll']);
