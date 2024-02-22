<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\TipoPedidoService;

class TipoPedidoController extends IntegracaoController
{
    public function __construct(TipoPedidoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
