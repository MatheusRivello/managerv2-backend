<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\CampanhaRequisitoService;

class CampanhaRequisitoController extends IntegracaoController
{
    public function __construct(CampanhaRequisitoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
