<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoSubGrupoService;

class ProdutoSubGrupoController extends IntegracaoController
{
    public function __construct(ProdutoSubGrupoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
