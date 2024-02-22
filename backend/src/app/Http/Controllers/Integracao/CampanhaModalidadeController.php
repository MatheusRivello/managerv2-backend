<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\CampanhaModalidadeService;

class CampanhaModalidadeController extends IntegracaoController
{
    public function __construct(CampanhaModalidadeService $integracao)
    {
        $this->integracao = $integracao;
    }
}