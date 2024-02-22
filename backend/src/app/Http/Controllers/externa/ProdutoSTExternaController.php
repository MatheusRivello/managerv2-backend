<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;

use Illuminate\Http\Request;
use App\Models\Tenant\ProdutoSt;
use App\Services\BaseService;

class ProdutoSTExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ProdutoSt::class;
        $this->filters = ['id_produto', 'uf', 'tipo_contribuinte', 'calcula_ipi'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_PRODUTO_ST_EXTERNA;
        $this->firstOrNew = ['id_produto', 'tipo_contribuinte', 'uf', 'calcula_ipi'];
        $this->fields = [
            "id_produto",
            "tipo_contribuinte",
            "uf",
            "aliquota_icms",
            "aliquota_icms_st",
            "valor_referencia",
            "class_pauta_mva",
            "pauta",
            "tipo_mva",
            "mva",
            "reducao_icms",
            "reducao_icms_st",
            "modo_calculo",
            "calcula_ipi",
            "frete_icms",
            "frete_ipi",
            "incide_ipi_base"
        ];
        $this->modelComBarra = '\produtost';
    }
    public function storeProdutoST(Request $request)
    {
        return $this->storePersonalizado($request);
    }
}
