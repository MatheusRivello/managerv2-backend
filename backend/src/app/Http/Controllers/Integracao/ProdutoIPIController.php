<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoIPIService;

class ProdutoIPIController extends IntegracaoController
{
    public function __construct(ProdutoIPIService $integracao)
    {
        $this->integracao = $integracao;
    }
}