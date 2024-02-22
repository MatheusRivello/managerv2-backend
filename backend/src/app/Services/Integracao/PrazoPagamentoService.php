<?php

namespace App\Services\Integracao;

use App\Models\Tenant\PrazoPagamento;

class PrazoPagamentoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = PrazoPagamento::class;
        $this->path = PRAZO_PAGAMENTO_INTEG;
        $this->where = fn ($obj) => ['id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->descricao = $obj->descricao;
            $model->variacao = $obj->variacao;
            $model->status = $obj->status;
            $model->valor_min = $obj->valor_min;
            return $model;
        };
    }
}
