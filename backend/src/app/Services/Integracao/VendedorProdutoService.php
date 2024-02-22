<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Produto;
use App\Models\Tenant\VendedorProduto;

class VendedorProdutoService extends IntegracaoService
{
    protected $produtos;
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = VendedorProduto::class;
        $this->path = VENDEDOR_PRODUTO_INTEG;
        $this->where = fn($obj) => [
            'id_produto' => $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id,
            'id_vendedor'=> $obj->id_vendedor
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->id_vendedor = $obj->id_vendedor;
            
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->produtos = Produto::get()
        ->keyBy(function($produto) {
            return $produto->id_filial . '-' . $produto->id_retaguarda;
        });
    }
}