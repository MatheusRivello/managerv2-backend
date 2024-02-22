<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\ClienteTabelaGrupo;
use App\Services\BaseService;

class ClienteTabelaGrupoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ClienteTabelaGrupo::class;
        $this->filters = ['id', 'id_cliente', 'id_tabela'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CLIENTE_TABELA_PRECO_EXTERNA;
        $this->firstOrNew = ['id_cliente', 'id_tabela', 'id_grupo'];
        $this->fields = [
            'id_cliente',
            'id_tabela',
            'id_grupo'
        ];
       $this->modelComBarra="\ClienteTabelaGrupo";
    }
}
