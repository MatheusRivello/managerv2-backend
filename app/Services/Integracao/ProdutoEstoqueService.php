<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Produto;
use App\Models\Tenant\ProdutoEstoque;

class ProdutoEstoqueService extends IntegracaoService
{
    protected $produtos;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ProdutoEstoque::class;
        $this->path = PRODUTO_ESTOQUE_INTEG;
        $this->where = fn ($obj) => [
            'id_produto' => $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id,
            'unidade' => $obj->unidade
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->unidade = $obj->unidade;
            $model->quantidade = $obj->quantidade;
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
