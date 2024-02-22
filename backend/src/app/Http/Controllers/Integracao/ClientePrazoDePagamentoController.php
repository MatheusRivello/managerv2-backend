<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ClientePrazoPgtoService;

class ClientePrazoDePagamentoController extends IntegracaoController
{
    public function __construct(ClientePrazoPgtoService $integracao)
    {
        $this->integracao = $integracao;
    }
}