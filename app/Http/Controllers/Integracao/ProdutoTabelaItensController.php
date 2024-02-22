<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoTabelaItensService;

class ProdutoTabelaItensController extends IntegracaoController
{
    public function __construct(ProdutoTabelaItensService $integracao)
    {
        $this->integracao = $integracao;
    }
}