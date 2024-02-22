<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoEmbalagemService;

class ProdutoEmbalagemController extends IntegracaoController
{
    public function __construct(ProdutoEmbalagemService $integracao)
    {
        $this->integracao = $integracao;
    }
}