<?php

use App\Http\Controllers\api\v1\Tenant\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::get('produto', [ProdutoController::class, 'getListaProdutos'])->name('produtoTenant');
