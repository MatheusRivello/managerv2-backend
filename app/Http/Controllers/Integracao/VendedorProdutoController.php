<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\VendedorProdutoService;

class VendedorProdutoController extends IntegracaoController
{
    public function __construct(VendedorProdutoService $integracao)
    {
        $this->integracao = $integracao;
    }
}