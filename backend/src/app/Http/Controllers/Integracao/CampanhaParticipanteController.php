<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\CampanhaParticipanteService;

class CampanhaParticipanteController extends IntegracaoController
{
    public function __construct(CampanhaParticipanteService $integracao)
    {
        $this->integracao = $integracao;
    }
}
