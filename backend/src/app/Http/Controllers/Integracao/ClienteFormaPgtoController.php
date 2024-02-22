<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ClienteFormaPgtoService;

class ClienteFormaPgtoController extends IntegracaoController
{
    public function __construct(ClienteFormaPgtoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
