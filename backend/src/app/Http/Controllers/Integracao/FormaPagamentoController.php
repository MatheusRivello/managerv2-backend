<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\FormaPagamentoService;

class FormaPagamentoController extends IntegracaoController
{
    public function __construct(FormaPagamentoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
