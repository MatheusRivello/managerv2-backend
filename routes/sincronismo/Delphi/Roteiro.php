<?php

use App\Http\Controllers\servico\v1\ERP\RoteiroServicoController;
use Illuminate\Support\Facades\Route;

Route::post('roteiro/novo', [RoteiroServicoController::class, 'novo']);