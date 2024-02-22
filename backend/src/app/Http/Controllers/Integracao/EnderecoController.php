<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\EnderecoService;

class EnderecoController extends IntegracaoController
{
    public function __construct(EnderecoService $integracao)
    {
        $this->integracao = $integracao;
    }
}