<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\StatusPedidoService;

class StatusPedidoController extends IntegracaoController
{
    public function __construct(StatusPedidoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
