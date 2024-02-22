<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Fornecedor;
use App\Models\Tenant\Produto;
use App\Models\Tenant\StatusProduto;

class ProdutoService extends IntegracaoService
{
    protected $statusProduto;
    protected $fornecedores;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Produto::class;
        $this->path = PRODUTO_INTEG;
        $this->where = fn ($obj) => [
            'id_filial' => $obj->id_filial,
            'id_retaguarda' => $obj->id_retaguarda
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->id_grupo = $obj->id_grupo;
            $model->id_subgrupo = $obj->id_subgrupo;
            $model->descricao = $obj->descricao;
            $model->status = $this->statusProduto[$obj->status]->id;
            $model->pro_inicio = $obj->pro_inicio;
            $model->pro_fim = $obj->pro_fim;
            $model->pro_unitario = $obj->pro_unitario;
            $model->unidvenda = $obj->unidvenda;
            $model->embalagem = $obj->embalagem;
            $model->pro_qtd_estoque = $obj->pro_qtd_estoque;
            $model->pes_bru = $obj->pes_bru;
            $model->pes_liq = $obj->pes_liq;
            $model->ult_uf = $obj->ult_uf;
            $model->ult_origem = $obj->ult_origem;
            $model->custo = $obj->custo;
            $model->cod_barras = $obj->cod_barras;
            $model->aplicacao = $obj->aplicacao;
            $model->referencia = $obj->referencia;
            $model->descto_verba = $obj->descto_verba;
            $model->tipo_estoque = $obj->tipo_estoque;
            $model->dt_validade = $obj->dt_validade;
            $model->id_fornecedor = $this->fornecedores[$obj->id_filial . '-' . $obj->id_fornecedor]->id;
            $model->qtd_embalagem = $obj->qtd_embalagem;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->statusProduto = StatusProduto::get()->keyBy('id_retaguarda');

        $this->fornecedores = Fornecedor::get()
        ->keyBy(function($fornecedor) {
            return $fornecedor->id_filial . '-' . $fornecedor->id_retaguarda;
        });
    }
}
