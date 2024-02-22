<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Aviso;

class AvisoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Aviso::class;
        $this->path = AVISO_INTEG;
        $this->where = fn ($obj) => [
            'id_filial' => $obj->id_filial,
            'descricao' => $obj->descricao
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->descricao = $obj->descricao;
            $model->dt_inicio = $obj->dt_inicio;
            $model->dt_fim = null;
            $model->tipo = 0;
            return $model;
        };
    }
}
