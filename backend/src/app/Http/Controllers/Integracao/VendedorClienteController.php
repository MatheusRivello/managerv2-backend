<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\VendedorClienteService;

class VendedorClienteController extends IntegracaoController
{
    public function __construct(VendedorClienteService $integracao)
    {
        $this->integracao = $integracao;
    }
}