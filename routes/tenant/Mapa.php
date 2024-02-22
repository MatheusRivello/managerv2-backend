<?php

use App\Http\Controllers\api\v1\Tenant\MapaController;
use Illuminate\Support\Facades\Route;

Route::get('mapas/rastrear', [MapaController::class, 'localizar'])->name('mapaTenant');