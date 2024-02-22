<?php
use App\Http\Controllers\Integracao\FornecedorController;
use Illuminate\Support\Facades\Route;

    Route::get('fornecedor', [FornecedorController::class, 'request']);
