<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\StatusClienteService;

class StatusClienteController extends IntegracaoController
{
    public function __construct(StatusClienteService $integracao)
    {
        $this->integracao = $integracao;
    }
}
