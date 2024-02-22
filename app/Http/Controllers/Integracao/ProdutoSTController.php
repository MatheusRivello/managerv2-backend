<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoSTService;

class ProdutoSTController extends IntegracaoController
{
    public function __construct(ProdutoSTService $integracao)
    {
        $this->integracao = $integracao;
    }
}