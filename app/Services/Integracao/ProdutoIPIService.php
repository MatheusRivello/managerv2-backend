<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Produto;
use App\Models\Tenant\ProdutoIpi;

class ProdutoIPIService extends IntegracaoService
{
    protected $produtos;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ProdutoIpi::class;
        $this->path = PRODUTO_IPI_INTEG;
        $this->where = fn ($obj) => [
            'id_produto' => $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->tipi_mva = $obj->tipi_mva;
            $model->tipi_mva_simples = $obj->tipi_mva_simples;
            $model->tipi_mva_fe_nac = $obj->tipi_mva_fe_nac;
            $model->tipi_mva_fe_imp = $obj->tipi_mva_fe_imp;
            $model->tipi_tpcalc = $obj->tipi_tpcalc;
            $model->tipi_aliquota = $obj->tipi_aliquota;
            $model->tipi_pauta = $obj->tipi_pauta;

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
