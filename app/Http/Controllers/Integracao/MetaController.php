<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\MetaService;

class MetaController extends IntegracaoController
{
    public function __construct(MetaService $integracao)
    {
        $this->integracao = $integracao;
    }
}