<?php

use App\Http\Controllers\api\v1\Tenant\MetaController;
use Illuminate\Support\Facades\Route;

Route::get('meta', [MetaController::class, 'getMeta'])->name('metaTenant');
Route::get('vendedor/meta', [MetaController::class, 'getVendedoresMeta']);
Route::get('vendedor/meta/detalhes', [MetaController::class, 'getMetaDetalhesVendedor']);
