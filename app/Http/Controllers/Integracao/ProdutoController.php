<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoService;

class ProdutoController extends IntegracaoController
{
    public function __construct(ProdutoService $integracao)
    {
        $this->integracao = $integracao;
    }
}