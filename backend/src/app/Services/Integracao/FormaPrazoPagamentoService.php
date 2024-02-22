<?php

namespace App\Services\Integracao;

use App\Models\Tenant\FormaPagamento;
use App\Models\Tenant\FormaPrazoPgto;
use App\Models\Tenant\PrazoPagamento;

class FormaPrazoPagamentoService extends IntegracaoService
{
    protected $prazosPagamento;
    protected $formasPagamento;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = FormaPrazoPgto::class;
        $this->path = FORMA_PRAZO_PGTO_INTEG;
        $this->where = fn($obj) => [
            'id_prazo_pgto'=> $this->prazosPagamento[$obj->id_prazo_pgto]->id,
            'id_forma_pgto' => $this->formasPagamento[$obj->id_forma_pgto]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_prazo_pgto = $this->prazosPagamento[$obj->id_prazo_pgto]->id;
            $model->id_forma_pgto = $this->formasPagamento[$obj->id_forma_pgto]->id;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->prazosPagamento = PrazoPagamento::get()->keyBy('id_retaguarda');
        $this->formasPagamento = FormaPagamento::get()->keyBy('id_retaguarda');
    }
}