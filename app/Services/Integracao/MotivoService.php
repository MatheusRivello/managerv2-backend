<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Motivo;

class MotivoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Motivo::class;
        $this->path = MOTIVO_INTEG;
        $this->where = fn ($obj) => ['id_filial' => $obj->id_filial, 'descricao' => $obj->descricao];
        $this->updateFields = function ($model, $obj) {
            $model->id = $obj->id;
            $model->id_filial = $obj->id_filial;
            $model->descricao = $obj->descricao;
            $model->tipo = $obj->tipo;
            return $model;
        };
    }
}
