<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\IndicadorMargemService;

class IndicadorMargemController extends IntegracaoController
{
    public function __construct(IndicadorMargemService $integracao)
    {
        $this->integracao = $integracao;
    }
}
