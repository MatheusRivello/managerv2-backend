<?php

use App\Http\Controllers\api\v1\Tenant\ClienteController;
use Illuminate\Support\Facades\Route;

Route::get('clientes', [ClienteController::class, 'findClients']);