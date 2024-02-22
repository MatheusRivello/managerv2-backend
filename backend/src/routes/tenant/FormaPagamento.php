<?php

use App\Http\Controllers\api\v1\Tenant\FormaPagamentoController;
use Illuminate\Support\Facades\Route;

Route::get('formapagamento', [FormaPagamentoController::class, 'getListaFormasPagamento'])->name('formapgtoTenant');
