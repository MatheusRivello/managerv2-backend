<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Motivo;
use App\Services\BaseService;

class MotivoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Motivo::class;
        $this->filters = ['id', 'id_filial', 'id_retaguarda', 'descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_MOTIVO_API_EXTERNA;
        $this->firstOrNew = ['id', 'id_filial', 'id_retaguarda', 'descricao'];
        $this->fields = [
            'id',
            'id_filial',
            'id_retaguarda',
            'descricao',
            'tipo',
            'status'
        ];
    }
}
