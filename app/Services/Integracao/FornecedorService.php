<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Fornecedor;

class FornecedorService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Fornecedor::class;
        $this->path = FORNECEDOR_INTEG;
        $this->where = fn($obj) => ['id_filial' => $obj->id_filial,'id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->razao_social = $obj->razao_social;
            $model->nome_fantasia = $obj->nome_fantasia;
            $model->status = $obj->status;
            return $model;
        };
    }
}