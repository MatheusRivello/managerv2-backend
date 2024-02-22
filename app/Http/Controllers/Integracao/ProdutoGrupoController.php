<?php

namespace App\Http\Controllers\Integracao;

use App\Services\Integracao\ProdutoGrupoService;

class ProdutoGrupoController extends IntegracaoController
{
    public function __construct(ProdutoGrupoService $integracao)
    {
        $this->integracao = $integracao;
    }
}
