<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\IndicadorMargem;
use App\Services\BaseService;

class IndicadorDeMargemExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = IndicadorMargem::class;
        $this->filters = ['id', 'id_filial'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_INDICADOR_DE_MARGEM_EXTERNA;
        $this->firstOrNew = ['id_filial', 'nivel', 'de'];
        $this->fields = [
            'id_filial',
            'nivel',
            'de',
            'ate',
            'indice'
        ];
    }
}
