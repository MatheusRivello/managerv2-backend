<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\NotaFiscalService;

class NotaFiscalController extends IntegracaoController
{
    public function __construct(NotaFiscalService $integracao)
    {
        $this->integracao = $integracao;
    }
}
