<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\CampanhaBeneficioService;


class CampanhaBeneficioController extends IntegracaoController
{
    public function __construct(CampanhaBeneficioService $integracao)
    {
        $this->integracao = $integracao;
    }
}
