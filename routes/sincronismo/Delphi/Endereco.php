<?php

use App\Http\Controllers\servico\v1\ERP\EnderecoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('endereco', [EnderecoServicoController::class, 'storeUpdate']);
Route::post('endereco/json', [EnderecoServicoController::class, 'atualizarDadosJSON']);