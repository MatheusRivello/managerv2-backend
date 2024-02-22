<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\MetaDetalheService;

class MetaDetalheController extends IntegracaoController
{
    public function __construct(MetaDetalheService $integracao)
    {
        $this->integracao = $integracao;
    }
}