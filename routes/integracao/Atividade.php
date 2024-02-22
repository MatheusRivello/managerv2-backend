<?php

use App\Http\Controllers\Integracao\AtividadeController;

use Illuminate\Support\Facades\Route;

Route::get('atividade', [AtividadeController::class, 'request']);