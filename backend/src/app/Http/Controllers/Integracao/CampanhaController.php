<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\CampanhaService;

class CampanhaController extends IntegracaoController
{
    public function __construct(CampanhaService $integracao)
    {
        $this->integracao = $integracao;
    }
}
