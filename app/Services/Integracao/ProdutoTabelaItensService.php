<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Produto;
use App\Models\Tenant\ProtabelaIten;
use App\Models\Tenant\ProtabelaPreco;

class ProdutoTabelaItensService extends IntegracaoService
{
    protected $produtos;
    protected $produtoTabelaPrecos;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ProtabelaIten::class;
        $this->path = PROTABELAITEN_INTEG;
        $this->where = fn ($obj) => [
            'id_produto' => $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id,
            'id_protabela_preco' => $this->produtoTabelaPrecos[$obj->id_filial . '-' . $obj->id_protabela_preco]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->id_protabela_preco = $this->produtoTabelaPrecos[$obj->id_filial . '-' . $obj->id_protabela_preco]->id;
            $model->unitario = $obj->unitario;
            $model->status = $obj->status;
            $model->qevendamax = $obj->qevendamax;
            $model->qevendamin = $obj->qevendamin;
            $model->desconto = $obj->desconto;
            $model->desconto2 = $obj->desconto2;
            $model->desconto3 = $obj->desconto3;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->produtos = Produto::get()
        ->keyBy(function($produto) {
            return $produto->id_filial . '-' . $produto->id_retaguarda;
        });
        $this->produtoTabelaPrecos = ProtabelaPreco::get()
        ->keyBy(function($preco) {
            return $preco->id_filial . '-' . $preco->id_retaguarda;
        });
    }
}
