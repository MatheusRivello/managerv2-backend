<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\MotivoService;

class MotivoController extends IntegracaoController
{
    public function __construct(MotivoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
