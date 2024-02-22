<?php

namespace App\Services\Integracao;

use App\Models\Tenant\StatusPedido;

class StatusPedidoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = StatusPedido::class;
        $this->path = STATUS_PEDIDO_INTEG;
        $this->where = fn ($obj) => ['id_filial' => $obj->id_filial,'id_pedido' => $obj->id_pedido];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_pedido = $obj->id_pedido;
            $model->data = $obj->data;
            $model->status = $obj->status;

            return $model;
        };
    }
}
