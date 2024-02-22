<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Grupo;

class ProdutoGrupoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Grupo::class;
        $this->path = PRODUTO_GRUPO_INTEG;
        $this->where = fn ($obj) => ['id_filial'=>$obj->id_filial,'id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->grupo_desc = $obj->grupo_desc;
            $model->descto_max = $obj->descto_max;
            $model->status = $obj->status;
            return $model;
        };
    }
}
