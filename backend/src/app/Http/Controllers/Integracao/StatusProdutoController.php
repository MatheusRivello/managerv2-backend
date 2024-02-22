<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\StatusProdutoService;

class StatusProdutoController extends IntegracaoController
{
    public function __construct(StatusProdutoService $integracao)
    {
        $this->integracao = $integracao;
    }
}