<?php

use App\Http\Controllers\api\v1\Tenant\TituloFinanceiroController;
use Illuminate\Support\Facades\Route;

Route::get('financeiro/lista', [TituloFinanceiroController::class, 'getTituloFinanceiro'])->name('tituloFinanceiroTenant');
Route::get('financeiro/totalhead', [TituloFinanceiroController::class, 'totalHead']);