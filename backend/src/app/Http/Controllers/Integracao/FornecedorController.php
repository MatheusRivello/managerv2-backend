<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\FornecedorService;

class FornecedorController extends IntegracaoController
{
    public function __construct(FornecedorService $integracao)
    {
        $this->integracao = $integracao;
    }
}
