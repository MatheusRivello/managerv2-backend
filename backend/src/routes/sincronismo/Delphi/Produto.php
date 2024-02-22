<?php

use App\Http\Controllers\servico\v1\ERP\ProdutoestoqueServicoController;
use App\Http\Controllers\servico\v1\ERP\ProdutodesctoqtdServicoController;
use App\Http\Controllers\servico\v1\ERP\ProdutoembalagemServicoController;
use App\Http\Controllers\servico\v1\ERP\ProdutoipiServicoController;
use App\Http\Controllers\servico\v1\ERP\ProdutoStServicoController;
use App\Http\Controllers\servico\v1\ERP\ProdutotabelaitensServicoController;
use App\Http\Controllers\servico\v1\ERP\ProdutoServicoController;
use Illuminate\Support\Facades\Route;

Route::post('produtodesctoqtd/zip', [ProdutodesctoqtdServicoController::class, 'storeUpdate']);
Route::post('produtoembalagem/zip', [ProdutoembalagemServicoController::class, 'storeUpdate']);
Route::post('produtoestoque/zip', [ProdutoestoqueServicoController::class, 'storeUpdate']);
Route::post('produtoestoque/estoqueupdatejson', [ProdutoestoqueServicoController::class, 'atualizarDadosJSON']);
Route::post('produtoipi/zip', [ProdutoipiServicoController::class, 'storeUpdate']);
Route::post('produtost/zip', [ProdutoStServicoController::class, 'storeUpdate']);
Route::post('produtotabelaitens/zip', [ProdutotabelaitensServicoController::class, 'storeUpdate']);
Route::post('produto/zip', [ProdutoServicoController::class, 'storeUpdate']);

Route::post('produto/json', [ProdutoServicoController::class, 'atualizarDadosJSON']);
Route::post('produtotabelaitens/json', [ProdutotabelaitensServicoController::class, 'atualizarDadosJSON']);
Route::post('produtost/json', [ProdutoStServicoController::class, 'atualizarDadosJSON']);
Route::post('produtoipi/json', [ProdutoipiServicoController::class, 'atualizarDadosJSON']);
Route::post('produtoestoque/json', [ProdutoestoqueServicoController::class, 'atualizarDadosJSON']);
