<?php

namespace App\Services\Integracao;

use App\Models\Tenant\TipoPedido;

class TipoPedidoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = TipoPedido::class;
        $this->path = TIPO_PEDIDO_INTEG;
        $this->where = fn ($obj) => ['id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->descricao = $obj->descricao;
            $model->status = $obj->status ?? true;
            return $model;
        };
    }
}
