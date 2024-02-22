<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Produto;
use App\Models\Tenant\ProdutoEmbalagem;

class ProdutoEmbalagemService extends IntegracaoService
{
    protected $produtos;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ProdutoEmbalagem::class;
        $this->path = PRODUTO_EMBALAGEM_INTEG;
        $this->where = fn ($obj) => [
            'id_produto' => $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id,
            'embalagem' => $obj->embalagem
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->unidade = $obj->unidade;
            $model->embalagem = $obj->embalagem;
            $model->fator = $obj->fator;
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
