<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\ClienteVisitaPlanner;
use App\Services\BaseService;

class ClienteVisitaPlannerExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->filters = ['id','id_cliente','id_vendedor','id_setor'];
        $this->Entity = ClienteVisitaPlanner::class;
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_VISITA_PLANNER;
        $this->firstOrNew = ['id_cliente','id_vendedor','id_setor'];
        $this->fields = [
            'id_cliente',
            'id_vendedor',
            'prioridade',
            'ordem',
            'dias',
            'id_setor'
        ];
    }
}