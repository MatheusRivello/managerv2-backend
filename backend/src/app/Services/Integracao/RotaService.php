<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Rota;

class RotaService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Rota::class;
        $this->path = ROTA_INTEG;
        $this->where = fn ($obj) => ['id_filial' => $obj->id_filial,'id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = intVal($obj->id_filial);
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->descricao = $obj->descricao;
            $model->rota_frete = $obj->rota_frete;
            $model->rota_tipo_frete = $obj->rota_tipo_frete;

            return $model;
        };
    }
}
