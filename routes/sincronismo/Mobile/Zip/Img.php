<?php

use App\Http\Controllers\mobile\v1\zip\ImgMobileController;
use Illuminate\Support\Facades\Route;

Route::get('img/img/{idProduto?}', [ImgMobileController::class, 'img']);
