<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;

use App\Models\Tenant\ProdutoEstoque;
use App\Services\BaseService;


class ProdutoEstoqueExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ProdutoEstoque::class;
        $this->filters = ['id_produto'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_PRODUTO_ESTOQUE_EXTERNA;
        $this->firstOrNew = ['id_produto'];
        $this->fields = [
            'id_produto',
            'unidade',
            'quantidade',
            'dt_modificado'
        ];
    }
}
