<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ClienteTabelaPrecoService;

class ClienteTabelaPrecoController extends IntegracaoController
{
    public function __construct(ClienteTabelaPrecoService $integracao)
    {
        $this->integracao = $integracao;
    }
}