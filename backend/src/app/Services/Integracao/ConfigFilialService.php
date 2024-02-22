<?php

namespace App\Services\Integracao;

use App\Models\Tenant\ConfiguracaoFilial;

class ConfigFilialService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ConfiguracaoFilial::class;
        $this->path = CONFIG_FILIAL_INTEG;
        $this->where = fn($obj) => ['descricao' => $obj->descricao];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->descricao = $obj->descricao;
            $model->valor = $obj->valor;
            $model->tipo = $obj->tipo;
            return $model;
        };
    }
}