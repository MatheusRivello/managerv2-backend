<?php

use App\Http\Controllers\api\v1\Central\Views\LicencasEmpresas;
use Illuminate\Support\Facades\Route;

Route::get('licencas', [LicencasEmpresas::class, 'index'])->name('licencas');
Route::get('licencas/{id}', [LicencasEmpresas::class, 'show']);
