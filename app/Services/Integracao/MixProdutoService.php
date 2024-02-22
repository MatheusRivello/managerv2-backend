<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\MixProduto;
use App\Models\Tenant\Produto;

class MixProdutoService extends IntegracaoService
{
    protected $produtos;
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = MixProduto::class;
        $this->path = MIX_PRODUTO_INTEG;
        $this->where = fn($obj) => [
            'id_produto' => $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id,
            'id_cliente' => $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->qtd_minima = $obj->qtd_minima;
            $model->qtd_faturada = $obj->qtd_faturada;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->produtos = Produto::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
        $this->clientes = Cliente::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
    }
}