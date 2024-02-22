<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\FilialService;

class FilialController extends IntegracaoController
{
    public function __construct(FilialService $integracao)
    {
        $this->integracao = $integracao;
    }
}
