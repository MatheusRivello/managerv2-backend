<?php

namespace App\Http\Middleware\api\v1;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProtectedRouteAuth
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 'Token é inválido'], 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => 'O token está expirado'], 403);
            } else {
                return response()->json(['status' => 'Token de autorização não encontrado'], 404);
            }
        }
        return $next($request);
    }
}
