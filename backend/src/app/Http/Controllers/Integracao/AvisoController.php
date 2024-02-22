<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\AvisoService;

class AvisoController extends IntegracaoController
{
    public function __construct(AvisoService $integracao)
    {
        $this->integracao = $integracao;
    }
}