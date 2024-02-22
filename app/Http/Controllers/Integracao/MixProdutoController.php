<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\MixProdutoService;

class MixProdutoController extends IntegracaoController
{
    public function __construct(MixProdutoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
