<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\StatusCliente;
use App\Services\BaseService;

class StatusDeClienteExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = StatusCliente::class;
        $this->filters = ['id', 'id_retaguarda', 'descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_STATUS_DE_CLIENTE_EXTERNA;
        $this->firstOrNew = ['id', 'id_retaguarda', 'descricao'];
        $this->fields = [
            'id_retaguarda',
            'descricao',
            'status',
            'bloqueia'
        ];
    }
}
