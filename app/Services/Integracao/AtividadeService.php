<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Atividade;

class AtividadeService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Atividade::class;
        $this->path = AFV_CONFIGURACAO;
        $this->where = fn ($obj) => [
            'id_filial' => $obj->id_filial,
            'descricao' => $obj->descricao
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->descricao = $obj->descricao;
            $model->status = 1;
            return $model;
        };
    }
}
