<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;

use App\Models\Tenant\Regiao;
use App\Services\BaseService;


class RegiaoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Regiao::class;
        $this->filters = ['descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_REGIAO_EXTERNA;
        $this->firstOrNew = ['descricao'];
        $this->fields = [
            'id',
            'descricao'
        ];
    }
}
