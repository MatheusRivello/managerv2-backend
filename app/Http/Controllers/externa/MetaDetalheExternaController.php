<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\MetaDetalhe;
use App\Services\BaseService;


class MetaDetalheExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = MetaDetalhe::class;
        $this->filters = ['id', 'id_meta', 'descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_META_DETALHE_EXTERNA;
        $this->firstOrNew = ['id_meta', 'ordem', 'descricao'];
        $this->fields = [
            'id_meta',
            'ordem',
            'descricao',
            'tot_cli_cadastrados',
            'tot_cli_atendidos',
            'percent_tot_cli_atendidos',
            'tot_qtd_ven',
            'tot_peso_ven',
            'percent_tot_peso_ven',
            'tot_val_ven',
            'percent_tot_val_ven',
            'objetivo_vendas',
            'percent_atingido',
            'tendencia_vendas',
            'percent_tendencia_ven',
            'objetivo_clientes',
            'numero_cli_falta_atender',
            'ped_a_faturar',
            'prazo_medio',
            'percent_desconto',
            'tot_desconto'
        ];
    }
}
