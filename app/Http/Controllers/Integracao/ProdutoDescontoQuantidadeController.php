<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoDescontoQuantidadeService;

class ProdutoDescontoQuantidadeController extends IntegracaoController
{
    public function __construct(ProdutoDescontoQuantidadeService $integracao)
    {
        $this->integracao = $integracao;
    }
}