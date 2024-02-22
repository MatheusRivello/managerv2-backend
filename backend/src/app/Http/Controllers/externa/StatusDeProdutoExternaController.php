<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\StatusProduto;
use App\Services\BaseService;

class StatusDeProdutoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = StatusProduto::class;
        $this->filters = ['id', 'id_retaguarda', 'descricao', 'status'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath =RULE_STATUS_DE_PRODUTO_EXTERNA;
        $this->firstOrNew = ['id', 'id_retaguarda', 'descricao'];
        $this->fields = [
            'id_retaguarda',
            'descricao',
            'cor',
            'status'
        ];
    }
}
