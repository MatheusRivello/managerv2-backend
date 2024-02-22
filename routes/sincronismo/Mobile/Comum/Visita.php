<?php

use App\Http\Controllers\mobile\v1\comum\VisitaMobileController;
use Illuminate\Support\Facades\Route;

Route::post('visita/retornavisita', [VisitaMobileController::class, 'retornavisita']);
Route::post('visita/visitajson', [VisitaMobileController::class, 'visitajson']);
