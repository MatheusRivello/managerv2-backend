<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ConfigFilialService;

class ConfigFilialController extends IntegracaoController
{
    public function __construct(ConfigFilialService $integracao)
    {
        $this->integracao = $integracao;
    }
}