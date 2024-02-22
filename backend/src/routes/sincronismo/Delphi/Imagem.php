<?php

use App\Http\Controllers\servico\v1\ERP\ImagemServicoController;
use Illuminate\Support\Facades\Route;

Route::post('imagem/upload', [ImagemServicoController::class, 'upload']);
