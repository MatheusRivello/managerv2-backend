<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\ProdutoEmbalagem;
use App\Services\BaseService;

class ProdutoEmbalagemExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ProdutoEmbalagem::class;
        $this->filters = ['id_produto', 'unidade', 'embalagem'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_PRODUTO_EMBALAGEM_EXTERNA;
        $this->firstOrNew = ['id', 'id_produto', 'unidade', 'embalagem'];
        $this->fields = [
            'id_produto',
            'unidade',
            'embalagem',
            'fator',
            'status'
        ];
    }
}
