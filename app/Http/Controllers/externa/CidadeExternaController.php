<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Cidade;
use App\Services\BaseService;

class CidadeExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Cidade::class;
        $this->filters = ['id', 'uf', 'descricao', 'codigo_ibge', 'ddd'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CIDADE_EXTERNA;
        $this->firstOrNew = ['id'];
        $this->fields = [
            'id',
            'uf',
            'descricao',
            'codigo_ibge',
            'ddd'
        ];
    }
}
