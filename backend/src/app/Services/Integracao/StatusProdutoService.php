<?php

namespace App\Services\Integracao;

use App\Models\Tenant\StatusProduto;

class StatusProdutoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = StatusProduto::class;
        $this->path = STATUS_PRODUTO_INTEG;
        $this->where = fn ($obj) => ['id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = trim($obj->id_retaguarda);
            $model->descricao = $obj->descricao;
            $model->cor = $obj->cor;
            $model->status = 1;

            return $model;
        };
    }
}
