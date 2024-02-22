<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\TituloFinanceiroService;

class TituloFinanceiroController extends IntegracaoController
{
    public function __construct(TituloFinanceiroService $integracao)
    {
        $this->integracao = $integracao;
    }
}
