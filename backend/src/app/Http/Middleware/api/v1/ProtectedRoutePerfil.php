<?php

namespace App\Http\Middleware\api\v1;

use App\Services\Middlewares\PerfilMiddlewareService;
use Closure;
use Exception;
use Illuminate\Http\Request;

class ProtectedRoutePerfil
{
    public function __construct()
    {
        $this->service = new PerfilMiddlewareService;
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            $this->service->getPermissao($request);
            return $next($request);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
