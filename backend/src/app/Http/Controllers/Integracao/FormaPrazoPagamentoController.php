<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\FormaPrazoPagamentoService;

class FormaPrazoPagamentoController extends IntegracaoController
{
    public function __construct(FormaPrazoPagamentoService $integracao)
    {
        $this->integracao = $integracao;
    }
}