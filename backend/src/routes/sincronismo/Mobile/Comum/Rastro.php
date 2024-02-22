<?php

use App\Http\Controllers\mobile\v1\comum\RastroMobileController;
use Illuminate\Support\Facades\Route;

Route::post('rastro/rastrojson', [RastroMobileController::class, 'rastrojson']);
