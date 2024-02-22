<?php

use App\Http\Controllers\mobile\v1\comum\EstoqueClienteMobileController;
use Illuminate\Support\Facades\Route;

Route::post('estoque_cliente/inserir', [EstoqueClienteMobileController::class, 'inserir']);
