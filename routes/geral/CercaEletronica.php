<?php

use App\Http\Controllers\api\v1\Central\CercaEletronicaController;
use Illuminate\Support\Facades\Route;

Route::get('cercaeletronica', [CercaEletronicaController::class, 'index']);
Route::get('cercaeletronica/empresa', [CercaEletronicaController::class, 'indexEmpresa']);
Route::get('cercaeletronica/motivo', [CercaEletronicaController::class, 'indexMotivo']);
Route::get('cercaeletronica/vendedores', [CercaEletronicaController::class, 'countVenSolicitaSenha']);
Route::get('cercaeletronica/vendedores/pedidos', [CercaEletronicaController::class, 'countPedLiberadosSenha']);
Route::post('cercaeletronica', [CercaEletronicaController::class, 'store']);
Route::get('cercaeletronica/{id}', [CercaEletronicaController::class, 'show']);
Route::get('cercaeletronica/pedidos/liberados', [CercaEletronicaController::class, 'pedidosLiberadosPorSenha']);
