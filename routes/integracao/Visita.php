<?php

use App\Http\Controllers\Integracao\VisitaController;
use Illuminate\Support\Facades\Route;

Route::get('visita', [VisitaController::class, 'request']);
