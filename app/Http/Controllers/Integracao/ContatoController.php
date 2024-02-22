<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ContatoService;

class ContatoController extends IntegracaoController
{
    public function __construct(ContatoService $integracao)
    {
        $this->integracao = $integracao;
    }
}