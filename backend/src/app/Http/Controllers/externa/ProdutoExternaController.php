<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Produto;
use App\Services\BaseService;


class ProdutoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Produto::class;
        $this->filters = ['id_retaguarda', 'descricao', 'ncm'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_PRODUTO_EXTERNA;
        $this->firstOrNew = ['id_retaguarda', 'id_filial'];
        $this->fields = [
            'id_filial',
            'id_fornecedor',
            'id_retaguarda',
            'id_grupo',
            'id_subgrupo',
            'descricao',
            'cod_barras',
            'dun',
            'ncm',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'descricao_curta',
            'frete_gratis',
            'status',
            'pro_inicio',
            'pro_fim',
            'pro_unitario',
            'unidvenda',
            'embalagem',
            'qtd_embalagem',
            'pro_qtd_estoque',
            'pes_bru',
            'pes_liq',
            'comprimento',
            'largura',
            'espessura',
            'ult_origem',
            'ult_uf',
            'custo',
            'descto_verba',
            'aplicacao',
            'referencia',
            'tipo_estoque',
            'dt_validade',
            'multiplo',
            'integra_web',
            'dt_alteracao',
            'pw_filial',
            'APRESENTA_VENDA'
        ];
    }
}
