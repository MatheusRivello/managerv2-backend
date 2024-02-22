<?php

namespace App\Services\Integracao;

use App\Models\Tenant\ProtabelaPreco;

class ProdutoTabelaPrecoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ProtabelaPreco::class;
        $this->path = PRODUTO_TABELA_PRECO_INTEG;
        $this->where = fn ($obj) => ['id_filial'=>$obj->id_filial,'id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->tab_desc = $obj->tab_desc;
            $model->tab_ini = $obj->tab_ini;
            $model->tab_fim = $obj->tab_fim;
            $model->gerar_verba = $obj->gerar_verba;
            return $model;
        };
    }
}
