<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\AtividadeService;

class AtividadeController extends IntegracaoController
{
    public function __construct(AtividadeService $integracao)
    {
        $this->integracao = $integracao;
    }
}