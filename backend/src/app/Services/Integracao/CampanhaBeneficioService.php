<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Campanha;
use App\Models\Tenant\CampanhaBeneficio;

class CampanhaBeneficioService extends IntegracaoService
{
    protected $campanhas;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = CampanhaBeneficio::class;
        $this->path = CAMPANHA_BENEFICIO_INTEG;
        $this->where = fn ($obj) => [
            'id_retaguarda' => $obj->id_retaguarda,
            'id_campanha' => $this->campanhas[$obj->id_filial . '-' . $obj->id_campanha]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->id_campanha = $this->campanhas[$obj->id_filial . '-' . $obj->id_campanha]->id;
            $model->tipo = $obj->tipo;
            $model->codigo = $obj->codigo;
            $model->quantidade = $obj->quantidade;
            $model->percentual_desconto = $obj->percentual_desconto;
            $model->desconto_automatico = $obj->desconto_automatico;
            $model->bonificacao_automatica = $obj->bonificacao_automatica;
            $model->status = $obj->status;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->campanhas = Campanha::get()
        ->keyBy(function($campanha) {
            return $campanha->id_filial . '-' . $campanha->id_retaguarda;
        });
    }
}
