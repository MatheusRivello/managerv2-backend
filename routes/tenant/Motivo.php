<?php

use App\Http\Controllers\api\v1\Tenant\MotivoController;
use Illuminate\Support\Facades\Route;

Route::get('motivo', [MotivoController::class, 'index']);