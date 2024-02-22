<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Meta;
use App\Services\BaseService;


class MetaExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Meta::class;
        $this->filters = ['id_filial', 'id_vendedor', 'id_retaguarda'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_META_EXTERNA;
        $this->firstOrNew = ['id_filial', 'id_vendedor', 'id_retaguarda'];
        $this->fields = [
            'id_filial',
            'id_vendedor',
            'id_retaguarda',
            'descricao',
            'tot_qtd_ven',
            'tot_peso_ven',
            'objetivo_vendas',
            'tot_val_ven',
            'percent_atingido'
        ];
    }
}
