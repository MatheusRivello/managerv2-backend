<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Meta;

class MetaService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Meta::class;
        $this->path = META_INTEG;
        $this->where = fn ($obj) => ['id_filial'=>$obj->id_filial,'id_vendedor'=>$obj->id_vendedor,'id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_vendedor = $obj->id_vendedor;
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->descricao = $obj->descricao;
            $model->tot_qtd_ven = $obj->tot_qtd_ven;
            $model->tot_peso_ven = $obj->tot_peso_ven;
            $model->objetivo_vendas = $obj->objetivo_vendas;
            $model->tot_val_ven = $obj->tot_val_ven;
            $model->percent_atingido = $obj->percent_atingido;
            
            return $model;
        };
    }
}
