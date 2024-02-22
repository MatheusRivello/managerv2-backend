<?php

use App\Http\Controllers\api\v1\Central\SincronismoInternoController;
use Illuminate\Support\Facades\Route;

Route::get('integração/interna', [SincronismoInternoController::class, 'index']);
Route::post('integração/interna', [SincronismoInternoController::class, 'store']);
