<?php

use App\Http\Controllers\mobile\v1\comum\ProdutoImagemMobileController;
use Illuminate\Support\Facades\Route;

Route::post('produtoimagem/produtoimagem', [ProdutoImagemMobileController::class, 'produtoimagem']);
Route::get('produtoimagem/download', [ProdutoImagemMobileController::class, 'baixarImagem']);
