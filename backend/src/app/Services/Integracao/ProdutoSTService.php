<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Produto;
use App\Models\Tenant\ProdutoSt;

class ProdutoSTService extends IntegracaoService
{
    protected $produtos;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ProdutoSt::class;
        $this->path = PRODUTO_ST_INTEG;
        $this->where = fn ($obj) => [
            'id_produto' => $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->tipo_contribuinte = $obj->tipo_contribuinte;
            $model->uf = $obj->uf;
            $model->aliquota_icms = $obj->aliquota_icms;
            $model->aliquota_icms_st = $obj->aliquota_icms_st;
            $model->valor_referencia = $obj->valor_referencia;
            $model->pauta = $obj->pauta;
            $model->reducao_icms = $obj->reducao_icms;
            $model->reducao_icms_st = $obj->reducao_icms_st;
            $model->modo_calculo = $obj->modo_calculo;
            $model->calcula_ipi = $obj->calcula_ipi;
            $model->frete_icms = $obj->frete_icms;
            $model->frete_ipi = $obj->frete_ipi;

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
