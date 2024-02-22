<?php

namespace App\Http\Controllers\Integracao;

use App\Http\Controllers\Controller;
use App\Services\Integracao\IntegracaoService;

abstract class IntegracaoController extends Controller
{
    /**
     * @var IntegracaoService
     */
    protected $integracao;

    public function request() {
        return json_encode($this->integracao->request()->getLog());
    }
}
