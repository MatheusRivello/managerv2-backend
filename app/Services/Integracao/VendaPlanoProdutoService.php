<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\Produto;
use App\Models\Tenant\VendaPlanoProduto;

class VendaPlanoProdutoService extends IntegracaoService
{
    protected $produtos;
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = VendaPlanoProduto::class;
        $this->path = VENDA_PLANO_PRODUTO_INTEG;
        $this->where = fn ($obj) => ['id_filial' => $obj->id_filial, 'id_cliente' => $obj->id_cliente, 'nfs_num' => $obj->nfs_num,'id_produto' => $obj->id_produto];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->nfs_num = $obj->nfs_num;
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->qtd_disponivel = $obj->qtd_disponivel;
            $model->qtd_contratada = $obj->qtd_contratada;
            $model->qtd_entregue = $obj->qtd_entregue;
            $model->valor_unitario = $obj->valor_unitario;
            $model->unidade = $obj->unidade;

            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->produtos = Produto::get()
        ->keyBy(function($produto) {
            return $produto->id_filial . '-' . $produto->id_retaguarda;
        });

        $this->clientes = Cliente::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
    }
}