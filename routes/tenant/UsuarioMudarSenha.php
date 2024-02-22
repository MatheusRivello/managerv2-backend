<?php

use App\Http\Controllers\api\v1\Central\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::patch('usuario/modificarSenha', [UsuarioController::class, 'mudarSenha']);
