<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Filial;

class FilialService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Filial::class;
        $this->path = FILIAL_INTEG;
        $this->where = fn ($obj) => ['emp_cgc' => $obj->emp_cgc];
        $this->updateFields = function ($model, $obj) {
            $model->id = $obj->id;
            $model->emp_cgc = $obj->emp_cgc;
            $model->emp_raz = $obj->emp_raz;
            $model->emp_fan = $obj->emp_fan;
            $model->emp_ativa = $obj->emp_ativa;
            $model->emp_uf = $obj->emp_uf;
            $model->emp_email = $obj->emp_email;
            return $model;
        };
    }
}