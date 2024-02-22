<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Campanha;

class CampanhaService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Campanha::class;
        $this->path = CAMPANHA_INTEG;
        $this->where = fn ($obj) => [
            'id_filial' => $obj->id_filial,
            'id_retaguarda' => $obj->id_retaguarda
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->descricao = $obj->descricao;
            $model->tipo_modalidade = $obj->tipo_modalidade;
            $model->data_inicial = $obj->data_inicial;
            $model->data_final = $obj->data_final;
            $model->mix_minimo = $obj->mix_minimo;
            $model->valor_minimo = $obj->valor_minimo;
            $model->volume_minimo = $obj->volume_minimo;
            $model->qtd_max_bonificacao = $obj->qtd_max_bonificacao;
            $model->status = $obj->status;
            return $model;
        };
    }
}
