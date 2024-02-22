<?php

use App\Http\Controllers\servico\v1\ERP\ServicoLocalServicoController;
use Illuminate\Support\Facades\Route;

Route::post('servicolocal/servicolocal', [ServicoLocalServicoController::class, 'servicolocal']);

