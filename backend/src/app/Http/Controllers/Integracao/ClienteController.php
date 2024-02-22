<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ClienteService;

class ClienteController extends IntegracaoController
{
    public function __construct(ClienteService $integracao)
    {
        $this->integracao = $integracao;
    }
}
