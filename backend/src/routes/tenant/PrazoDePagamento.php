<?php

use App\Http\Controllers\api\v1\Tenant\PrazoDePagamentoController;
use Illuminate\Support\Facades\Route;

Route::get('prazo-pagamento', [PrazoDePagamentoController::class, 'getAll']);
