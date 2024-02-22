<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ClienteTabelaGrupoService;

class ClienteTabelaGrupoController extends IntegracaoController
{
    public function __construct(ClienteTabelaGrupoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
