<?php

use App\Http\Controllers\api\v1\Tenant\VendedoresController;
use Illuminate\Support\Facades\Route;

Route::get('vendedor', [VendedoresController::class, 'getVendedors']);
