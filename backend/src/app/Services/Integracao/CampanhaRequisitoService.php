<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Campanha;
use App\Models\Tenant\CampanhaRequisito;

class CampanhaRequisitoService extends IntegracaoService
{
    protected $campanhas;
    
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = CampanhaRequisito::class;
        $this->path = CAMPANHA_REQUISITO_INTEG;
        $this->where = fn ($obj) => [
            'id_campanha' => $this->campanhas[$obj->id_filial . '-' . $obj->id_campanha]->id,
            'id_retaguarda' => $obj->id_retaguarda
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->id_campanha = $this->campanhas[$obj->id_filial . '-' . $obj->id_campanha]->id;
            $model->tipo = $obj->tipo;
            $model->codigo = $obj->codigo;
            $model->quantidade = $obj->quantidade;
            $model->quantidade_max = $obj->quantidade_max;
            $model->percentual_desconto = $obj->percentual_desconto;
            $model->obrigatorio = $obj->obrigatorio;
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
