<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Produto;
use App\Models\Tenant\ProdutoDesctoQtd;
use App\Models\Tenant\ProtabelaPreco;

class ProdutoDescontoQuantidadeService extends IntegracaoService
{
    protected $produtos;
    protected $proTabelaPrecos;
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass =  ProdutoDesctoQtd::class;
        $this->path = PRODUTO_DESCONTO_QUANTIDADE_INTEG;
        $this->where = fn ($obj) => [
            'id_produto' => $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id,
            'id_protabela_preco' => $this->proTabelaPrecos[$obj->id_filial . '-' . $obj->id_protabela_preco]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->id_protabela_preco = $this->proTabelaPrecos[$obj->id_filial . '-' . $obj->id_protabela_preco]->id;
            $model->quantidade = $obj->quantidade;
            $model->desconto = $obj->desconto;

            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->produtos = Produto::get()
        ->keyBy(function($produto) {
            return $produto->id_filial . '-' . $produto->id_retaguarda;
        });;
        $this->proTabelaPrecos = ProtabelaPreco::get()
        ->keyBy(function($preco) {
            return $preco->id_filial . '-' . $preco->id_retaguarda;
        });
    }
}
