<?php

namespace App\Services\Integracao;

use App\Models\Tenant\FormaPagamento;

class FormaPagamentoService extends IntegracaoService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = FormaPagamento::class;
        $this->path = FORMA_PAGAMENTO_INTEG;
        $this->where = fn ($obj) => ['id_retaguarda' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->descricao = $obj->descricao;
            $model->status = $obj->status;
            $model->valor_min = $obj->valor_min;
            $model->situacao = $obj->situacao;
            return $model;
        };
    }
}