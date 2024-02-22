<?php

use App\Http\Controllers\api\v1\Central\StatusServicoController;
use Illuminate\Support\Facades\Route;

Route::get('status/servico', [StatusServicoController::class, 'index'])->name('statusServico');
