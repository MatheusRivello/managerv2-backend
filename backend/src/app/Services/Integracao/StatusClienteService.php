<?php

namespace App\Services\Integracao;

use App\Models\Tenant\StatusCliente;

class StatusClienteService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = StatusCliente::class;
        $this->path = STATUS_CLIENTE_INTEG;
        $this->where = fn ($obj) => ['id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = trim($obj->id_retaguarda);
            $model->descricao = $obj->descricao;
            $model->bloqueia = $obj->bloqueia;

            return $model;
        };
    }
}
