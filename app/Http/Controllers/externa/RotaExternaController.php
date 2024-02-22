<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;

use App\Models\Tenant\Rota;
use App\Services\BaseService;


class RotaExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Rota::class;
        $this->filters = ['id_filial', 'id_retaguarda', 'descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_ROTA_EXTERNA;
        $this->firstOrNew = ['id_filial', 'id_retaguarda', 'descricao'];
        $this->fields = [
            'id_filial',
            'id_retaguarda',
            'descricao',
            'rota_frete',
            'rota_tipo_frete'
        ];
    }
}
