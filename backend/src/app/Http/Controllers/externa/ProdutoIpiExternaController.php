<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;

use App\Models\Tenant\ProdutoIpi;
use App\Services\BaseService;


class ProdutoIpiExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ProdutoIpi::class;
        $this->filters = ['id_produto', 'tipi_mva', 'calcula_ipi'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_PRODUTO_Ipi_EXTERNA;
        $this->firstOrNew = ['id_produto', 'tipi_mva', 'calcula_ipi'];
        $this->fields = [
            'id_produto',
            'tipi_mva',
            'tipi_mva_simples',
            'tipi_mva_fe_nac',
            'tipi_mva_fe_imp',
            'tipi_tpcalc',
            'tipi_aliquota',
            'tipi_pauta',
            'calcula_ipi'
        ];
    }
}
