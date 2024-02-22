<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\VisitaSetores;
use App\Services\BaseService;

class VisitaSetoresExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = VisitaSetores::class;
        $this->filters = ['id', 'id_filial', 'status'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_VISITA_SETORES;
        $this->firstOrNew = ['id','id_filial'];
        $this->fields = [ 
        'id_filial', 
        'descricao',
        'cor',
        'status'
        ];
    }
}
