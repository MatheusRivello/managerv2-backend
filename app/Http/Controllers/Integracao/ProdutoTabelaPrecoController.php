<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoTabelaPrecoService;

class ProdutoTabelaPrecoController extends IntegracaoController
{
    public function __construct(ProdutoTabelaPrecoService $integracao)
    {
        $this->integracao = $integracao;
    }
}