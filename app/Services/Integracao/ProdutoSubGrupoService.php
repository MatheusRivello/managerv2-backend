<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Subgrupo;

class ProdutoSubGrupoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Subgrupo::class;
        $this->path = SUBGRUPO_INTEG;
        $this->where = fn ($obj) => ['id_filial'=>$obj->id_filial,'id_retaguarda' => $obj->id_retaguarda, 'id_grupo' => $obj->id_grupo];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_grupo = $obj->id_grupo;
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->subgrupo_desc = $obj->subgrupo_desc;
            $model->descto_max = $obj->descto_max;
            $model->status = $obj->status;
            return $model;
        };
    }
}
