<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Atividade;
use App\Services\BaseService;


class ClienteAtividadeExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Atividade::class;
        $this->filters = ['id_retaguarda', 'descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath =RULE_CLIENTE_ATIVIDADE_EXTERNA;
        $this->firstOrNew = ['id','id_filial', 'id_retaguarda'];
        $this->fields = [
            'id_filial',
            'id_retaguarda',
            'descricao',
            'status'
        ];
    }
}
