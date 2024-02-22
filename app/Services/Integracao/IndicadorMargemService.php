<?php

namespace App\Services\Integracao;

use App\Models\Tenant\IndicadorMargem;

class IndicadorMargemService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = IndicadorMargem::class;
        $this->path = INDICADOR_MARGEM_INTEG;
        $this->where = fn ($obj) => [
            'id_filial' => $obj->id_filial,
            'nivel' => $obj->nivel
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->nivel = $obj->nivel;
            $model->de = $obj->de;
            $model->ate = $obj->ate;
            $model->indice = $obj->indice;
            return $model;
        };
    }
}
