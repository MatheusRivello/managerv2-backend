<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\NotaFiscalItemService;

class NotaFiscalItemController extends IntegracaoController
{
    public function __construct(NotaFiscalItemService $integracao)
    {
        $this->integracao = $integracao;
    }
}
