<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\VisitaService;

class VisitaController extends IntegracaoController
{
    public function __construct(VisitaService $integracao)
    {
        $this->integracao = $integracao;
    }
}
