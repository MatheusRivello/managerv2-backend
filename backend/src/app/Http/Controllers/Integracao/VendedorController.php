<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\VendedorService;

class VendedorController extends IntegracaoController
{
    public function __construct(VendedorService $integracao)
    {
        $this->integracao = $integracao;
    }
}
