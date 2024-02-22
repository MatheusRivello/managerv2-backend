<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\PrazoPagamentoService;

class PrazoPagamentoController extends IntegracaoController
{
    public function __construct(PrazoPagamentoService $integracao)
    {
        $this->integracao = $integracao;
    }
}