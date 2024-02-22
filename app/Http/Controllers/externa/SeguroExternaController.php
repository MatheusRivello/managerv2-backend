<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;


use App\Models\Tenant\Seguro;
use App\Services\BaseService;


class SeguroExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Seguro::class;
        $this->filters = ['valor', 'uf'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_SEGURO_EXTERNA;
        $this->firstOrNew = ['valor', 'uf'];
        $this->fields = [
            'valor',
            'uf',
            'dt_modificado'
        ];
    }
}
