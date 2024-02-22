<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Log;
use App\Services\BaseService;

class LogExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Log::class;
        $this->filters = ['id', 'tipo', "id_empresa", "id_cliente", "mac", "id_filial"];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_LOG_EXTERNA;
        $this->firstOrNew = ['id', 'tipo'];
        $this->fields = [
            'tipo',
            'id_empresa',
            'mac',
            'id_cliente',
            'id_filial',
            'tabela',
            'conteudo',
            'mensagem',
            'ip',
            'dt_cadastro'
        ];
    }
}
