<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\VendaPlanoProdutoService;

class VendaPlanoProdutoController extends IntegracaoController
{
    public function __construct(VendaPlanoProdutoService $integracao)
    {
        $this->integracao = $integracao;
    }
}