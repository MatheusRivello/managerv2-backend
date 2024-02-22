<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\VendaPlanoService;

class VendaPlanoController extends IntegracaoController
{
    public function __construct(VendaPlanoService $integracao)
    {
        $this->integracao = $integracao;
    }
}