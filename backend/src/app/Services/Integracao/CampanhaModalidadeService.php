<?php
namespace App\Services\Integracao;

use App\Models\Tenant\Campanha;
use App\Models\Tenant\CampanhaModalidade;

class CampanhaModalidadeService extends IntegracaoService
{
    protected $campanhas;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = CampanhaModalidade::class;
        $this->path = CAMPANHA_MODALIDADE_INTEG;
        $this->where = fn($obj) => [
            'id_campanha' => $this->campanhas[$obj->id_filial . '-' . $obj->id_campanha]->id,
            'id_retaguarda' => $obj->id_retaguarda,
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_campanha = $this->campanhas[$obj->id_filial . '-' . $obj->id_campanha]->id;
            $model->id_retaguarda = $obj->id_retaguarda;
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