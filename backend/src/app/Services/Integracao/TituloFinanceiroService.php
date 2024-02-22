<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\FormaPagamento;
use App\Models\Tenant\TituloFinanceiro;

class TituloFinanceiroService extends IntegracaoService
{
    protected $formasPagamento;
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);

        $this->ModelClass = TituloFinanceiro::class;
        $this->path = TITULO_FINANCEIRO_INTEG;
        $this->where = fn ($obj) => ['id_retaguarda' => $obj->id_retaguarda, 'numero_doc' => $obj->numero_doc];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->descricao = $obj->descricao;
            $model->numero_doc = $obj->numero_doc;
            $model->parcela = $obj->parcela;
            $model->tipo_titulo = $obj->tipo_titulo;
            $model->dt_vencimento = $obj->dt_vencimento;
            $model->dt_pagamento = $obj->dt_pagamento;
            $model->dt_emissao = $obj->dt_emissao;
            $model->valor = $obj->valor;
            $model->multa_juros = $obj->multa_juros;
            $model->status = $obj->status;
            $model->valor_original = $obj->valor_original;
            $model->id_forma_pgto = $this->formasPagamento[$obj->id_forma_pgto]->id;
            $model->linha_digitavel = $obj->linha_digitavel;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->formasPagamento = FormaPagamento::get()->keyBy('id_retaguarda');

        $this->clientes = Cliente::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
    }
}
