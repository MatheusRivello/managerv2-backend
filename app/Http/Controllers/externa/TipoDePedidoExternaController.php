<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\TipoPedido;
use App\Services\BaseService;

class TipoDePedidoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = TipoPedido::class;
        $this->filters = ['id', 'id_retaguarda','descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_TIPO_PEDIDO_EXTERNA;
        $this->firstOrNew = ['id', 'id_retaguarda'];
        $this->fields = [
            'id',
            'id_retaguarda',
            'descricao',
            'status'
        ];
    }
}