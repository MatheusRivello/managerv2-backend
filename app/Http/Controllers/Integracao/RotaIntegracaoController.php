<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\RotaService;

class RotaIntegracaoController extends IntegracaoController
{
    public function __construct(RotaService $integracao)
    {
        $this->integracao = $integracao;
    }
}
