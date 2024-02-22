<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Integracao;
use App\Services\BaseService;
use Illuminate\Http\Request;

class IntegracaoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Integracao::class;
        $this->tabela='integracao';
        $this->modelComBarra = '\Integracao';
        $this->filters = ['id', 'integrador', 'id_interno', 'id_externo'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_INTEGRACAO_EXTERNA;
        $this->firstOrNew = ['id', 'integrador', 'id_interno', 'id_externo'];
        $this->fields = [
            'integrador',
            'tipo',
            'id_interno',
            'id_externo',
            'campo_extra_1',
            'campo_extra_2',
            'campo_extra_3',
            'ultimo_status',
            'dt_modificado'
        ];
    }

    public function storeIntegracao(Request $request)
    {
        $where = ['integrador' => $request->integrador, 'id_interno' => $request->idInterno, 'id_externo' => $request->idExterno];
        $this->destroyWhere($where);
        return $this->storePersonalizado($request);
    }
}
