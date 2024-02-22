<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoEstoqueService;

class ProdutoEstoqueController extends IntegracaoController
{
    public function __construct(ProdutoEstoqueService $integracao)
    {
        $this->integracao = $integracao;
    }
}
