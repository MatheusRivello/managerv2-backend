<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\Campanha;
use App\Services\BaseService;

class CampanhaExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Campanha::class;
        $this->filters = ['id', 'descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CAMPANHA_EXTERNA;
        $this->firstOrNew = ['id', 'descricao'];
        $this->fields = [
            'id',
            'id_filial',
            'id_retaguarda',
            'descricao',
            'tipo_modalidade',
            'data_inicial',
            'data_final',
            'permite_acumular_desconto',
            'mix_minimo',
            'valor_minimo',
            'valor_maximo',
            'volume_minimo',
            'volume_maximo',
            'qtd_max_bonificacao',
            'status'
        ];
    }
}