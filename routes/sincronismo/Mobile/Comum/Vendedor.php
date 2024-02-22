<?php

use App\Http\Controllers\mobile\v1\comum\VendedorMobileController;
use Illuminate\Support\Facades\Route;

Route::post('vendedor/vendedor', [VendedorMobileController::class, 'vendedor']);
