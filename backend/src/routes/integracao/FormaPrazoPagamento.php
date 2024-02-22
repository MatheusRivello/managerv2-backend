<?php

use App\Http\Controllers\Integracao\FormaPrazoPagamentoController;
use Illuminate\Support\Facades\Route;

    Route::get('forma/prazo/pagamento', [FormaPrazoPagamentoController::class, 'request']);