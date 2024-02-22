<?php

namespace App\Services\Middlewares;

use App\Services\BaseService;
use Exception;

class PerfilMiddlewareService extends BaseService
{
    public function getPermissao($request)
    {
        $urlPath = $this->getUrlAPI($request);
        $menusDoPerfil = collect($this->usuarioLogado()->perfil->url_api);
        $verificacao = $menusDoPerfil->firstWhere('url', $urlPath) ? true : false;

        if (!$verificacao) {
            throw new Exception("Você não possui permissão para acessar esta rota", 401);
        };
    }

    private function getUrlAPI($request)
    {
        return $request->path();
    }
}
