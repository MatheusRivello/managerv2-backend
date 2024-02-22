<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\VendedorProtabelaPrecoService;

class VendedorProtabelaPrecoController extends IntegracaoController
{
    public function __construct(VendedorProtabelaPrecoService $integracao)
    {
        $this->integracao = $integracao;
    }
}